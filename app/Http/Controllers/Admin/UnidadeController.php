<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{
    public function index()
    {
        return view('admin.unidades.index');
    }

    public function create()
    {
        return view('admin.unidades.create');
    }

    public function store(Request $request)
    {
        // Lógica para criar unidade/departamento
        return redirect()->route('admin.unidades.index')->with('success', 'Unidade criada com sucesso');
    }

    public function show($id)
    {
        return view('admin.unidades.show', compact('id'));
    }

    public function edit($id)
    {
        return view('admin.unidades.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Lógica para atualizar unidade/departamento
        return redirect()->route('admin.unidades.index')->with('success', 'Unidade atualizada com sucesso');
    }

    public function destroy($id)
    {
        // Lógica para excluir unidade/departamento
        return redirect()->route('admin.unidades.index')->with('success', 'Unidade excluída com sucesso');
    }
}
