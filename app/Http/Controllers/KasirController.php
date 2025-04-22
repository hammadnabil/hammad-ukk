<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\menus;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;


class kasirController extends Controller
{
    public function index()
    {

        $orders = Order::where('status', 'belum dibayar')->get();

        return view('kasir.index', compact('orders'));
    }


    public function show($id)
    {
        $order = Order::findOrFail($id);

        return view('kasir.payment', compact('order'));
    }


    public function processPayment(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $items = json_decode($order->items, true);
        $totalPrice = 0;

        foreach ($items as $item) {
            $menu = Menus::find($item['id']);
            if ($menu) {
                $totalPrice += $menu->price * $item['quantity'];
            }
        }


        $request->validate([
            'cash' => "required|numeric|min:$totalPrice"
        ]);


        $user = Auth::user();


        $transaction = Transaction::create([
            'order_id'    => $order->id,
            'total_price' => $totalPrice,
            'cash'        => $request->cash,
            'change'      => $request->cash - $totalPrice,
            'paid_at'     => now(),
            'cashier_id'  => $user->id,
        ]);


        $order->status = 'completed';
        $order->save();


        ActivityLog::create([
            'user_id'    => $user->id,
            'action'     => 'Membayar pesanan dengan kode ' . $order->order_code,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);


        return redirect()->route('kasir.index')->with('success', 'Pembayaran berhasil!');
    }


    public function history()
    {

        $transactions = Transaction::whereNotNull('paid_at')->get();

        return view('kasir.history', compact('transactions'));
    }
}
