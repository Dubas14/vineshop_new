<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('uk_UA');
        $products = Product::pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            $order = Order::create([
                'name' => $faker->firstName,
                'phone' => $faker->unique()->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'status' => $faker->randomElement(['Підтверджено', 'Очікує підтвердження', 'Скасовано']),
                'user_id' => null, // або $faker->numberBetween(1, 10)
                'created_at' => $faker->dateTimeBetween('-30 days', 'now'),
                'updated_at' => now(),
            ]);

            // Додаємо товари у замовлення
            $itemsCount = rand(1, 4);
            $productIds = $faker->randomElements($products, $itemsCount);

            foreach ($productIds as $pid) {
                $order->items()->create([
                    'product_id' => $pid,
                    'quantity' => rand(1, 5),
                    'price' => $faker->randomFloat(2, 100, 3000),
                    'discount' => $faker->randomElement([0, 5, 10, 15]),
                ]);
            }
        }
    }
}
