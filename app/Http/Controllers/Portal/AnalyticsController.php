<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Visualizacao;
use App\Models\Edicao;
use App\Models\Download;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Período de análise (padrão: últimos 30 dias)
        $periodo = $request->get('periodo', 30);
        $dataInicio = now()->subDays($periodo);
        
        // Estatísticas gerais
        $stats = [
            'total_visualizacoes' => Visualizacao::where('created_at', '>=', $dataInicio)->count(),
            'total_downloads' => Download::where('created_at', '>=', $dataInicio)->count(),
            'visualizacoes_hoje' => Visualizacao::whereDate('created_at', today())->count(),
            'downloads_hoje' => Download::whereDate('created_at', today())->count(),
        ];
        
        // Top 10 edições mais visualizadas
        $edicoesPopulares = Edicao::withCount(['visualizacoes' => function($query) use ($dataInicio) {
            $query->where('created_at', '>=', $dataInicio);
        }])
        ->orderBy('visualizacoes_count', 'desc')
        ->limit(10)
        ->get();
        
        // Gráfico de visualizações por dia
        $visualizacoesPorDia = Visualizacao::selectRaw('DATE(created_at) as data, COUNT(*) as total')
            ->where('created_at', '>=', $dataInicio)
            ->groupBy('data')
            ->orderBy('data')
            ->get();
        
        // Top IPs (para detectar possíveis bots)
        $topIps = Visualizacao::selectRaw('ip, COUNT(*) as total')
            ->where('created_at', '>=', $dataInicio)
            ->groupBy('ip')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        // Visualizações por hora do dia
        $visualizacoesPorHora = Visualizacao::selectRaw('HOUR(created_at) as hora, COUNT(*) as total')
            ->where('created_at', '>=', $dataInicio)
            ->groupBy('hora')
            ->orderBy('hora')
            ->get();
        
        return view('portal.analytics.index', compact(
            'stats',
            'edicoesPopulares',
            'visualizacoesPorDia',
            'topIps',
            'visualizacoesPorHora',
            'periodo'
        ));
    }
    
    public function edicao($id, Request $request)
    {
        $edicao = Edicao::findOrFail($id);
        $periodo = $request->get('periodo', 30);
        $dataInicio = now()->subDays($periodo);
        
        // Estatísticas específicas da edição
        $stats = [
            'total_visualizacoes' => $edicao->visualizacoes()->where('created_at', '>=', $dataInicio)->count(),
            'total_downloads' => $edicao->downloads()->where('created_at', '>=', $dataInicio)->count(),
            'primeira_visualizacao' => $edicao->visualizacoes()->orderBy('created_at')->first()?->created_at,
            'ultima_visualizacao' => $edicao->visualizacoes()->orderBy('created_at', 'desc')->first()?->created_at,
        ];
        
        // Visualizações por dia para esta edição
        $visualizacoesPorDia = $edicao->visualizacoes()
            ->selectRaw('DATE(created_at) as data, COUNT(*) as total')
            ->where('created_at', '>=', $dataInicio)
            ->groupBy('data')
            ->orderBy('data')
            ->get();
        
        // Top matérias mais acessadas desta edição
        $materiasPopulares = $edicao->materias()
            ->withCount(['visualizacoes' => function($query) use ($dataInicio) {
                $query->where('created_at', '>=', $dataInicio);
            }])
            ->orderBy('visualizacoes_count', 'desc')
            ->limit(10)
            ->get();
        
        return view('portal.analytics.edicao', compact(
            'edicao',
            'stats',
            'visualizacoesPorDia',
            'materiasPopulares',
            'periodo'
        ));
    }
}
