<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request, User $user)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
    
            $user = Auth::user();
            $this->logActivity($user, 'Login ke sistem');
            
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }


    public function logout()
    {
        $user = Auth::user();

        if($user) {
            $this->logActivity($user, 'user logout dari sistem');
        }

        Auth::logout();
        return redirect()->route('login');
    }

    public function LogActivity($user, $action)
    {
        ActivityLog::create([
            'user_id'=> $user->id,
            'action'=> $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User_agent'),
        ]);
    }

}
