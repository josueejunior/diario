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
        $materias = Materia::whereHas('edicao', function ($query) {
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
}
