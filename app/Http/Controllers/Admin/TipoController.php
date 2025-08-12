<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TipoController extends Controller
{
    /**
     * Exibe uma lista de todos os tipos de matéria.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tipos = Tipo::orderBy('nome')->paginate(15);
        return view('admin.tipos.index', compact('tipos'));
    }

    /**
     * Mostra o formulário para criar um novo tipo de matéria.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.tipos.create');
    }

    /**
     * Armazena um novo tipo de matéria no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:tipos,nome',
            'template' => 'nullable|string',
            'descricao' => 'nullable|string',
            'ativo' => 'nullable|boolean',
        ]);

        $tipo = Tipo::create([
            'nome' => $request->nome,
            'slug' => Str::slug($request->nome),
            'template' => $request->template,
            'descricao' => $request->descricao,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('tipos.show', $tipo)
                        ->with('success', 'Tipo de matéria criado com sucesso.');
    }

    /**
     * Exibe os detalhes de um tipo de matéria específico.
     *
     * @param  \App\Models\Tipo  $tipo
     * @return \Illuminate\View\View
     */
    public function show(Tipo $tipo)
    {
        $materias = $tipo->materias()->paginate(10);
        return view('admin.tipos.show', compact('tipo', 'materias'));
    }

    /**
     * Mostra o formulário para editar um tipo de matéria.
     *
     * @param  \App\Models\Tipo  $tipo
     * @return \Illuminate\View\View
     */
    public function edit(Tipo $tipo)
    {
        return view('admin.tipos.edit', compact('tipo'));
    }

    /**
     * Atualiza um tipo de matéria no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tipo  $tipo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Tipo $tipo)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:tipos,nome,' . $tipo->id,
            'template' => 'nullable|string',
            'descricao' => 'nullable|string',
            'ativo' => 'nullable|boolean',
        ]);

        $tipo->update([
            'nome' => $request->nome,
            'slug' => Str::slug($request->nome),
            'template' => $request->template,
            'descricao' => $request->descricao,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('tipos.show', $tipo)
                         ->with('success', 'Tipo de matéria atualizado com sucesso.');
    }

    /**
     * Remove um tipo de matéria do banco de dados.
     *
     * @param  \App\Models\Tipo  $tipo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Tipo $tipo)
    {
        // Verificar se existem matérias associadas antes de excluir
        if ($tipo->materias()->count() > 0) {
            return back()->with('error', 'Não é possível excluir este tipo pois existem matérias associadas a ele.');
        }

        $tipo->delete();

        return redirect()->route('tipos.index')
                         ->with('success', 'Tipo de matéria excluído com sucesso.');
    }
}
