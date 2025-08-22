<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EntidadeController extends Controller
{
    public function index()
    {
        return view('admin.entidade.index');
    }

    public function update(Request $request)
    {
        // Lógica para atualizar dados da entidade
        return redirect()->back()->with('success', 'Dados da entidade atualizados com sucesso');
    }
}
