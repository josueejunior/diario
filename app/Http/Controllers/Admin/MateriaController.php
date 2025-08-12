<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use App\Models\Tipo;
use App\Models\Orgao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MateriaController extends Controller
{
    /**
     * Exibe uma lista de todas as matérias.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Materia::query();
        
        // Filtros
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->search . '%')
                  ->orWhere('texto', 'like', '%' . $request->search . '%')
                  ->orWhere('numero', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('tipo_id') && $request->tipo_id) {
            $query->where('tipo_id', $request->tipo_id);
        }
        
        if ($request->has('orgao_id') && $request->orgao_id) {
            $query->where('orgao_id', $request->orgao_id);
        }

        // Ordenação
        $query->orderBy('created_at', 'desc');
        
        $materias = $query->paginate(15);
        $tipos = Tipo::all();
        $orgaos = Orgao::all();
        
        return view('admin.materias.index', compact('materias', 'tipos', 'orgaos'));
    }

    /**
     * Mostra o formulário para criar uma nova matéria.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $tipos = Tipo::where('ativo', true)->get();
        $orgaos = Orgao::all();
        
        return view('admin.materias.create', compact('tipos', 'orgaos'));
    }

    /**
     * Armazena uma nova matéria no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo_id' => 'required|exists:tipos,id',
            'orgao_id' => 'required|exists:orgaos,id',
            'numero' => 'required|string|max:255',
            'data' => 'required|date',
            'titulo' => 'required|string|max:255',
            'texto' => 'required|string',
            'arquivo' => 'nullable|file|mimes:pdf,doc,docx,odt|max:10240',
        ]);

        $dados = [
            'tipo_id' => $request->tipo_id,
            'orgao_id' => $request->orgao_id,
            'numero' => $request->numero,
            'data' => $request->data,
            'titulo' => $request->titulo,
            'texto' => $request->texto,
            'status' => 'pendente',
            'created_by' => Auth::id()
        ];

        if ($request->hasFile('arquivo')) {
            $arquivo = $request->file('arquivo');
            $dados['arquivo'] = $arquivo->store('materias', 'public');
        }

        $materia = Materia::create($dados);

        return redirect()->route('materias.show', $materia)
                         ->with('success', 'Matéria criada com sucesso.');
    }

    /**
     * Exibe os detalhes de uma matéria específica.
     *
     * @param  \App\Models\Materia  $materia
     * @return \Illuminate\View\View
     */
    public function show(Materia $materia)
    {
        return view('admin.materias.show', compact('materia'));
    }

    /**
     * Mostra o formulário para editar uma matéria.
     *
     * @param  \App\Models\Materia  $materia
     * @return \Illuminate\View\View
     */
    public function edit(Materia $materia)
    {
        $tipos = Tipo::where('ativo', true)->get();
        $orgaos = Orgao::all();
        
        return view('admin.materias.edit', compact('materia', 'tipos', 'orgaos'));
    }

    /**
     * Atualiza uma matéria no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Materia  $materia
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Materia $materia)
    {
        $request->validate([
            'tipo_id' => 'required|exists:tipos,id',
            'orgao_id' => 'required|exists:orgaos,id',
            'numero' => 'required|string|max:255',
            'data' => 'required|date',
            'titulo' => 'required|string|max:255',
            'texto' => 'required|string',
            'arquivo' => 'nullable|file|mimes:pdf,doc,docx,odt|max:10240',
        ]);

        $dados = [
            'tipo_id' => $request->tipo_id,
            'orgao_id' => $request->orgao_id,
            'numero' => $request->numero,
            'data' => $request->data,
            'titulo' => $request->titulo,
            'texto' => $request->texto,
        ];

        if ($request->hasFile('arquivo')) {
            // Apaga o arquivo antigo se existir
            if ($materia->arquivo && Storage::disk('public')->exists($materia->arquivo)) {
                Storage::disk('public')->delete($materia->arquivo);
            }
            
            $arquivo = $request->file('arquivo');
            $dados['arquivo'] = $arquivo->store('materias', 'public');
        }

        $materia->update($dados);

        return redirect()->route('materias.show', $materia)
                         ->with('success', 'Matéria atualizada com sucesso.');
    }

    /**
     * Remove uma matéria do banco de dados.
     *
     * @param  \App\Models\Materia  $materia
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Materia $materia)
    {
        // Apaga o arquivo se existir
        if ($materia->arquivo && Storage::disk('public')->exists($materia->arquivo)) {
            Storage::disk('public')->delete($materia->arquivo);
        }

        $materia->delete();

        return redirect()->route('materias.index')
                         ->with('success', 'Matéria excluída com sucesso.');
    }

    /**
     * Aprova uma matéria.
     *
     * @param  \App\Models\Materia  $materia
     * @return \Illuminate\Http\RedirectResponse
     */
    public function aprovar(Materia $materia)
    {
        $materia->update([
            'status' => 'aprovado',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        return back()->with('success', 'Matéria aprovada com sucesso!');
    }

    /**
     * Envia uma matéria para revisão.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Materia  $materia
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revisar(Request $request, Materia $materia)
    {
        $request->validate([
            'notas_revisao' => 'required|string'
        ]);

        $materia->update([
            'status' => 'revisao',
            'notas_revisao' => $request->notas_revisao
        ]);

        return back()->with('success', 'Matéria enviada para revisão com sucesso!');
    }
}
