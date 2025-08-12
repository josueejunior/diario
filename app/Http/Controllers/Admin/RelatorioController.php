<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Edicao;
use App\Models\Materia;
use App\Models\Download;
use App\Models\Visualizacao;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    /**
     * Exibe a página principal de relatórios.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Estatísticas Gerais
        $totalEdicoes = Edicao::count();
        $totalMaterias = Materia::count();
        $totalDownloads = Download::count();
        $totalVisualizacoes = Visualizacao::count();
        
        // Dados para os gráficos dos últimos 30 dias
        $startDate = Carbon::now()->subDays(30);
        
        $edicoesPublicadas = Edicao::where('publicado', true)
            ->where('data_publicacao', '>=', $startDate)
            ->count();
            
        // Visualizações por dia nos últimos 30 dias
        $visualizacoesPorDia = Visualizacao::select(
                DB::raw('DATE(created_at) as data'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('data')
            ->orderBy('data')
            ->get();
            
        // Downloads por dia nos últimos 30 dias
        $downloadsPorDia = Download::select(
                DB::raw('DATE(created_at) as data'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('data')
            ->orderBy('data')
            ->get();
        
        return view('admin.relatorios.index', compact(
            'totalEdicoes', 
            'totalMaterias', 
            'totalDownloads', 
            'totalVisualizacoes',
            'edicoesPublicadas',
            'visualizacoesPorDia',
            'downloadsPorDia'
        ));
    }

    /**
     * Exibe relatórios de downloads de edições.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function downloads(Request $request)
    {
        $query = Download::with('edicao');
        
        // Filtros
        if ($request->has('data_inicio') && $request->data_inicio) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }
        
        if ($request->has('data_fim') && $request->data_fim) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }
        
        if ($request->has('edicao_id') && $request->edicao_id) {
            $query->where('edicao_id', $request->edicao_id);
        }
        
        // Ordenação
        $downloads = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Dados para gráficos
        $downloadsPorDia = Download::select(
                DB::raw('DATE(created_at) as data'),
                DB::raw('count(*) as total')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('data')
            ->limit(30)
            ->get();
            
        $downloadsPorEdicao = Download::select(
                'edicao_id',
                DB::raw('count(*) as total')
            )
            ->groupBy('edicao_id')
            ->orderByDesc('total')
            ->limit(10)
            ->with('edicao')
            ->get();
        
        $edicoes = Edicao::orderBy('data', 'desc')->get();
        
        return view('admin.relatorios.downloads', compact(
            'downloads', 
            'downloadsPorDia', 
            'downloadsPorEdicao',
            'edicoes'
        ));
    }

    /**
     * Exibe relatórios de visualizações de matérias.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function visualizacoes(Request $request)
    {
        $query = Visualizacao::with(['edicao', 'materia']);
        
        // Filtros
        if ($request->has('data_inicio') && $request->data_inicio) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }
        
        if ($request->has('data_fim') && $request->data_fim) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }
        
        if ($request->has('edicao_id') && $request->edicao_id) {
            $query->where('edicao_id', $request->edicao_id);
        }
        
        if ($request->has('materia_id') && $request->materia_id) {
            $query->where('materia_id', $request->materia_id);
        }
        
        // Ordenação
        $visualizacoes = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Dados para gráficos
        $visualizacoesPorDia = Visualizacao::select(
                DB::raw('DATE(created_at) as data'),
                DB::raw('count(*) as total')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('data')
            ->limit(30)
            ->get();
            
        $visualizacoesPorMateria = Visualizacao::select(
                'materia_id',
                DB::raw('count(*) as total')
            )
            ->whereNotNull('materia_id')
            ->groupBy('materia_id')
            ->orderByDesc('total')
            ->limit(10)
            ->with('materia')
            ->get();
        
        $edicoes = Edicao::orderBy('data', 'desc')->get();
        $materias = Materia::orderBy('created_at', 'desc')->limit(100)->get();
        
        return view('admin.relatorios.visualizacoes', compact(
            'visualizacoes', 
            'visualizacoesPorDia', 
            'visualizacoesPorMateria',
            'edicoes',
            'materias'
        ));
    }

    /**
     * Exibe relatórios de publicações.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function publicacoes(Request $request)
    {
        $query = Edicao::where('publicado', true);
        
        // Filtros
        if ($request->has('data_inicio') && $request->data_inicio) {
            $query->whereDate('data_publicacao', '>=', $request->data_inicio);
        }
        
        if ($request->has('data_fim') && $request->data_fim) {
            $query->whereDate('data_publicacao', '<=', $request->data_fim);
        }
        
        if ($request->has('tipo') && $request->tipo) {
            $query->where('tipo', $request->tipo);
        }
        
        // Ordenação
        $publicacoes = $query->orderBy('data_publicacao', 'desc')->paginate(20);
        
        // Dados para gráficos
        $publicacoesPorDia = Edicao::select(
                DB::raw('DATE(data_publicacao) as data'),
                DB::raw('count(*) as total')
            )
            ->where('publicado', true)
            ->groupBy(DB::raw('DATE(data_publicacao)'))
            ->orderBy('data')
            ->limit(30)
            ->get();
            
        $publicacoesPorTipo = Edicao::select(
                'tipo',
                DB::raw('count(*) as total')
            )
            ->where('publicado', true)
            ->groupBy('tipo')
            ->orderByDesc('total')
            ->get();
        
        return view('admin.relatorios.publicacoes', compact(
            'publicacoes', 
            'publicacoesPorDia', 
            'publicacoesPorTipo'
        ));
    }
}
