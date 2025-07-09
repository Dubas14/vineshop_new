<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Загальна статистика для карток dashboard
    public function stats()
    {
        return [
            'orders_last_week_sum' => Order::whereBetween('created_at', [now()->subWeek(), now()])
                ->sum('total_sum'),
            'orders_this_week_count' => Order::whereBetween('created_at', [now()->startOfWeek(), now()])
                ->count(),
            'new_customers' => User::where('created_at', '>=', now()->subWeek())->count(),
            // 'visitors' => Visit::where('created_at', '>=', now()->startOfDay())->count(), // Якщо нема моделі Visit, заміни на 0:
            'visitors' => 0,
        ];
    }

    // Дані для DonutChart: кількість товарів у кожній категорії
    public function categoriesStat()
    {
        $categories = \App\Models\Category::all();
        $labels = [];
        $data = [];

        foreach ($categories as $category) {
            $labels[] = $category->name;

            // Сума quantity усіх order_items, де product.category_id = $category->id
            $soldCount = \App\Models\OrderItem::whereHas('product', function ($q) use ($category) {
                $q->where('category_id', $category->id);
            })->sum('quantity');

            $data[] = $soldCount;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    // Дані для LineChart: кількість замовлень по дням за тиждень
    public function ordersPerDay()
    {
        $orders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [now()->subWeek(), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Дні тижня (пн, вт, ...), щоб графік завжди був повний
        $days = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dayLabel = \Carbon\Carbon::parse($date)->isoFormat('dd'); // 'Пн', 'Вт', ...
            $days[] = $dayLabel;
            $order = $orders->firstWhere('date', $date);
            $data[] = $order ? $order->total : 0;
        }

        return [
            'labels' => $days,
            'data'   => $data,
        ];
    }
}
