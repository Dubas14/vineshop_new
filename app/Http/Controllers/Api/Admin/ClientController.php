<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = User::select('id', 'name', 'email', 'phone', 'created_at', 'discount')
            ->where('is_admin', false)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($user) {
                $orders = \App\Models\Order::where('user_id', $user->id)->pluck('id');
                $orders_count = $orders->count();
                $orders_sum = \App\Models\OrderItem::whereIn('order_id', $orders)
                    ->selectRaw('SUM(price * quantity) as total')
                    ->value('total') ?? 0;

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'created_at' => $user->created_at->format('d M Y, H:i'),
                    'orders_count' => $orders_count,
                    'orders_sum' => number_format($orders_sum, 2, '.', ' '),
                    'discount' => $user->discount, // <-- тепер завжди буде
                ];
            });

        return response()->json($clients);
    }

    public function update(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->discount = $request->input('discount', 0);
        $user->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);

        // Якщо не хочеш видаляти адміністраторів випадково:
        if ($user->is_admin) {
            return response()->json(['error' => 'Неможливо видалити адміністратора!'], 403);
        }

        $user->delete();

        return response()->json(['success' => true]);
    }
}
