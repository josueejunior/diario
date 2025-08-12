<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Edicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
 

class EdicaoController extends Controller
{
    /**
     * Exibe a página inicial com as edições mais recentes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Busca as edições mais recentes
        $edicoes = Edicao::orderBy('data_publicacao', 'desc')
                         ->where('publicado', true)
                         ->paginate(10);
        
        // Obtém a edição mais recente para exibição do PDF com contadores
        $edicaoRecente = Edicao::where('publicado', true)
                               ->orderBy('data_publicacao', 'desc')
                               ->withCount('visualizacoes', 'downloads')
                               ->first();
        
        // Obtém as datas das edições do mês atual para o calendário
        $dataAtual = now();
        $primeiroDiaMes = $dataAtual->copy()->startOfMonth();
        $ultimoDiaMes = $dataAtual->copy()->endOfMonth();
        
        $datasEdicoes = Edicao::where('publicado', true)
                            ->whereBetween('data_publicacao', [$primeiroDiaMes, $ultimoDiaMes])
                            ->pluck('data_publicacao')
                            ->map(function($data) {
                                return $data->format('Y-m-d');
                            })
                            ->toArray();
        
        // Adiciona edições dos dois meses anteriores para o calendário
        $mesesAnteriores = [];
        for ($i = 1; $i <= 2; $i++) {
            $mesAnterior = $dataAtual->copy()->subMonths($i);
            $primeiroDiaMesAnterior = $mesAnterior->copy()->startOfMonth();
            $ultimoDiaMesAnterior = $mesAnterior->copy()->endOfMonth();
            
            $edicoesAnteriores = Edicao::where('publicado', true)
                ->whereBetween('data_publicacao', [$primeiroDiaMesAnterior, $ultimoDiaMesAnterior])
                ->pluck('data_publicacao', 'id')
                ->map(function($data) {
                    return $data->format('Y-m-d');
                })
                ->toArray();
            
            $mesesAnteriores[$mesAnterior->format('Y-m')] = $edicoesAnteriores;
        }
        
        return view('portal.edicoes.index', compact('edicoes', 'edicaoRecente', 'datasEdicoes', 'mesesAnteriores', 'dataAtual'));
    }
    
    /**
     * Exibe os detalhes de uma edição específica.
     *
     * @param  \App\Models\Edicao  $edicao
     * @return \Illuminate\View\View
     */
    public function show(Edicao $edicao)
    {
        return view('portal.edicoes.show', compact('edicao'));
    }
    
    /**
     * Lista todas as matérias de uma edição específica.
     *
     * @param  \App\Models\Edicao  $edicao
     * @return \Illuminate\View\View
     */
    public function materias(Edicao $edicao)
    {
        $materias = $edicao->materias()->paginate(15);
        
        return view('portal.edicoes.materias', compact('edicao', 'materias'));
    }
    
    /**
     * Gera o PDF de uma edição.
     *
     * @param  \App\Models\Edicao  $edicao
     * @return \Illuminate\Http\Response
     */
    public function pdf(Edicao $edicao)
    {
        // Registrar a visualização para estatísticas
        $edicao->visualizacoes()->create([
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        // Incrementa o contador de visualizações
        $edicao->increment('visualizacoes');
        
        // Verificar se tem caminho de arquivo definido
        if ($edicao->caminho_arquivo && Storage::disk('public')->exists($edicao->caminho_arquivo)) {
            // Se o arquivo existir, retorna o arquivo
            return response()->file(
                storage_path("app/public/{$edicao->caminho_arquivo}"), 
                ['Content-Type' => 'application/pdf']
            );
        }
        
        // Se não existir, gera um PDF simples com os dados da edição
        $pdfService = app()->make('App\Services\PdfService');
        $pdf = $pdfService->gerarPdfEdicao($edicao);
        
        // Dompdf retorna o stream, que podemos retornar como resposta HTTP
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="edicao-'.$edicao->numero.'.pdf"'
        ]);
    }
    
    /**
     * Exibe a página de verificação de autenticidade.
     *
     * @return \Illuminate\View\View
     */
    public function verificar()
    {
        return view('portal.verificar');
    }
    
    /**
     * Verifica a autenticidade de um documento pelo hash.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verificarHash(Request $request)
    {
        $hash = $request->input('hash');
        
        // Aqui você verifica o hash no banco de dados
        $edicao = Edicao::where('hash', $hash)->first();
        
        if ($edicao) {
            return redirect()->route('portal.edicoes.show', $edicao)
                             ->with('success', 'Documento autêntico verificado com sucesso!');
        }
        
        return back()->with('error', 'Hash não encontrado. O documento pode não ser autêntico.');
    }
    
    /**
     * Registra um download e serve o arquivo.
     *
     * @param  \App\Models\Edicao  $edicao
     * @return \Illuminate\Http\Response
     */
    public function download(Edicao $edicao)
    {
        // Registrar o download para estatísticas
        $edicao->downloads()->create([
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        // Incrementa o contador de downloads
        $edicao->increment('downloads');
        
        // Verificar se tem caminho de arquivo definido
        if ($edicao->caminho_arquivo && Storage::disk('public')->exists($edicao->caminho_arquivo)) {
            return response()->download(
                storage_path("app/public/{$edicao->caminho_arquivo}"),
                "edicao-{$edicao->numero}.pdf",
                ['Content-Type' => 'application/pdf']
            );
        }
        
        // Se não existir o arquivo, gera um PDF e o retorna
        $pdfService = app()->make('App\Services\PdfService');
        $pdf = $pdfService->gerarPdfEdicao($edicao);
        
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="edicao-'.$edicao->numero.'.pdf"'
        ]);
    }
}
