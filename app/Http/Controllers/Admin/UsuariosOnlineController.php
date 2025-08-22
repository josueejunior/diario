<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsuariosOnlineController extends Controller
{
    public function index()
    {
        return view('admin.usuarios-online.index');
    }

    public function sessoes()
    {
        return view('admin.usuarios-online.sessoes');
    }
}
