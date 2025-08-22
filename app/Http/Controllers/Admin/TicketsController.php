<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    public function meus()
    {
        return view('admin.tickets.meus');
    }

    public function store(Request $request)
    {
        // Lógica para criar ticket
        return redirect()->back()->with('success', 'Ticket criado com sucesso');
    }

    public function show($id)
    {
        return view('admin.tickets.show', compact('id'));
    }

    public function responder(Request $request, $id)
    {
        // Lógica para responder ticket
        return redirect()->back()->with('success', 'Resposta enviada com sucesso');
    }
}
