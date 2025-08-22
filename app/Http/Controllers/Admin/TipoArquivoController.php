<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoArquivoController extends Controller
{
    public function index()
    {
        return view('admin.tipos-arquivos.index');
    }

    public function create()
    {
        return view('admin.tipos-arquivos.create');
    }

    public function store(Request $request)
    {
        // Lógica para criar tipo de arquivo
        return redirect()->route('admin.tipos-arquivos.index')->with('success', 'Tipo de arquivo criado com sucesso');
    }

    public function show($id)
    {
        return view('admin.tipos-arquivos.show', compact('id'));
    }

    public function edit($id)
    {
        return view('admin.tipos-arquivos.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Lógica para atualizar tipo de arquivo
        return redirect()->route('admin.tipos-arquivos.index')->with('success', 'Tipo de arquivo atualizado com sucesso');
    }

    public function destroy($id)
    {
        // Lógica para excluir tipo de arquivo
        return redirect()->route('admin.tipos-arquivos.index')->with('success', 'Tipo de arquivo excluído com sucesso');
    }
}
