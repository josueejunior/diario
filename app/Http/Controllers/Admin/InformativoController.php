<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InformativoController extends Controller
{
    public function index()
    {
        return view('admin.informativos.index');
    }

    public function create()
    {
        return view('admin.informativos.create');
    }

    public function store(Request $request)
    {
        // Lógica para criar informativo
        return redirect()->route('admin.informativos.index')->with('success', 'Informativo criado com sucesso');
    }

    public function show($id)
    {
        return view('admin.informativos.show', compact('id'));
    }

    public function edit($id)
    {
        return view('admin.informativos.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Lógica para atualizar informativo
        return redirect()->route('admin.informativos.index')->with('success', 'Informativo atualizado com sucesso');
    }

    public function destroy($id)
    {
        // Lógica para excluir informativo
        return redirect()->route('admin.informativos.index')->with('success', 'Informativo excluído com sucesso');
    }
}
