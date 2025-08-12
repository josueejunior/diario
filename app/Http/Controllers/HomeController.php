<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Edicao;
use App\Models\Materia;
use App\Models\Tipo;
use App\Models\Orgao;
use App\Models\Download;
use App\Models\Visualizacao;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Última edição publicada
        $ultimaEdicao = Edicao::orderBy('data', 'desc')->first();
        
        // Últimas publicações (matérias mais recentes)
        $ultimasPublicacoes = Materia::with(['tipo', 'orgao'])
            ->where('status', 'aprovado')
            ->orderBy('data', 'desc')
            ->limit(8)
            ->get();
        
        // Estatísticas
        $totalEdicoes = Edicao::count();
        $materiasEdicaoAtual = $ultimaEdicao ? $ultimaEdicao->materias()->count() : 0;
        
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
        
        // Calendário - edições dos últimos 30 dias
        $edicoesCalendario = Edicao::where('data', '>=', Carbon::now()->subDays(30))
            ->orderBy('data', 'desc')
            ->get()
            ->groupBy(function ($edicao) {
                return $edicao->data->format('Y-m-d');
            });

        return view('home', compact(
            'ultimaEdicao',
            'ultimasPublicacoes',
            'totalEdicoes',
            'materiasEdicaoAtual',
            'downloadsPopulares',
            'secoesPopulares',
            'edicoesCalendario'
        ));
    }
    
    public function buscar(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->route('home');
        }
        
        // Buscar em matérias
        $materias = Materia::with(['tipo', 'orgao'])
            ->where('status', 'aprovado')
            ->where(function ($q) use ($query) {
                $q->where('titulo', 'LIKE', "%{$query}%")
                  ->orWhere('texto', 'LIKE', "%{$query}%")
                  ->orWhere('numero', 'LIKE', "%{$query}%");
            })
            ->orderBy('data', 'desc')
            ->paginate(20);
        
        // Buscar em edições
        $edicoes = Edicao::where('numero', 'LIKE', "%{$query}%")
            ->orWhereDate('data', $query)
            ->orderBy('data', 'desc')
            ->get();
        
        return view('portal.busca.resultados', compact('materias', 'edicoes', 'query'));
    }
}
