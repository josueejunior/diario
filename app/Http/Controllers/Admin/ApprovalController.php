<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function pending()
    {
        return view('admin.approval.pending');
    }

    public function history()
    {
        return view('admin.approval.history');
    }
}
