<?php

namespace App\Http\Controllers;

use App\Models\Menus;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function indexMenu()
    {
        $menus = Menus::all();
        return view('manager.menu.index', compact('menus'));
    }

    public function createMenu()
    {
        return view('manager.menu.create');
    }



    

    public function storeMenu(Request $request, Menus $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'tambah menu (' . $menu->name . ')',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);

        Menus::create($request->all());

        
        return redirect()->route('manager.menu.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function editMenu(Menus $menu)
    {
        return view('manager.menu.edit', compact('menu'));
    }

    public function updateMenu(Request $request, Menus $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update menu (' . $menu->name . ')',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);


        $menu->update($request->all());

        return redirect()->route('manager.menu.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroyMenu(Menus $menu)
    {
        $menu->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus Menu (' . $menu->name . ')',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);

        return redirect()->route('manager.menu.index')->with('success', 'Menu berhasil dihapus!');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $menus = Menus::where('name', 'like', "%{$query}%")->get();

        return response()->json($menus);
    }
}
