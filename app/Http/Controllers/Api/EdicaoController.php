<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Edicao;
use App\Models\Visualizacao;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EdicaoController extends Controller
{
    /**
     * Lista edições com filtros
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min($request->get('per_page', 15), 100);
        
        $edicoes = Edicao::with(['materias.tipo', 'materias.orgao', 'assinatura'])
            ->where('publicado', true)
            ->when($request->get('ano'), function ($query, $ano) {
                $query->whereYear('data', $ano);
            })
            ->when($request->get('mes'), function ($query, $mes) {
                $query->whereMonth('data', $mes);
            })
            ->when($request->get('tipo'), function ($query, $tipo) {
                $query->where('tipo', $tipo);
            })
            ->when($request->get('numero'), function ($query, $numero) {
                $query->where('numero', 'LIKE', "%{$numero}%");
            })
            ->orderBy('data', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $edicoes->map(function ($edicao) {
                return [
                    'id' => $edicao->id,
                    'numero' => $edicao->numero,
                    'data' => $edicao->data,
                    'tipo' => $edicao->tipo,
                    'descricao' => $edicao->descricao,
                    'total_materias' => $edicao->materias->count(),
                    'materias_preview' => $edicao->materias->take(3)->map(function ($materia) {
                        return [
                            'id' => $materia->id,
                            'titulo' => $materia->titulo,
                            'tipo' => $materia->tipo->nome ?? null,
                            'orgao' => $materia->orgao->nome ?? null,
                        ];
                    }),
                    'assinada' => $edicao->assinatura ? true : false,
                    'pdf_disponivel' => $edicao->caminho_arquivo ? true : false,
                    'links' => [
                        'self' => route('api.edicoes.show', $edicao),
                        'web' => route('portal.edicoes.show', $edicao),
                        'pdf' => $edicao->caminho_arquivo ? route('portal.edicoes.pdf', $edicao) : null,
                    ],
                    'created_at' => $edicao->created_at,
                    'updated_at' => $edicao->updated_at,
                ];
            }),
            'pagination' => [
                'current_page' => $edicoes->currentPage(),
                'last_page' => $edicoes->lastPage(),
                'per_page' => $edicoes->perPage(),
                'total' => $edicoes->total(),
                'from' => $edicoes->firstItem(),
                'to' => $edicoes->lastItem(),
            ]
        ]);
    }

    /**
     * Exibe uma edição específica
     */
    public function show(Request $request, Edicao $edicao): JsonResponse
    {
        if (!$edicao->publicado) {
            return response()->json([
                'success' => false,
                'message' => 'Edição não publicada ou não encontrada'
            ], 404);
        }

        $edicao->load(['materias.tipo', 'materias.orgao', 'assinatura']);

        // Registrar visualização se não for bot
        if (!$this->isBot($request->userAgent())) {
            $this->registrarVisualizacao($edicao, $request);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $edicao->id,
                'numero' => $edicao->numero,
                'data' => $edicao->data,
                'tipo' => $edicao->tipo,
                'descricao' => $edicao->descricao,
                'publicado' => $edicao->publicado,
                'data_publicacao' => $edicao->data_publicacao,
                'caminho_arquivo' => $edicao->caminho_arquivo,
                'materias' => $edicao->materias->map(function ($materia) {
                    return [
                        'id' => $materia->id,
                        'titulo' => $materia->titulo,
                        'numero' => $materia->numero,
                        'texto' => $materia->texto,
                        'data' => $materia->data,
                        'tipo' => [
                            'id' => $materia->tipo->id ?? null,
                            'nome' => $materia->tipo->nome ?? null,
                        ],
                        'orgao' => [
                            'id' => $materia->orgao->id ?? null,
                            'nome' => $materia->orgao->nome ?? null,
                            'sigla' => $materia->orgao->sigla ?? null,
                        ],
                        'links' => [
                            'self' => route('api.materias.show', $materia),
                            'web' => route('portal.materias.show', $materia),
                        ]
                    ];
                }),
                'assinatura' => $edicao->assinatura ? [
                    'signatario' => $edicao->assinatura->signatario,
                    'cpf_signatario' => $edicao->assinatura->cpf_signatario,
                    'cargo_signatario' => $edicao->assinatura->cargo_signatario,
                    'ac' => $edicao->assinatura->ac,
                    'algoritmo' => $edicao->assinatura->algoritmo,
                    'hash' => $edicao->assinatura->hash,
                    'carimbo_tempo' => $edicao->assinatura->carimbo_tempo,
                ] : null,
                'links' => [
                    'web' => route('portal.edicoes.show', $edicao),
                    'pdf' => $edicao->caminho_arquivo ? route('portal.edicoes.pdf', $edicao) : null,
                    'materias' => route('portal.edicoes.materias', $edicao),
                ],
                'created_at' => $edicao->created_at,
                'updated_at' => $edicao->updated_at,
            ]
        ]);
    }

    /**
     * Publica uma edição (rota administrativa)
     */
    public function publicar(Request $request, Edicao $edicao): JsonResponse
    {
        if ($edicao->publicado) {
            return response()->json([
                'success' => false,
                'message' => 'Edição já está publicada'
            ], 400);
        }

        $edicao->update([
            'publicado' => true,
            'data_publicacao' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Edição publicada com sucesso',
            'data' => [
                'id' => $edicao->id,
                'numero' => $edicao->numero,
                'publicado' => $edicao->publicado,
                'data_publicacao' => $edicao->data_publicacao,
            ]
        ]);
    }

    /**
     * Assina uma edição digitalmente (rota administrativa)
     */
    public function assinar(Request $request, Edicao $edicao): JsonResponse
    {
        $request->validate([
            'signatario' => 'required|string|max:255',
            'cpf_signatario' => 'required|string|size:11',
            'cargo_signatario' => 'required|string|max:255',
            'ac' => 'string|max:255',
        ]);

        if ($edicao->assinatura) {
            return response()->json([
                'success' => false,
                'message' => 'Edição já está assinada'
            ], 400);
        }

        // Gerar hash do arquivo ou conteúdo
        $hash = hash('sha256', $edicao->numero . $edicao->data->format('Y-m-d') . $edicao->created_at);

        $assinatura = $edicao->assinatura()->create([
            'signatario' => $request->signatario,
            'cpf_signatario' => $request->cpf_signatario,
            'cargo_signatario' => $request->cargo_signatario,
            'ac' => $request->ac ?? 'AC-DIARIO',
            'algoritmo' => 'SHA-256',
            'hash' => $hash,
            'carimbo_tempo' => now(),
            'signed_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Edição assinada digitalmente com sucesso',
            'data' => [
                'id' => $edicao->id,
                'numero' => $edicao->numero,
                'assinatura' => [
                    'signatario' => $assinatura->signatario,
                    'hash' => $assinatura->hash,
                    'carimbo_tempo' => $assinatura->carimbo_tempo,
                ]
            ]
        ]);
    }

    /**
     * Verifica se o user agent é de um bot
     */
    private function isBot($userAgent): bool
    {
        $bots = [
            'bot', 'crawler', 'spider', 'scraper', 'wget', 'curl',
            'googlebot', 'bingbot', 'yahoo', 'slurp', 'facebook',
            'twitter', 'linkedin', 'whatsapp', 'telegram'
        ];

        $userAgent = strtolower($userAgent);
        
        foreach ($bots as $bot) {
            if (strpos($userAgent, $bot) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Registra visualização para estatísticas
     */
    private function registrarVisualizacao(Edicao $edicao, Request $request): void
    {
        // Evitar registros duplicados da mesma IP na última hora
        $existing = Visualizacao::where('edicao_id', $edicao->id)
            ->where('ip', $request->ip())
            ->where('created_at', '>=', now()->subHour())
            ->exists();

        if (!$existing) {
            Visualizacao::create([
                'edicao_id' => $edicao->id,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'origem' => 'api'
            ]);
        }
    }
}
