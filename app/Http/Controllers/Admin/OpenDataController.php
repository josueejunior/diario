<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OpenDataController extends Controller
{
    public function catalog()
    {
        return view('admin.opendata.catalog');
    }
}
