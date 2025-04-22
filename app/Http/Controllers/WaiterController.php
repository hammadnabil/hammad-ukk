<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Menus;
use Illuminate\Support\Str;

class WaiterController extends Controller
{
    public function indexOrder()
    {
        $orders = Order::where('status', 'belum dibayar')->get()->map(function ($order) {
            $items = json_decode($order->items, true); 
            $order->decoded_items = collect($items ?: [])->map(function ($item) {
                $menu = Menus::find($item['id'] ?? 0);
                $price = $menu ? (int) $menu->price : 0;
                return [
                    'name' => $menu ? $menu->name : 'Tidak diketahui',
                    'price' => $price,
                    'quantity' => (int) ($item['quantity'] ?? 0),
                    'subtotal' => $price * ($item['quantity'] ?? 0)
                ];
            });
            $order->total_price = $order->decoded_items->sum('subtotal');
            return $order;
        });
    
        return view('waiter.orders.index', compact('orders'));
    }

    public function createOrder()
    {
        $menus = Menus::all();
        return view('waiter.orders.create', compact('menus'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'menus' => 'required|array',
            'menus.*.id' => 'exists:menus,id',
            'menus.*.quantity' => 'integer|min:1',
            'menus*.price' => 'exist:menus,price'
        ]);

        $order = Order::create([
            'order_code' => Str::random(8),
            'items' => json_encode($request->menus),
            'status' => 'belum dibayar',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Membuat pesanan dengan kode ' . $order->order_code,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);

        return response()->json([
            'message' => 'Pesanan berhasil dibuat!',
            'order_code' => $order->order_code
        ]);
    }

    public function editOrder($id)
    {
        $order = Order::findOrFail($id);
        $items = json_decode($order->items, true) ?? [];

        $order->decoded_items = collect($items)->map(function ($item) {
            $menu = Menus::find($item['id'] ?? 0);
            return [
                'id' => $item['id'] ?? 0,
                'name' => $menu->name ?? 'Tidak diketahui',
                'price' => (int) ($menu->price ?? 0),
                'quantity' => (int) ($item['quantity'] ?? 0),
                'subtotal' => ($menu->price ?? 0) * ($item['quantity'] ?? 0)
            ];
        });

        $menus = Menus::all();
        return view('waiter.orders.edit', compact('order', 'menus'));
    }

    public function updateOrder(Request $request, $id)
    {
        $request->validate([
            'menus' => 'required|string',
        ]);

        $menus = json_decode($request->menus, true);

        if (!is_array($menus) || empty($menus)) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak valid!'], 400);
        }

        foreach ($menus as $menu) {
            if (!isset($menu['id']) || !isset($menu['quantity'])) {
                return response()->json(['success' => false, 'message' => 'Format pesanan tidak valid!'], 400);
            }
        }
        $order = Order::findOrFail($id);
        $order->setItems(json_encode($menus));
        $order->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Mengedit pesanan dengan kode ' . $order->order_code,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);

        return response()->json(['success' => true, 'message' => 'Pesanan berhasil diperbarui!']);
    }
}
