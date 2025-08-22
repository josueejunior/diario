<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function dashboard()
    {
        return view('admin.api.dashboard');
    }

    public function tokens()
    {
        return view('admin.api.tokens');
    }
}
