<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        return view('admin.analytics.dashboard');
    }

    public function visitas()
    {
        return view('admin.analytics.visitas');
    }

    public function documentos()
    {
        return view('admin.analytics.documentos');
    }
}
