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

        foreach ($orders as $order) {
            $order->total_price = $order->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });
        }

        return response()->json($orders);
    }

    // Перегляд одного замовлення
    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        $order->total_price = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return response()->json($order);
    }

    // Оновлення замовлення (наприклад, статус або кількість)
    public function update(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);

        $data = $request->validate([
            'status' => 'required|string|max:255',
            'items' => 'array',
            'items.*.id' => 'exists:order_items,id',
            'items.*.price' => 'numeric',
            'items.*.quantity' => 'integer',
        ]);

        $order->status = $data['status'];
        $order->save();

        // Якщо оновлюються окремі товари
        if (isset($data['items'])) {
            foreach ($data['items'] as $itemData) {
                $item = $order->items->firstWhere('id', $itemData['id']);
                if ($item) {
                    $item->price = $itemData['price'];
                    $item->quantity = $itemData['quantity'];
                    $item->save();
                }
            }
        }

        return response()->json(['message' => 'Замовлення оновлено']);
    }
}
