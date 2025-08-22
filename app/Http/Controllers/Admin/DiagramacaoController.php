<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Edicao;
use App\Models\Materia;
use App\Models\Tipo;
use App\Models\Orgao;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiagramacaoController extends Controller
{
    /**
     * Exibe a página de diagramação
     */
    public function index(Request $request)
    {
        $query = Edicao::with(['materias.tipo', 'materias.orgao']);

        // Filtros
        if ($request->filled('data')) {
            $query->whereDate('data', $request->data);
        }

        if ($request->filled('status')) {
            if ($request->status == 'rascunho') {
                $query->where('publicado', false)->whereNull('data_publicacao');
            } elseif ($request->status == 'publicado') {
                $query->where('publicado', true)->whereNotNull('data_publicacao');
            } elseif ($request->status == 'pronto') {
                $query->where('publicado', false)->whereNotNull('descricao');
            }
        }

        // Ordenação
        $edicoes = $query->orderBy('data', 'desc')->paginate(15);

        // Estatísticas
        $stats = [
            'total_edicoes' => Edicao::count(),
            'edicoes_rascunho' => Edicao::where('publicado', false)->whereNull('data_publicacao')->count(),
            'edicoes_prontas' => Edicao::where('publicado', false)->whereNotNull('descricao')->count(),
            'edicoes_publicadas' => Edicao::where('publicado', true)->count(),
            'materias_pendentes' => Materia::whereDoesntHave('edicoes')->count(),
        ];

        // Matérias sem edição para seleção
        $materiasSemEdicao = Materia::with(['tipo', 'orgao'])
            ->whereDoesntHave('edicoes')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.diagramacao.index', compact('edicoes', 'stats', 'materiasSemEdicao'));
    }

    /**
     * Gerar nova edição
     */
    public function gerar(Request $request)
    {
        $request->validate([
            'data' => 'required|date',
            'numero' => 'required|integer',
            'materias' => 'required|array|min:1',
            'materias.*' => 'exists:materias,id'
        ]);

        try {
            // Verificar se já existe edição para esta data
            $existeEdicao = Edicao::whereDate('data', $request->data)->exists();
            if ($existeEdicao) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Já existe uma edição para esta data'
                ], 422);
            }

            // Criar nova edição
            $edicao = Edicao::create([
                'numero' => $request->numero,
                'data' => $request->data,
                'publicado' => false,
                'descricao' => "Edição #{$request->numero} - " . Carbon::parse($request->data)->format('d/m/Y'),
                'observacoes' => 'Edição gerada automaticamente via diagramação'
            ]);

            // Associar matérias à edição
            $edicao->materias()->sync($request->materias);

            // Log da ação
            if (class_exists('App\Http\Controllers\Admin\LogsController')) {
                \App\Http\Controllers\Admin\LogsController::createLog(
                    'create', 
                    "Nova edição #{$edicao->numero} gerada via diagramação", 
                    'diagramacao',
                    [
                        'edicao_id' => $edicao->id,
                        'numero' => $edicao->numero,
                        'data' => $edicao->data->toDateString(),
                        'materias_count' => count($request->materias)
                    ]
                );
            }

            return response()->json([
                'success' => true, 
                'message' => "Edição #{$edicao->numero} gerada com sucesso",
                'edicao_id' => $edicao->id,
                'redirect' => route('admin.edicoes.show', $edicao)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao gerar edição: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Salvar alterações de diagramação
     */
    public function salvar(Request $request)
    {
        $request->validate([
            'edicao_id' => 'required|exists:edicoes,id',
            'materias' => 'array',
            'materias.*' => 'exists:materias,id',
            'observacoes' => 'nullable|string'
        ]);

        try {
            $edicao = Edicao::findOrFail($request->edicao_id);
            
            // Verificar se edição não está publicada
            if ($edicao->publicado) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Não é possível alterar uma edição já publicada'
                ], 422);
            }

            // Atualizar matérias da edição
            if ($request->has('materias')) {
                $edicao->materias()->sync($request->materias);
            }

            // Atualizar observações se fornecidas
            if ($request->filled('observacoes')) {
                $edicao->update(['observacoes' => $request->observacoes]);
            }

            // Log da ação
            if (class_exists('App\Http\Controllers\Admin\LogsController')) {
                \App\Http\Controllers\Admin\LogsController::createLog(
                    'update', 
                    "Diagramação da edição #{$edicao->numero} atualizada", 
                    'diagramacao',
                    [
                        'edicao_id' => $edicao->id,
                        'materias_count' => $edicao->materias()->count()
                    ]
                );
            }

            return response()->json([
                'success' => true, 
                'message' => 'Diagramação salva com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao salvar diagramação: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Visualizar diagramação de uma edição
     */
    public function show($id)
    {
        $edicao = Edicao::with(['materias.tipo', 'materias.orgao', 'assinatura'])
            ->findOrFail($id);

        return view('admin.diagramacao.show', compact('edicao'));
    }

    /**
     * Editar diagramação de uma edição
     */
    public function edit($id)
    {
        $edicao = Edicao::with(['materias.tipo', 'materias.orgao'])
            ->findOrFail($id);

        // Verificar se edição não está publicada
        if ($edicao->publicado) {
            return redirect()->route('admin.diagramacao.index')
                ->with('error', 'Não é possível editar uma edição já publicada');
        }

        // Matérias disponíveis para adicionar
        $materiasDisponiveis = Materia::with(['tipo', 'orgao'])
            ->whereDoesntHave('edicoes')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.diagramacao.edit', compact('edicao', 'materiasDisponiveis'));
    }

    /**
     * API para obter matérias disponíveis
     */
    public function getMateriasDisponiveis(Request $request)
    {
        $query = Materia::with(['tipo', 'orgao'])
            ->whereDoesntHave('edicoes');

        // Filtros opcionais
        if ($request->filled('tipo_id')) {
            $query->where('tipo_id', $request->tipo_id);
        }

        if ($request->filled('orgao_id')) {
            $query->where('orgao_id', $request->orgao_id);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->search . '%')
                  ->orWhere('numero', 'like', '%' . $request->search . '%');
            });
        }

        $materias = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $materias->map(function($materia) {
                return [
                    'id' => $materia->id,
                    'numero' => $materia->numero,
                    'titulo' => $materia->titulo,
                    'tipo' => $materia->tipo->nome ?? 'N/A',
                    'orgao' => $materia->orgao->nome ?? 'N/A',
                    'data' => $materia->data ? $materia->data->format('d/m/Y') : $materia->created_at->format('d/m/Y')
                ];
            })
        ]);
    }

    /**
     * Remover matéria de uma edição
     */
    public function removerMateria(Request $request)
    {
        $request->validate([
            'edicao_id' => 'required|exists:edicoes,id',
            'materia_id' => 'required|exists:materias,id'
        ]);

        try {
            $edicao = Edicao::findOrFail($request->edicao_id);
            
            if ($edicao->publicado) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Não é possível alterar uma edição já publicada'
                ], 422);
            }

            $edicao->materias()->detach($request->materia_id);

            return response()->json([
                'success' => true, 
                'message' => 'Matéria removida da edição com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao remover matéria: ' . $e->getMessage()
            ], 500);
        }
    }
}
