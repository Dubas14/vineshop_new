<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Список замовлень
    public function index()
    {
        $orders = Order::with('items')->latest()->get();

        return response()->json([
            'data' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'name' => $order->name,
                    'email' => $order->email,
                    'status' => $order->status,
                    'total_price' => $order->items->sum(fn($item) => $item->price * $item->quantity),
                ];
            }),
        ]);
    }

    // Перегляд одного замовлення
    public function show($id)
    {
        $order = Order::with(['items.product'])->findOrFail($id);

        $total = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return response()->json([
            'id' => $order->id,
            'name' => $order->name,
            'email' => $order->email,
            'status' => $order->status,
            'items' => $order->items,
            'total' => $total,
        ]);
    }

    // Оновлення замовлення (наприклад, статус або кількість)
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->input('status');
        $order->save();

        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $orderItem = $order->items()->where('id', $item['id'])->first();
                if ($orderItem) {
                    $orderItem->update([
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'discount' => $item['discount'] ?? 0,
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Оновлено']);
    }
}
