<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        if($user->role->name==='admin') {
            return view('dashboard.admin');
        }

        return redirect()->route('login')->withErrors('role tidak dikenali');
    }
}
