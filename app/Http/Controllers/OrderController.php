<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'email' => 'nullable|email',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Кошик порожній'], 422);
            }
            return redirect()->back()->with('error', 'Кошик порожній');
        }

        $order = Order::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'status' => 'Очікує підтвердження',
            'user_id' => auth()->id(),
        ]);

        foreach ($cart as $productId => $item) {
            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        session()->forget('cart');

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Замовлення успішно оформлено']);
        }

        return redirect()->route('home')->with('success', 'Замовлення успішно оформлено!');
    }
}
