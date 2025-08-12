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
    public function index()
    {
        // Busca as matérias mais recentes de edições publicadas
        $materias = Materia::whereHas('edicoes', function ($query) {
                        $query->where('publicado', true);
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
        
        return view('portal.materias.index', compact('materias'));
    }

    /**
     * Exibe os detalhes de uma matéria específica.
     *
     * @param  \App\Models\Materia  $materia
     * @return \Illuminate\View\View
     */
    public function show(Materia $materia)
    {
        // Verifica se a edição está publicada
        if (!$materia->edicao || !$materia->edicao->publicado) {
            abort(404);
        }
        
        // Registra a visualização
        $materia->visualizacoes()->create([
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        
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
        $materias = Materia::whereHas('edicoes', function ($query) {
                        $query->where('publicado', true);
                    })
                    ->whereHas('tipo', function ($query) use ($tipo) {
                        $query->where('nome', 'like', "%{$tipo}%");
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
        
        return view('portal.materias.tipo', compact('materias', 'tipo', 'titulo'));
    }
}
