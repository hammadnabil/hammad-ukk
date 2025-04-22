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
        }if($user->role->name==='manager') {
            return view('dashboard.manager');
        }if($user->role->name==='waiter') {
            return view('dashboard.waiter');
        }if($user->role->name==='kasir') {
            return view('dashboard.kasir');
        }

        return redirect()->route('login')->withErrors('role tidak dikenali');
    }
}
