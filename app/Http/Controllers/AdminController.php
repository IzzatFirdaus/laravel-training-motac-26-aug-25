<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function dashboard(): \Illuminate\View\View
    {
        return view('admin.sb.dashboard');
    }

    public function login(): \Illuminate\View\View
    {
        return view('admin.sb.login');
    }
}
