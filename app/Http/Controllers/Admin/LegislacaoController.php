<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Legislacao;
use App\Models\Orgao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LegislacaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Legislacao::with('orgao');
        
        // Filtros de busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('ementa', 'like', "%{$search}%")
                  ->orWhere('numero', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_publicacao', '>=', $request->data_inicio);
        }
        
        if ($request->filled('data_fim')) {
            $query->whereDate('data_publicacao', '<=', $request->data_fim);
        }

        // Ordenação
        $legislacao = $query->orderBy('data_publicacao', 'desc')->paginate(15);
        
        // Dados para os filtros
        $tipos = Legislacao::distinct()->pluck('tipo')->sort();
        $orgaos = Orgao::orderBy('nome')->get();
        
        return view('admin.legislacao.index', compact('legislacao', 'tipos', 'orgaos'));
    }

    public function create()
    {
        $orgaos = Orgao::orderBy('nome')->get();
        return view('admin.legislacao.create', compact('orgaos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'numero' => 'required|string|max:50',
            'data_publicacao' => 'required|date',
            'ementa' => 'nullable|string',
            'texto_completo' => 'nullable|string',
            'arquivo_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'status' => 'required|in:vigente,revogada,suspensa',
            'orgao_id' => 'required|exists:orgaos,id'
        ]);

        $dados = $request->except('arquivo_pdf');

        if ($request->hasFile('arquivo_pdf')) {
            $arquivo = $request->file('arquivo_pdf');
            $dados['arquivo_pdf'] = $arquivo->store('legislacao', 'public');
        }

        Legislacao::create($dados);

        return redirect()->route('admin.legislacao.index')
                         ->with('success', 'Legislação criada com sucesso');
    }

    public function show(Legislacao $legislacao)
    {
        $legislacao->load('orgao');
        return view('admin.legislacao.show', compact('legislacao'));
    }

    public function edit(Legislacao $legislacao)
    {
        $orgaos = Orgao::orderBy('nome')->get();
        return view('admin.legislacao.edit', compact('legislacao', 'orgaos'));
    }

    public function update(Request $request, Legislacao $legislacao)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'numero' => 'required|string|max:50',
            'data_publicacao' => 'required|date',
            'ementa' => 'nullable|string',
            'texto_completo' => 'nullable|string',
            'arquivo_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'status' => 'required|in:vigente,revogada,suspensa',
            'orgao_id' => 'required|exists:orgaos,id'
        ]);

        $dados = $request->except('arquivo_pdf');

        if ($request->hasFile('arquivo_pdf')) {
            // Remove arquivo antigo se existir
            if ($legislacao->arquivo_pdf && Storage::disk('public')->exists($legislacao->arquivo_pdf)) {
                Storage::disk('public')->delete($legislacao->arquivo_pdf);
            }
            
            $arquivo = $request->file('arquivo_pdf');
            $dados['arquivo_pdf'] = $arquivo->store('legislacao', 'public');
        }

        $legislacao->update($dados);

        return redirect()->route('admin.legislacao.show', $legislacao)
                         ->with('success', 'Legislação atualizada com sucesso');
    }

    public function destroy(Legislacao $legislacao)
    {
        // Remove arquivo se existir
        if ($legislacao->arquivo_pdf && Storage::disk('public')->exists($legislacao->arquivo_pdf)) {
            Storage::disk('public')->delete($legislacao->arquivo_pdf);
        }

        $legislacao->delete();

        return redirect()->route('admin.legislacao.index')
                         ->with('success', 'Legislação excluída com sucesso');
    }
}
