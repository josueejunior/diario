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
        
        return view('portal.edicoes.index', compact('edicoes'));
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
        // Aqui você pode integrar com o serviço de PDF
        // Por enquanto, retornamos uma resposta temporária
        return response()->download(storage_path("app/edicoes/{$edicao->id}.pdf"), "edicao-{$edicao->numero}.pdf");
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
