<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AjustesGeraisController extends Controller
{
    public function index()
    {
        return view('admin.ajustes-gerais.index');
    }

    public function update(Request $request)
    {
        // Lógica para atualizar ajustes gerais
        return redirect()->back()->with('success', 'Ajustes gerais atualizados com sucesso');
    }
}
