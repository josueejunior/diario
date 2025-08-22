<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use Illuminate\Http\Request;

class MateriasReprovadasController extends Controller
{
    public function index()
    {
        $materiasReprovadas = Materia::where('status', 'reprovada')
            ->with(['tipo', 'orgao'])
            ->paginate(15);

        return view('admin.materias-reprovadas.index', compact('materiasReprovadas'));
    }

    public function revisar(Request $request, Materia $materia)
    {
        $materia->update([
            'status' => 'rascunho',
            'observacoes' => $request->observacoes
        ]);

        return redirect()->back()->with('success', 'Matéria enviada para revisão');
    }

    public function destroy(Materia $materia)
    {
        $materia->delete();
        return redirect()->back()->with('success', 'Matéria excluída com sucesso');
    }
}
