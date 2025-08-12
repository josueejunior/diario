<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Edicao;
use Illuminate\Http\Request;

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
        
        // Obtém a edição mais recente para exibição do PDF
        $edicaoRecente = Edicao::where('publicado', true)
                               ->orderBy('data_publicacao', 'desc')
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pdf(Edicao $edicao)
    {
        // Verifica se existe o arquivo PDF no storage
        $pdfPath = "edicoes/{$edicao->id}.pdf";
        
        if (!$edicao->caminho_arquivo || !storage_path("app/public/{$edicao->caminho_arquivo}")) {
            // Se não existir, gera um PDF simples com os dados da edição
            $pdf = app('App\Services\PdfService')->gerarPdfEdicao($edicao);
            return $pdf->stream("edicao-{$edicao->numero}.pdf");
        }
        
        // Se o arquivo existir, retorna o arquivo
        return response()->file(storage_path("app/public/{$edicao->caminho_arquivo}"), [
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
}
