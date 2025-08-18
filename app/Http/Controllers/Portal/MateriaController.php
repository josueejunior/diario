<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    /**
     * Lista todas as matérias publicadas.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Query base para matérias
        $query = Materia::with(['tipo', 'orgao', 'edicoes'])
                        ->withCount('visualizacoes');

        // Busca por texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'LIKE', "%{$search}%")
                  ->orWhere('texto', 'LIKE', "%{$search}%");
            });
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->whereHas('tipo', function ($q) use ($request) {
                $q->where('slug', $request->tipo);
            });
        }

        // Filtro por órgão
        if ($request->filled('orgao')) {
            $query->where('orgao_id', $request->orgao);
        }

        // Ordenação
        $materias = $query->orderBy('data', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(15);

        // Dados adicionais para os filtros
        $tipos = \App\Models\Tipo::all();
        $orgaos = \App\Models\Orgao::all();
        
        return view('portal.materias.index', compact('materias', 'tipos', 'orgaos'));
    }

    /**
     * Exibe os detalhes de uma matéria específica.
     *
     * @param  \App\Models\Materia  $materia
     * @return \Illuminate\View\View
     */
    public function show(Materia $materia)
    {
        // Carrega as relações necessárias
        $materia->load(['tipo', 'orgao', 'edicoes' => function ($query) {
            $query->orderBy('data', 'desc');
        }]);
        
        // Tenta carregar a contagem de visualizações
        try {
            $materia->loadCount('visualizacoes');
        } catch (\Exception $e) {
            // Se falhar, define como 0
            $materia->visualizacoes_count = 0;
        }
        
        // Registra a visualização de forma segura
        try {
            $materia->visualizacoes()->create([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        } catch (\Exception $e) {
            // Ignora erros de inserção de visualização para não quebrar a página
            \Log::warning('Erro ao registrar visualização: ' . $e->getMessage());
        }
        
        return view('portal.materias.show', compact('materia'));
    }

    /**
     * Exibe portarias publicadas.
     *
     * @return \Illuminate\View\View
     */
    public function portarias()
    {
        return $this->materiaPorTipo('Portaria', 'Portarias');
    }

    /**
     * Exibe decretos publicados.
     *
     * @return \Illuminate\View\View
     */
    public function decretos()
    {
        return $this->materiaPorTipo('Decreto', 'Decretos');
    }

    /**
     * Exibe leis publicadas.
     *
     * @return \Illuminate\View\View
     */
    public function leis()
    {
        return $this->materiaPorTipo('Lei', 'Leis');
    }

    /**
     * Exibe resoluções publicadas.
     *
     * @return \Illuminate\View\View
     */
    public function resolucoes()
    {
        return $this->materiaPorTipo('Resolução', 'Resoluções');
    }

    /**
     * Exibe editais publicados.
     *
     * @return \Illuminate\View\View
     */
    public function editais()
    {
        return $this->materiaPorTipo('Edital', 'Editais');
    }

    /**
     * Método auxiliar para filtrar matérias por tipo.
     *
     * @param string $tipo
     * @param string $titulo
     * @return \Illuminate\View\View
     */
    private function materiaPorTipo($tipo, $titulo)
    {
        // Query base para matérias do tipo específico
        $query = Materia::with(['tipo', 'orgao', 'edicoes'])
                        ->withCount('visualizacoes')
                        ->whereHas('tipo', function ($q) use ($tipo) {
                            $q->where('nome', 'like', "%{$tipo}%");
                        });

        // Busca por texto se fornecido
        if (request()->filled('search')) {
            $search = request()->search;
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'LIKE', "%{$search}%")
                  ->orWhere('texto', 'LIKE', "%{$search}%");
            });
        }

        // Ordenação e paginação
        $materias = $query->orderBy('data', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(15);

        return view('portal.materias.tipo', compact('materias', 'tipo', 'titulo'));
    }
}
