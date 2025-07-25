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
            'user_id' => auth()->check() ? auth()->id() : null,
        ]);

        // Отримати знижку користувача
        $userDiscount = 0;
        if (auth()->check()) {
            $userDiscount = auth()->user()->discount ?? 0;
        }

        foreach ($cart as $productId => $item) {
            $basePrice = $item['price'];
            $priceWithDiscount = $basePrice;
            if ($userDiscount > 0) {
                $priceWithDiscount = $basePrice * (1 - $userDiscount / 100);
            }
            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => round($priceWithDiscount, 2), // Вже зі знижкою!
                'discount' => $userDiscount,
            ]);
        }

        session()->forget('cart');

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Замовлення успішно оформлено']);
        }

        return redirect()->route('home')->with('success', 'Замовлення успішно оформлено!');
    }
}
