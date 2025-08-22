<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use App\Models\Edicao;
use App\Services\PadesLtvService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AtoController extends Controller
{
    protected $padesService;

    public function __construct(PadesLtvService $padesService)
    {
        $this->padesService = $padesService;
    }

    /**
     * Exibe um ato específico com URL permanente
     */
    public function show(Materia $materia)
    {
        // Incrementar visualizações
        $this->registrarVisualizacao($materia);

        // Dados estruturados para SEO (schema.org)
        $structuredData = $this->gerarDadosEstruturados($materia);
        
        // Metadados ricos
        $metadata = $this->gerarMetadados($materia);
        
        return view('portal.atos.show', compact('materia', 'structuredData', 'metadata'));
    }

    /**
     * API JSON do ato
     */
    public function json(Materia $materia)
    {
        return response()->json([
            'id' => $materia->id,
            'numero' => $materia->numero,
            'titulo' => $materia->titulo,
            'texto' => $materia->texto,
            'data' => $materia->data->toISOString(),
            'tipo' => [
                'id' => $materia->tipo->id,
                'nome' => $materia->tipo->nome,
                'slug' => $materia->tipo->slug,
                'descricao' => $materia->tipo->descricao
            ],
            'orgao' => [
                'id' => $materia->orgao->id,
                'nome' => $materia->orgao->nome,
                'sigla' => $materia->orgao->sigla,
                'slug' => $materia->orgao->slug
            ],
            'status' => $materia->status,
            'metadados' => $this->gerarMetadados($materia),
            'urls' => [
                'canonical' => route('portal.atos.show', $materia),
                'json' => route('portal.atos.json', $materia),
                'pdf' => $materia->edicoes->isNotEmpty() ? route('portal.edicoes.pdf', $materia->edicoes->first()) : null
            ],
            'assinatura_digital' => $this->obterDadosAssinatura($materia),
            'publicacao' => [
                'edicoes' => $materia->edicoes->map(function ($edicao) {
                    return [
                        'id' => $edicao->id,
                        'numero' => $edicao->numero,
                        'data' => $edicao->data->toISOString(),
                        'url' => route('portal.edicoes.show', $edicao)
                    ];
                })
            ],
            'updated_at' => $materia->updated_at->toISOString(),
            'created_at' => $materia->created_at->toISOString()
        ]);
    }

    /**
     * Gera metadados ricos do ato
     */
    private function gerarMetadados(Materia $materia): array
    {
        return [
            'id_permanente' => "BR.GOV.DIARIO.{$materia->id}",
            'classificacao' => $materia->tipo->nome,
            'orgao_emissor' => $materia->orgao->nome,
            'sigla_orgao' => $materia->orgao->sigla,
            'numero_ato' => $materia->numero,
            'data_publicacao' => $materia->data->format('Y-m-d'),
            'data_assinatura' => $materia->assinatura?->carimbo_tempo?->format('Y-m-d H:i:s'),
            'base_legal' => $this->extrairBaseLegal($materia->texto),
            'assunto' => $this->extrairAssunto($materia->titulo),
            'tags' => $this->gerarTags($materia),
            'palavras_chave' => $this->extrairPalavrasChave($materia->texto),
            'formato_digital' => 'HTML/PDF',
            'assinatura_digital' => $materia->assinatura ? 'ICP-Brasil' : null,
            'validacao_ltv' => $materia->assinatura?->is_ltv_enabled ?? false,
            'hash_documento' => $materia->assinatura?->hash,
            'url_verificacao' => $materia->assinatura ? route('portal.verificar', ['hash' => $materia->assinatura->hash]) : null,
            'nivel_acesso' => 'público',
            'licenca' => 'Domínio Público'
        ];
    }

    /**
     * Gera dados estruturados para SEO (schema.org)
     */
    private function gerarDadosEstruturados(Materia $materia): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'GovernmentService',
            'name' => $materia->titulo,
            'description' => $this->gerarDescricao($materia),
            'identifier' => "BR.GOV.DIARIO.{$materia->id}",
            'url' => route('portal.atos.show', $materia),
            'publisher' => [
                '@type' => 'GovernmentOrganization',
                'name' => $materia->orgao->nome,
                'alternateName' => $materia->orgao->sigla
            ],
            'datePublished' => $materia->data->toISOString(),
            'dateModified' => $materia->updated_at->toISOString(),
            'isAccessibleForFree' => true,
            'inLanguage' => 'pt-BR',
            'audience' => [
                '@type' => 'Audience',
                'audienceType' => 'public'
            ],
            'about' => [
                '@type' => 'Thing',
                'name' => $materia->tipo->nome,
                'description' => $materia->tipo->descricao
            ],
            'mainEntity' => [
                '@type' => 'Legislation',
                'name' => $materia->titulo,
                'legislationIdentifier' => $materia->numero,
                'legislationType' => $materia->tipo->nome,
                'legislationJurisdiction' => [
                    '@type' => 'AdministrativeArea',
                    'name' => 'Municipal'
                ],
                'legislationDate' => $materia->data->toDateString()
            ]
        ];
    }

    /**
     * Obtém dados da assinatura digital
     */
    private function obterDadosAssinatura(Materia $materia): ?array
    {
        if (!$materia->assinatura) {
            return null;
        }

        $assinatura = $materia->assinatura;
        
        return [
            'presente' => true,
            'algoritmo' => $assinatura->algoritmo,
            'signatario' => $assinatura->signatario,
            'ac' => $assinatura->ac,
            'carimbo_tempo' => $assinatura->carimbo_tempo->toISOString(),
            'hash' => $assinatura->hash,
            'pades_ltv' => $assinatura->is_ltv_enabled ?? false,
            'validacao' => $this->padesService->verificarAssinaturaPAdES($assinatura),
            'qr_code_url' => route('portal.verificar') . '?hash=' . $assinatura->hash
        ];
    }

    /**
     * Extrai base legal do texto
     */
    private function extrairBaseLegal(string $texto): ?string
    {
        // Regex para encontrar referências legais comuns
        $patterns = [
            '/Lei n[º°]?\s*(\d+[\/\-]\d+)/i',
            '/Decreto n[º°]?\s*(\d+[\/\-]\d+)/i',
            '/Portaria n[º°]?\s*(\d+[\/\-]\d+)/i',
            '/Resolução n[º°]?\s*(\d+[\/\-]\d+)/i',
            '/Constituição Federal/i',
            '/Lei Orgânica/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $texto, $matches)) {
                return $matches[0];
            }
        }

        return null;
    }

    /**
     * Extrai assunto principal
     */
    private function extrairAssunto(string $titulo): string
    {
        // Remove partes comuns do início
        $titulo = preg_replace('/^(Dispõe sobre|Altera|Regulamenta|Estabelece|Institui|Nomeia|Revoga)\s*/i', '', $titulo);
        
        // Remove artigos e preposições do início
        $titulo = preg_replace('/^(a|o|os|as|de|da|do|das|dos)\s+/i', '', $titulo);
        
        return trim($titulo);
    }

    /**
     * Gera tags automáticas
     */
    private function gerarTags(Materia $materia): array
    {
        $tags = [];
        
        // Tag do tipo
        $tags[] = $materia->tipo->nome;
        
        // Tag do órgão
        $tags[] = $materia->orgao->sigla;
        
        // Tags por palavras-chave no título
        $palavrasChave = ['nomeação', 'exoneração', 'aposentadoria', 'licitação', 'contrato', 'convênio', 'regulamento'];
        
        foreach ($palavrasChave as $palavra) {
            if (stripos($materia->titulo, $palavra) !== false) {
                $tags[] = $palavra;
            }
        }
        
        return array_unique($tags);
    }

    /**
     * Extrai palavras-chave do texto
     */
    private function extrairPalavrasChave(string $texto): array
    {
        // Lista de palavras-chave relevantes para o contexto governamental
        $keywords = [
            'servidor', 'funcionário', 'cargo', 'função', 'concurso', 'seleção',
            'licitação', 'pregão', 'contrato', 'convênio', 'acordo',
            'orçamento', 'despesa', 'receita', 'tributo', 'taxa',
            'meio ambiente', 'sustentabilidade', 'licenciamento',
            'saúde', 'educação', 'assistência social', 'segurança',
            'transporte', 'trânsito', 'urbanismo', 'habitação'
        ];

        $encontradas = [];
        
        foreach ($keywords as $keyword) {
            if (stripos($texto, $keyword) !== false) {
                $encontradas[] = $keyword;
            }
        }
        
        return array_unique($encontradas);
    }

    /**
     * Gera descrição resumida
     */
    private function gerarDescricao(Materia $materia): string
    {
        $texto = strip_tags($materia->texto);
        $texto = preg_replace('/\s+/', ' ', $texto);
        
        if (strlen($texto) > 160) {
            return substr($texto, 0, 157) . '...';
        }
        
        return $texto;
    }

    /**
     * Registra visualização do ato
     */
    private function registrarVisualizacao(Materia $materia): void
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent();
        
        // Evita registrar múltiplas visualizações da mesma sessão
        $key = "materia_view_{$materia->id}_{$ip}_" . session()->getId();
        
        if (!Cache::has($key)) {
            \App\Models\Visualizacao::create([
                'materia_id' => $materia->id,
                'ip_address' => $ip,
                'user_agent' => $userAgent
            ]);
            
            // Cache por 1 hora
            Cache::put($key, true, 3600);
        }
    }

    /**
     * Sitemap XML para atos
     */
    public function sitemap()
    {
        $atos = Materia::where('status', 'aprovado')
            ->with(['tipo', 'orgao'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->view('portal.atos.sitemap', compact('atos'))
            ->header('Content-Type', 'application/xml');
    }
}
