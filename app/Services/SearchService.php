<?php

namespace App\Services;

use App\Models\Materia;
use App\Models\Edicao;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class SearchService
{
    /**
     * Busca inteligente com full-text e filtros
     */
    public function search(array $params = [])
    {
        $query = Materia::with(['tipo', 'orgao', 'edicoes'])
            ->select('materias.*');

        // Busca por texto completo
        if (!empty($params['q'])) {
            $searchTerm = $this->cleanSearchTerm($params['q']);
            
            // Se for um número, busca por número da matéria ou processo
            if (is_numeric($searchTerm)) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('numero', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('processo_numero', 'LIKE', "%{$searchTerm}%");
                });
            } else {
                // Busca full-text
                $query->whereRaw('MATCH(titulo, texto) AGAINST(? IN BOOLEAN MODE)', [$searchTerm]);
                
                // Adicionar score de relevância
                $query->selectRaw('materias.*, MATCH(titulo, texto) AGAINST(? IN BOOLEAN MODE) as relevance_score', [$searchTerm]);
                $query->orderBy('relevance_score', 'desc');
            }
        }

        // Filtro por tipo de documento
        if (!empty($params['tipo_id'])) {
            $query->where('tipo_id', $params['tipo_id']);
        }

        // Filtro por órgão
        if (!empty($params['orgao_id'])) {
            $query->where('orgao_id', $params['orgao_id']);
        }

        // Filtro por período
        if (!empty($params['data_inicio'])) {
            $query->whereDate('data', '>=', $params['data_inicio']);
        }

        if (!empty($params['data_fim'])) {
            $query->whereDate('data', '<=', $params['data_fim']);
        }

        // Filtro por ano
        if (!empty($params['ano'])) {
            $query->whereYear('data', $params['ano']);
        }

        // Filtro por mês
        if (!empty($params['mes'])) {
            $query->whereMonth('data', $params['mes']);
        }

        // Filtro por palavras-chave
        if (!empty($params['keywords'])) {
            $keywords = is_array($params['keywords']) ? $params['keywords'] : explode(',', $params['keywords']);
            
            $query->where(function($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $keyword = trim($keyword);
                    $q->orWhereJsonContains('keywords', $keyword)
                      ->orWhere('titulo', 'LIKE', "%{$keyword}%")
                      ->orWhere('texto', 'LIKE', "%{$keyword}%");
                }
            });
        }

        // Filtro por tags
        if (!empty($params['tags'])) {
            $tags = is_array($params['tags']) ? $params['tags'] : explode(',', $params['tags']);
            
            foreach ($tags as $tag) {
                $query->whereJsonContains('tags', trim($tag));
            }
        }

        // Filtro por número do processo
        if (!empty($params['processo'])) {
            $query->where('processo_numero', 'LIKE', '%' . $params['processo'] . '%');
        }

        // Filtro por status
        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }

        // Ordenação
        if (empty($params['q'])) {
            $query->orderBy($params['sort'] ?? 'data', $params['direction'] ?? 'desc');
        }

        // Somente matérias aprovadas para busca pública
        if (!($params['include_pending'] ?? false)) {
            $query->where('status', 'aprovado');
        }

        return $query;
    }

    /**
     * Busca rápida para autocomplete
     */
    public function quickSearch(string $term, int $limit = 10)
    {
        $term = $this->cleanSearchTerm($term);
        
        return Materia::with(['tipo', 'orgao'])
            ->where('status', 'aprovado')
            ->where(function($query) use ($term) {
                $query->where('titulo', 'LIKE', "%{$term}%")
                      ->orWhere('numero', 'LIKE', "%{$term}%")
                      ->orWhere('processo_numero', 'LIKE', "%{$term}%");
            })
            ->orderBy('data', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($materia) {
                return [
                    'id' => $materia->id,
                    'titulo' => $materia->titulo,
                    'numero' => $materia->numero,
                    'tipo' => $materia->tipo->nome,
                    'orgao' => $materia->orgao->nome,
                    'data' => $materia->data->format('d/m/Y'),
                    'url' => route('portal.materias.show', $materia)
                ];
            });
    }

    /**
     * Busca sugestões baseadas em termos similares
     */
    public function getSuggestions(string $term)
    {
        $term = $this->cleanSearchTerm($term);
        
        // Buscar termos similares nos títulos
        $suggestions = DB::table('materias')
            ->select('titulo')
            ->where('status', 'aprovado')
            ->where('titulo', 'LIKE', "%{$term}%")
            ->distinct()
            ->limit(5)
            ->pluck('titulo')
            ->map(function($titulo) use ($term) {
                // Extrair palavras relevantes do título
                $words = str_word_count(strtolower($titulo), 1, 'áéíóúàèìòùâêîôûãõçÁÉÍÓÚÀÈÌÒÙÂÊÎÔÛÃÕÇ');
                $relevantWords = array_filter($words, function($word) use ($term) {
                    return strlen($word) > 3 && stripos($word, $term) !== false;
                });
                return array_slice($relevantWords, 0, 3);
            })
            ->flatten()
            ->unique()
            ->values();

        return $suggestions;
    }

    /**
     * Estatísticas de busca
     */
    public function getSearchStats()
    {
        return [
            'total_documents' => Materia::where('status', 'aprovado')->count(),
            'total_types' => DB::table('tipos')->count(),
            'total_organs' => DB::table('orgaos')->count(),
            'latest_publication' => Materia::where('status', 'aprovado')
                ->orderBy('data', 'desc')
                ->first(['data', 'titulo']),
            'date_range' => [
                'from' => Materia::where('status', 'aprovado')->min('data'),
                'to' => Materia::where('status', 'aprovado')->max('data')
            ]
        ];
    }

    /**
     * Busca por edições
     */
    public function searchEdicoes(array $params = [])
    {
        $query = Edicao::with(['materias.tipo', 'materias.orgao']);

        if (!empty($params['numero'])) {
            $query->where('numero', 'LIKE', '%' . $params['numero'] . '%');
        }

        if (!empty($params['data_inicio'])) {
            $query->whereDate('data', '>=', $params['data_inicio']);
        }

        if (!empty($params['data_fim'])) {
            $query->whereDate('data', '<=', $params['data_fim']);
        }

        if (!empty($params['ano'])) {
            $query->whereYear('data', $params['ano']);
        }

        if (!empty($params['mes'])) {
            $query->whereMonth('data', $params['mes']);
        }

        if (!empty($params['tipo'])) {
            $query->where('tipo', $params['tipo']);
        }

        return $query->orderBy('data', 'desc');
    }

    /**
     * Limpa e prepara termo de busca
     */
    private function cleanSearchTerm(string $term): string
    {
        // Remove caracteres especiais mas mantém acentos
        $term = preg_replace('/[^\p{L}\p{N}\s\-]/u', '', $term);
        
        // Remove espaços extras
        $term = preg_replace('/\s+/', ' ', trim($term));
        
        // Para busca boolean mode, adiciona wildcards
        $words = explode(' ', $term);
        $booleanTerm = '';
        
        foreach ($words as $word) {
            if (strlen($word) > 2) {
                $booleanTerm .= '+' . $word . '* ';
            }
        }
        
        return trim($booleanTerm);
    }

    /**
     * Destacar termos de busca no texto
     */
    public function highlightSearchTerms(string $text, string $searchTerm): string
    {
        if (empty($searchTerm)) {
            return $text;
        }

        $words = explode(' ', str_replace(['+', '*'], '', $searchTerm));
        
        foreach ($words as $word) {
            if (strlen($word) > 2) {
                $text = preg_replace(
                    '/(' . preg_quote($word, '/') . ')/ui',
                    '<mark class="bg-warning">$1</mark>',
                    $text
                );
            }
        }

        return $text;
    }

    /**
     * Gerar resumo do texto com destaque
     */
    public function generateSummary(string $text, string $searchTerm = '', int $length = 300): string
    {
        if (empty($searchTerm)) {
            return substr(strip_tags($text), 0, $length) . (strlen($text) > $length ? '...' : '');
        }

        $words = explode(' ', str_replace(['+', '*'], '', $searchTerm));
        $sentences = preg_split('/[.!?]+/', $text);
        
        // Encontrar frase mais relevante
        $bestSentence = '';
        $maxScore = 0;
        
        foreach ($sentences as $sentence) {
            $score = 0;
            foreach ($words as $word) {
                if (strlen($word) > 2) {
                    $score += substr_count(strtolower($sentence), strtolower($word));
                }
            }
            
            if ($score > $maxScore) {
                $maxScore = $score;
                $bestSentence = trim($sentence);
            }
        }

        if ($bestSentence) {
            $summary = substr($bestSentence, 0, $length);
            return $this->highlightSearchTerms($summary, $searchTerm) . (strlen($bestSentence) > $length ? '...' : '');
        }

        return substr(strip_tags($text), 0, $length) . (strlen($text) > $length ? '...' : '');
    }
}
