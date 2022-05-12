<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $get_all = SystemLog::orderBy('created_at', 'desc')->get();
        return view('all_data', compact('get_all'));
    }
}
