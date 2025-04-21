<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create(Role $roles )
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah User',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'berhasil menambahkan user');
    }

    public function edit(User $user, Role $roles)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role_id'=> 'required|exists:roles,id',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update User',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);

        

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id'=> $request->role_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'berhasil memperbarui user');
        
    }

    public function destroy(User $user)
    {
        $user->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus User',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'berhasil menghapus user');
    }


    public function log(Request $request)
    {
        {
            $logs = ActivityLog::with('user')
            ->when($request->filled('user_id'), function ($query) use ($request) {
                return $query->where('user_id', $request->user_id);
            })
            ->when($request->filled('date'), function ($query) use ($request) {
                return $query->whereDate('created_at', $request->date);
            })
            ->latest()
            ->paginate(10);
    
            return view('admin.logs.index', compact('logs'));
        }
    }
}
