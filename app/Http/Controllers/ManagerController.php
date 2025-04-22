<?php

namespace App\Http\Controllers;

use App\Models\Menus;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Exports\reportExport;
use Maatwebsite\Excel\Facades\Excel;


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

    public function log(Request $request)
    { {
            $logs = ActivityLog::with('user')
                ->when($request->filled('user_id'), function ($query) use ($request) {
                    return $query->where('user_id', $request->user_id);
                })
                ->when($request->filled('date'), function ($query) use ($request) {
                    return $query->whereDate('created_at', $request->date);
                })
                ->latest()
                ->paginate(10);

            return view('manager.logs', compact('logs'));
        }
    }

    public function report(Request $request)
{
    $query = Transaction::with('cashier');

    if ($request->filter_type == 'day' && $request->date) {
        $query->whereDate('paid_at', $request->date);
    } elseif ($request->filter_type == 'month' && $request->month) {
        $query->whereMonth('paid_at', date('m', strtotime($request->month)))
              ->whereYear('paid_at', date('Y', strtotime($request->month)));
    }

    $transactions = $query->orderBy('paid_at', 'desc')->get();
    $totalRevenue = $transactions->sum('total_price');

    return view('manager.report', compact('transactions', 'totalRevenue'));
}


    public function history(Request $request)
{
    $transactions = Transaction::with('cashier', 'order')
        ->when($request->cashier_id, function ($query) use ($request) {
            $query->where('cashier_id', $request->cashier_id);
        })
        ->when($request->date, function ($query) use ($request) {
            $query->whereDate('paid_at', $request->date);
        })
        ->whereNotNull('paid_at')
        ->orderBy('paid_at', 'desc')
        ->get();

    return view('manager.history', compact('transactions'));
}


    public function exportExcel(Request $request)
{
    $filterType = $request->filter_type;
    $date = $request->date;
    $month = $request->month;

    $fileName = 'laporan_pendapatan_' . now()->format('Ymd_His') . '.xlsx';
    return Excel::download(new reportExport($filterType, $date, $month), $fileName);
}


}
