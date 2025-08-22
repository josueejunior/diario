<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PesquisasSiteController extends Controller
{
    public function index()
    {
        return view('admin.pesquisas-site.index');
    }

    public function relatorio()
    {
        return view('admin.pesquisas-site.relatorio');
    }

    public function export(Request $request)
    {
        // Lógica para exportar pesquisas
        return response()->json(['success' => true, 'message' => 'Relatório exportado com sucesso']);
    }
}
