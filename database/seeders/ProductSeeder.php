<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categoryMap = Category::pluck('id', 'name')->toArray();

        $products = [
            ['Шардоне 2022', 'Біле', 345.50],
            ['Рислінг німецький', 'Біле', 410.00],
            ['Каберне Совіньйон', 'Червоне', 499.00],
            ['Мерло італійське', 'Червоне', 395.75],
            ['Рожеве Франція', 'Рожеве', 379.00],
            ['Міцна настоянка', 'Міцне', 255.00],
            ['Коньяк VSOP', 'Міцне', 980.00],
            ['Просекко Brut', 'Ігристе', 475.50],
            ['Шампанське Moët', 'Ігристе', 1220.00],
            ['Сапераві', 'Червоне', 320.00],
            ['Авторське біле', 'Біле', 285.00],
            ['Рожеве сухе', 'Рожеве', 360.00],
            ['Грузинське вино', 'Червоне', 420.00],
            ['Піно Нуар', 'Вино', 410.00],
            ['Мерло Селект', 'Вино', 390.00],
        ];

        foreach ($products as [$name, $categoryName, $price]) {
            if (!isset($categoryMap[$categoryName])) {
                continue;
            }

            Product::create([
                'name' => $name,
                'slug' => Str::slug($name) . '-' . rand(100, 999), // Унікальний slug
                'description' => 'Опис до товару "' . $name . '"',
                'price' => $price,
                'category_id' => $categoryMap[$categoryName],
                'image' => 'default.jpg',
            ]);
        }
    }
}
