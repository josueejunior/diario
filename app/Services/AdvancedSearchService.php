<?php

namespace App\Services;

use App\Models\Materia;
use App\Models\Edicao;
use App\Models\Tipo;
use App\Models\Orgao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AdvancedSearchService
{
    private $sinonimos;
    private $stopWords;

    public function __construct()
    {
        $this->sinonimos = [
            'prefeitura' => ['prefeitura', 'município', 'municipal', 'administração'],
            'funcionário' => ['funcionário', 'servidor', 'agente público', 'funcionario'],
            'concurso' => ['concurso', 'seleção', 'processo seletivo'],
            'licitação' => ['licitação', 'pregão', 'tomada de preços', 'licitacao'],
            'contrato' => ['contrato', 'convênio', 'acordo', 'termo'],
            'aposentadoria' => ['aposentadoria', 'aposentação', 'reforma'],
            'exoneração' => ['exoneração', 'demissão', 'dispensa', 'exoneracao'],
            'nomeação' => ['nomeação', 'designação', 'provimento', 'nomeacao']
        ];

        $this->stopWords = [
            'a', 'o', 'e', 'de', 'da', 'do', 'das', 'dos', 'em', 'na', 'no', 'nas', 'nos',
            'para', 'por', 'com', 'como', 'que', 'se', 'um', 'uma', 'os', 'as', 'ao', 'aos',
            'à', 'às', 'pelo', 'pela', 'pelos', 'pelas', 'este', 'esta', 'estes', 'estas',
            'esse', 'essa', 'esses', 'essas', 'aquele', 'aquela', 'aqueles', 'aquelas'
        ];
    }

    /**
     * Busca inteligente com operadores e sinônimos
     */
    public function search(string $query, array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $startTime = microtime(true);
        
        // Parse da query
        $parsedQuery = $this->parseQuery($query);
        
        // Construir query base
        $materias = Materia::with(['tipo', 'orgao', 'edicoes'])
            ->where('status', 'aprovado');

        // Aplicar busca textual
        if (!empty($parsedQuery['terms']) || !empty($parsedQuery['phrases']) || !empty($parsedQuery['required'])) {
            $materias = $this->applyTextSearch($materias, $parsedQuery);
        }

        // Aplicar filtros
        $materias = $this->applyFilters($materias, $filters);

        // Contagem total para paginação
        $total = $materias->count();

        // Aplicar paginação
        $offset = ($page - 1) * $perPage;
        $results = $materias->offset($offset)->limit($perPage)->get();

        // Calcular relevância e destacar termos
        $resultsWithHighlights = $this->processResults($results, $parsedQuery, $query);

        $endTime = microtime(true);
        $searchTime = round(($endTime - $startTime) * 1000, 2);

        return [
            'results' => $resultsWithHighlights,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage),
            'search_time_ms' => $searchTime,
            'query_info' => [
                'original' => $query,
                'parsed' => $parsedQuery,
                'expanded_terms' => $this->expandTermsWithSynonyms($parsedQuery['terms']),
                'explanation' => $this->explainSearch($parsedQuery, $filters)
            ],
            'suggestions' => $this->getSuggestions($query, $total),
            'filters_applied' => $filters,
            'facets' => $this->getFacets($query, $filters)
        ];
    }

    /**
     * Parse da query com operadores booleanos
     */
    private function parseQuery(string $query): array
    {
        $result = [
            'terms' => [],
            'phrases' => [],
            'required' => [],
            'excluded' => [],
            'operators' => []
        ];

        // Extrair frases entre aspas
        preg_match_all('/"([^"]+)"/', $query, $phrases);
        foreach ($phrases[1] as $phrase) {
            $result['phrases'][] = trim($phrase);
            $query = str_replace('"' . $phrase . '"', '', $query);
        }

        // Extrair termos obrigatórios (+termo)
        preg_match_all('/\+(\w+)/', $query, $required);
        foreach ($required[1] as $term) {
            $result['required'][] = trim($term);
            $query = str_replace('+' . $term, '', $query);
        }

        // Extrair termos excluídos (-termo)
        preg_match_all('/\-(\w+)/', $query, $excluded);
        foreach ($excluded[1] as $term) {
            $result['excluded'][] = trim($term);
            $query = str_replace('-' . $term, '', $query);
        }

        // Detectar operadores OR
        if (stripos($query, ' OR ') !== false) {
            $result['operators'][] = 'OR';
        }

        // Detectar operadores AND
        if (stripos($query, ' AND ') !== false) {
            $result['operators'][] = 'AND';
        }

        // Extrair termos restantes
        $query = preg_replace('/\s+(OR|AND)\s+/i', ' ', $query);
        $terms = explode(' ', $query);
        
        foreach ($terms as $term) {
            $term = trim($term);
            if (!empty($term) && !in_array(strtolower($term), $this->stopWords)) {
                $result['terms'][] = $term;
            }
        }

        return $result;
    }

    /**
     * Aplica busca textual inteligente
     */
    private function applyTextSearch($query, array $parsedQuery)
    {
        return $query->where(function ($q) use ($parsedQuery) {
            // Busca por frases exatas
            foreach ($parsedQuery['phrases'] as $phrase) {
                $q->where(function ($subQ) use ($phrase) {
                    $subQ->where('titulo', 'LIKE', "%{$phrase}%")
                         ->orWhere('texto', 'LIKE', "%{$phrase}%")
                         ->orWhere('numero', 'LIKE', "%{$phrase}%");
                });
            }

            // Termos obrigatórios
            foreach ($parsedQuery['required'] as $term) {
                $expandedTerms = $this->expandTermWithSynonyms($term);
                $q->where(function ($subQ) use ($expandedTerms) {
                    foreach ($expandedTerms as $expTerm) {
                        $subQ->orWhere('titulo', 'LIKE', "%{$expTerm}%")
                             ->orWhere('texto', 'LIKE', "%{$expTerm}%");
                    }
                });
            }

            // Termos excluídos
            foreach ($parsedQuery['excluded'] as $term) {
                $q->where('titulo', 'NOT LIKE', "%{$term}%")
                  ->where('texto', 'NOT LIKE', "%{$term}%");
            }

            // Termos normais com sinônimos
            if (!empty($parsedQuery['terms'])) {
                $hasOr = in_array('OR', $parsedQuery['operators']);
                
                if ($hasOr) {
                    // Lógica OR
                    $q->where(function ($orQ) use ($parsedQuery) {
                        foreach ($parsedQuery['terms'] as $term) {
                            $expandedTerms = $this->expandTermWithSynonyms($term);
                            foreach ($expandedTerms as $expTerm) {
                                $orQ->orWhere('titulo', 'LIKE', "%{$expTerm}%")
                                    ->orWhere('texto', 'LIKE', "%{$expTerm}%")
                                    ->orWhere('numero', 'LIKE', "%{$expTerm}%");
                            }
                        }
                    });
                } else {
                    // Lógica AND (padrão)
                    foreach ($parsedQuery['terms'] as $term) {
                        $expandedTerms = $this->expandTermWithSynonyms($term);
                        $q->where(function ($andQ) use ($expandedTerms) {
                            foreach ($expandedTerms as $expTerm) {
                                $andQ->orWhere('titulo', 'LIKE', "%{$expTerm}%")
                                     ->orWhere('texto', 'LIKE', "%{$expTerm}%")
                                     ->orWhere('numero', 'LIKE', "%{$expTerm}%");
                            }
                        });
                    }
                }
            }
        });
    }

    /**
     * Aplica filtros avançados
     */
    private function applyFilters($query, array $filters)
    {
        if (!empty($filters['tipos'])) {
            $query->whereIn('tipo_id', $filters['tipos']);
        }

        if (!empty($filters['orgaos'])) {
            $query->whereIn('orgao_id', $filters['orgaos']);
        }

        if (!empty($filters['data_inicio'])) {
            $query->whereDate('data', '>=', $filters['data_inicio']);
        }

        if (!empty($filters['data_fim'])) {
            $query->whereDate('data', '<=', $filters['data_fim']);
        }

        if (!empty($filters['numero'])) {
            $query->where('numero', 'LIKE', "%{$filters['numero']}%");
        }

        if (!empty($filters['assinado'])) {
            if ($filters['assinado'] === 'sim') {
                $query->whereHas('assinatura');
            } elseif ($filters['assinado'] === 'nao') {
                $query->whereDoesntHave('assinatura');
            }
        }

        // Ordenação
        $sortBy = $filters['ordenar'] ?? 'relevancia';
        switch ($sortBy) {
            case 'data_desc':
                $query->orderBy('data', 'desc');
                break;
            case 'data_asc':
                $query->orderBy('data', 'asc');
                break;
            case 'titulo':
                $query->orderBy('titulo', 'asc');
                break;
            case 'tipo':
                $query->join('tipos', 'materias.tipo_id', '=', 'tipos.id')
                      ->orderBy('tipos.nome', 'asc')
                      ->select('materias.*');
                break;
            default: // relevancia
                $query->orderBy('updated_at', 'desc');
        }

        return $query;
    }

    /**
     * Processa resultados adicionando highlights e relevância
     */
    private function processResults($results, array $parsedQuery, string $originalQuery)
    {
        $allTerms = array_merge(
            $parsedQuery['terms'],
            $parsedQuery['phrases'],
            $parsedQuery['required']
        );

        return $results->map(function ($materia) use ($allTerms, $originalQuery) {
            $score = $this->calculateRelevanceScore($materia, $allTerms);
            
            return [
                'id' => $materia->id,
                'numero' => $materia->numero,
                'titulo' => $this->highlightTerms($materia->titulo, $allTerms),
                'titulo_plain' => $materia->titulo,
                'texto_resumo' => $this->generateSummary($materia->texto, $allTerms),
                'data' => $materia->data->format('d/m/Y'),
                'tipo' => [
                    'id' => $materia->tipo->id,
                    'nome' => $materia->tipo->nome,
                    'slug' => $materia->tipo->slug
                ],
                'orgao' => [
                    'id' => $materia->orgao->id,
                    'nome' => $materia->orgao->nome,
                    'sigla' => $materia->orgao->sigla
                ],
                'url' => route('portal.atos.show', $materia),
                'relevance_score' => $score,
                'relevance_explanation' => $this->explainRelevance($materia, $allTerms),
                'assinatura_digital' => $materia->assinatura ? true : false,
                'edicoes' => $materia->edicoes->map(function ($edicao) {
                    return [
                        'numero' => $edicao->numero,
                        'data' => $edicao->data->format('d/m/Y'),
                        'url' => route('portal.edicoes.show', $edicao)
                    ];
                })
            ];
        })->sortByDesc('relevance_score')->values();
    }

    /**
     * Calcula score de relevância
     */
    private function calculateRelevanceScore($materia, array $terms): float
    {
        $score = 0;

        foreach ($terms as $term) {
            $term = strtolower($term);
            
            // Pontuação por ocorrências no título (peso maior)
            $titleOccurrences = substr_count(strtolower($materia->titulo), $term);
            $score += $titleOccurrences * 3;

            // Pontuação por ocorrências no texto
            $textOccurrences = substr_count(strtolower($materia->texto), $term);
            $score += $textOccurrences * 1;

            // Pontuação por ocorrências no número
            $numberOccurrences = substr_count(strtolower($materia->numero), $term);
            $score += $numberOccurrences * 2;
        }

        // Bônus por data recente
        $daysSinceUpdate = $materia->updated_at->diffInDays(now());
        $recencyBonus = max(0, (365 - $daysSinceUpdate) / 365);
        $score += $recencyBonus;

        // Bônus por ter assinatura digital
        if ($materia->assinatura) {
            $score += 0.5;
        }

        return round($score, 2);
    }

    /**
     * Explica a relevância do resultado
     */
    private function explainRelevance($materia, array $terms): array
    {
        $explanation = [];

        foreach ($terms as $term) {
            $term = strtolower($term);
            
            $titleMatches = substr_count(strtolower($materia->titulo), $term);
            if ($titleMatches > 0) {
                $explanation[] = "'{$term}' encontrado {$titleMatches}x no título";
            }

            $textMatches = substr_count(strtolower($materia->texto), $term);
            if ($textMatches > 0) {
                $explanation[] = "'{$term}' encontrado {$textMatches}x no texto";
            }

            $numberMatches = substr_count(strtolower($materia->numero), $term);
            if ($numberMatches > 0) {
                $explanation[] = "'{$term}' encontrado no número do ato";
            }
        }

        if ($materia->assinatura) {
            $explanation[] = "Documento com assinatura digital";
        }

        $daysSinceUpdate = $materia->updated_at->diffInDays(now());
        if ($daysSinceUpdate < 30) {
            $explanation[] = "Documento recente ({$daysSinceUpdate} dias)";
        }

        return $explanation;
    }

    /**
     * Destaca termos nos resultados
     */
    private function highlightTerms(string $text, array $terms): string
    {
        foreach ($terms as $term) {
            $text = preg_replace('/(' . preg_quote($term, '/') . ')/i', '<mark>$1</mark>', $text);
        }
        return $text;
    }

    /**
     * Gera resumo do texto com contexto dos termos
     */
    private function generateSummary(string $text, array $terms): string
    {
        $text = strip_tags($text);
        $sentences = preg_split('/[.!?]+/', $text);
        $relevantSentences = [];

        foreach ($sentences as $sentence) {
            foreach ($terms as $term) {
                if (stripos($sentence, $term) !== false) {
                    $relevantSentences[] = trim($sentence);
                    break;
                }
            }
        }

        if (empty($relevantSentences)) {
            // Se não encontrou sentenças relevantes, pega o início
            return substr($text, 0, 200) . '...';
        }

        $summary = implode('. ', array_slice($relevantSentences, 0, 2));
        
        if (strlen($summary) > 300) {
            $summary = substr($summary, 0, 297) . '...';
        }

        return $this->highlightTerms($summary, $terms);
    }

    /**
     * Expande termo com sinônimos
     */
    private function expandTermWithSynonyms(string $term): array
    {
        $term = strtolower($term);
        
        foreach ($this->sinonimos as $concept => $synonyms) {
            if (in_array($term, $synonyms)) {
                return $synonyms;
            }
        }

        return [$term];
    }

    /**
     * Expande todos os termos com sinônimos
     */
    private function expandTermsWithSynonyms(array $terms): array
    {
        $expanded = [];
        
        foreach ($terms as $term) {
            $expanded = array_merge($expanded, $this->expandTermWithSynonyms($term));
        }

        return array_unique($expanded);
    }

    /**
     * Explica como a busca foi interpretada
     */
    private function explainSearch(array $parsedQuery, array $filters): array
    {
        $explanation = [];

        if (!empty($parsedQuery['phrases'])) {
            $explanation[] = 'Buscando frases exatas: ' . implode(', ', $parsedQuery['phrases']);
        }

        if (!empty($parsedQuery['required'])) {
            $explanation[] = 'Termos obrigatórios: ' . implode(', ', $parsedQuery['required']);
        }

        if (!empty($parsedQuery['excluded'])) {
            $explanation[] = 'Termos excluídos: ' . implode(', ', $parsedQuery['excluded']);
        }

        if (!empty($parsedQuery['terms'])) {
            $explanation[] = 'Termos de busca: ' . implode(', ', $parsedQuery['terms']);
        }

        if (in_array('OR', $parsedQuery['operators'])) {
            $explanation[] = 'Usando lógica OR (qualquer termo)';
        } elseif (!empty($parsedQuery['terms'])) {
            $explanation[] = 'Usando lógica AND (todos os termos)';
        }

        if (!empty($filters['tipos'])) {
            $tipos = Tipo::whereIn('id', $filters['tipos'])->pluck('nome')->toArray();
            $explanation[] = 'Filtrado por tipos: ' . implode(', ', $tipos);
        }

        if (!empty($filters['orgaos'])) {
            $orgaos = Orgao::whereIn('id', $filters['orgaos'])->pluck('sigla')->toArray();
            $explanation[] = 'Filtrado por órgãos: ' . implode(', ', $orgaos);
        }

        return $explanation;
    }

    /**
     * Gera sugestões de busca
     */
    private function getSuggestions(string $query, int $totalResults): array
    {
        $suggestions = [];

        if ($totalResults === 0) {
            // Sugerir termos similares
            $suggestions[] = 'Tente usar sinônimos: funcionário → servidor';
            $suggestions[] = 'Verifique a grafia dos termos';
            $suggestions[] = 'Use termos mais genéricos';
            $suggestions[] = 'Remova filtros para ampliar a busca';
        } elseif ($totalResults < 5) {
            $suggestions[] = 'Poucos resultados. Tente termos mais genéricos';
            $suggestions[] = 'Use operador OR para buscar qualquer termo';
        }

        // Sugerir buscas populares
        $popularSearches = Cache::remember('popular_searches', 3600, function () {
            return ['decreto', 'portaria', 'nomeação', 'licitação', 'contrato'];
        });

        if (!in_array(strtolower($query), $popularSearches)) {
            $suggestions[] = 'Buscas populares: ' . implode(', ', $popularSearches);
        }

        return $suggestions;
    }

    /**
     * Gera facetas para refinar busca
     */
    private function getFacets(string $query, array $filters): array
    {
        $baseQuery = Materia::where('status', 'aprovado');

        // Aplicar filtros existentes exceto o que queremos contar
        if (!empty($filters['orgaos'])) {
            $baseQuery->whereIn('orgao_id', $filters['orgaos']);
        }
        if (!empty($filters['data_inicio'])) {
            $baseQuery->whereDate('data', '>=', $filters['data_inicio']);
        }
        if (!empty($filters['data_fim'])) {
            $baseQuery->whereDate('data', '<=', $filters['data_fim']);
        }

        return [
            'tipos' => $baseQuery->select('tipo_id', DB::raw('count(*) as count'))
                ->join('tipos', 'materias.tipo_id', '=', 'tipos.id')
                ->groupBy('tipo_id')
                ->with('tipo')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->tipo_id,
                        'nome' => $item->tipo->nome,
                        'count' => $item->count
                    ];
                }),
            
            'orgaos' => $baseQuery->select('orgao_id', DB::raw('count(*) as count'))
                ->join('orgaos', 'materias.orgao_id', '=', 'orgaos.id')
                ->groupBy('orgao_id')
                ->with('orgao')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->orgao_id,
                        'nome' => $item->orgao->nome,
                        'sigla' => $item->orgao->sigla,
                        'count' => $item->count
                    ];
                }),

            'por_ano' => $baseQuery->select(DB::raw('YEAR(data) as ano'), DB::raw('count(*) as count'))
                ->groupBy(DB::raw('YEAR(data)'))
                ->orderBy('ano', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'ano' => $item->ano,
                        'count' => $item->count
                    ];
                })
        ];
    }
}
