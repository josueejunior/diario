<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Edicao;
use App\Models\Materia;
use App\Models\Tipo;
use App\Models\Orgao;
use App\Models\Download;
use App\Models\Visualizacao;
use App\Services\SearchService;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Mês e ano para o calendário (padrão: atual)
        $mesCalendario = $request->get('mes', Carbon::now()->month);
        $anoCalendario = $request->get('ano', Carbon::now()->year);
        $dataCalendario = Carbon::createFromDate($anoCalendario, $mesCalendario, 1);
        
        // Última edição publicada (para o novo design será a edicaoRecente)
        $edicaoRecente = Edicao::with(['assinatura', 'assinaturas', 'visualizacoes', 'downloads'])
            ->withCount(['visualizacoes', 'downloads', 'materias'])
            ->orderBy('data', 'desc')
            ->first();
        
        // Edições recentes para a lista (com contagem de relacionamentos)
        $edicoesRecentes = Edicao::withCount(['materias', 'visualizacoes', 'downloads'])
            ->orderBy('data', 'desc')
            ->limit(10)
            ->get();
        
        // Últimas publicações (matérias mais recentes)
        $ultimasPublicacoes = Materia::with(['tipo', 'orgao'])
            ->where('status', 'aprovado')
            ->orderBy('data', 'desc')
            ->limit(8)
            ->get();
        
        // Estatísticas para o novo design
        $stats = [
            'total_edicoes' => Edicao::count(),
            'total_materias' => Materia::where('status', 'aprovado')->count(),
            'total_visualizacoes' => Visualizacao::count(),
            'total_downloads' => Download::count(),
            'visualizacoes_edicao_recente' => $edicaoRecente ? $edicaoRecente->visualizacoes_count : 0,
            'downloads_edicao_recente' => $edicaoRecente ? $edicaoRecente->downloads_count : 0,
        ];
        
        // Estatísticas antigas para compatibilidade
        $totalEdicoes = $stats['total_edicoes'];
        $materiasEdicaoAtual = $edicaoRecente ? $edicaoRecente->materias_count : 0;
        
        // Downloads mais populares (últimos 30 dias)
        $downloadsPopulares = Download::with(['edicao'])
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select('edicao_id')
            ->selectRaw('count(*) as total_downloads')
            ->groupBy('edicao_id')
            ->orderBy('total_downloads', 'desc')
            ->limit(5)
            ->get();
        
        // Seções mais acessadas (tipos de matéria mais visualizados)
        $secoesPopulares = Visualizacao::with(['materia.tipo'])
            ->whereHas('materia')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->get()
            ->groupBy('materia.tipo.nome')
            ->map(function ($visualizacoes) {
                return $visualizacoes->count();
            })
            ->sortDesc()
            ->take(5);
        
        // Calendário - edições do mês selecionado
        $edicoesCalendario = Edicao::whereMonth('data', $mesCalendario)
            ->whereYear('data', $anoCalendario)
            ->orderBy('data', 'desc')
            ->get()
            ->groupBy(function ($edicao) {
                return $edicao->data->format('Y-m-d');
            });

        // Para compatibilidade com o layout antigo
        $ultimaEdicao = $edicaoRecente;

        return view('home', compact(
            'edicaoRecente',
            'edicoesRecentes', 
            'ultimasPublicacoes',
            'stats',
            'totalEdicoes',
            'materiasEdicaoAtual',
            'downloadsPopulares',
            'secoesPopulares',
            'edicoesCalendario',
            'ultimaEdicao',
            'dataCalendario'
        ));
    }
    
    public function buscar(Request $request, SearchService $searchService)
    {
        $params = $request->all();
        
        if (empty($params['q']) && empty(array_filter($params, function($key) {
            return in_array($key, ['tipo_id', 'orgao_id', 'data_inicio', 'data_fim', 'ano', 'mes']);
        }, ARRAY_FILTER_USE_KEY))) {
            return redirect()->route('home');
        }
        
        // Buscar matérias com o novo serviço
        $materiasQuery = $searchService->search($params);
        $materias = $materiasQuery->paginate(20)->appends($request->query());
        
        // Buscar edições se aplicável
        $edicoes = collect();
        if (!empty($params['q']) || !empty($params['numero'])) {
            $edicoesQuery = $searchService->searchEdicoes($params);
            $edicoes = $edicoesQuery->limit(10)->get();
        }
        
        // Estatísticas de busca
        $searchStats = $searchService->getSearchStats();
        
        // Sugestões se não houve resultados
        $suggestions = collect();
        if ($materias->isEmpty() && !empty($params['q'])) {
            $suggestions = $searchService->getSuggestions($params['q']);
        }
        
        // Dados para filtros
        $tipos = \App\Models\Tipo::orderBy('nome')->get();
        $orgaos = \App\Models\Orgao::orderBy('nome')->get();
        $anos = \App\Models\Materia::selectRaw('YEAR(data) as ano')
            ->distinct()
            ->orderBy('ano', 'desc')
            ->pluck('ano');
        
        return view('portal.busca.resultados', compact(
            'materias', 
            'edicoes', 
            'params',
            'searchStats',
            'suggestions',
            'tipos',
            'orgaos',
            'anos'
        ));
    }

    /**
     * API para busca rápida (autocomplete)
     */
    public function quickSearch(Request $request, SearchService $searchService)
    {
        $term = $request->get('q');
        
        if (strlen($term) < 3) {
            return response()->json([]);
        }
        
        $results = $searchService->quickSearch($term, 10);
        
        return response()->json($results);
    }
}
