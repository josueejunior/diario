<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orgao;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrgaoController extends Controller
{
    /**
     * Exibe uma lista de todos os órgãos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orgaos = Orgao::orderBy('nome')->paginate(15);
        return view('admin.orgaos.index', compact('orgaos'));
    }

    /**
     * Mostra o formulário para criar um novo órgão.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.orgaos.create');
    }

    /**
     * Armazena um novo órgão no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:orgaos,nome',
            'sigla' => 'required|string|max:50|unique:orgaos,sigla',
            'descricao' => 'nullable|string',
            'ativo' => 'nullable|boolean',
        ]);

        $orgao = Orgao::create([
            'nome' => $request->nome,
            'slug' => Str::slug($request->nome),
            'sigla' => $request->sigla,
            'descricao' => $request->descricao,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('orgaos.show', $orgao)
                        ->with('success', 'Órgão criado com sucesso.');
    }

    /**
     * Exibe os detalhes de um órgão específico.
     *
     * @param  \App\Models\Orgao  $orgao
     * @return \Illuminate\View\View
     */
    public function show(Orgao $orgao)
    {
        $materias = $orgao->materias()->paginate(10);
        return view('admin.orgaos.show', compact('orgao', 'materias'));
    }

    /**
     * Mostra o formulário para editar um órgão.
     *
     * @param  \App\Models\Orgao  $orgao
     * @return \Illuminate\View\View
     */
    public function edit(Orgao $orgao)
    {
        return view('admin.orgaos.edit', compact('orgao'));
    }

    /**
     * Atualiza um órgão no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orgao  $orgao
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Orgao $orgao)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:orgaos,nome,' . $orgao->id,
            'sigla' => 'required|string|max:50|unique:orgaos,sigla,' . $orgao->id,
            'descricao' => 'nullable|string',
            'ativo' => 'nullable|boolean',
        ]);

        $orgao->update([
            'nome' => $request->nome,
            'slug' => Str::slug($request->nome),
            'sigla' => $request->sigla,
            'descricao' => $request->descricao,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('orgaos.show', $orgao)
                         ->with('success', 'Órgão atualizado com sucesso.');
    }

    /**
     * Remove um órgão do banco de dados.
     *
     * @param  \App\Models\Orgao  $orgao
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Orgao $orgao)
    {
        // Verificar se existem matérias associadas antes de excluir
        if ($orgao->materias()->count() > 0) {
            return back()->with('error', 'Não é possível excluir este órgão pois existem matérias associadas a ele.');
        }

        $orgao->delete();

        return redirect()->route('orgaos.index')
                         ->with('success', 'Órgão excluído com sucesso.');
    }
}
