<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['name' => 'Вино', 'slug' => 'vino']);
        Category::create(['name' => 'Ігристе', 'slug' => 'ihreste']);
        Category::create(['name' => 'Міцне', 'slug' => 'mitsne']);
        Category::create(['name' => 'Рожеве', 'slug' => 'rozheve']);
        Category::create(['name' => 'Біле', 'slug' => 'bile']);
        Category::create(['name' => 'Червоне', 'slug' => 'chervone']);
    }
}
