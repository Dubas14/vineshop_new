<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Вино', 'slug' => 'vino']);
        Category::create(['name' => 'Ігристе', 'slug' => 'ihreste']);
        Category::create(['name' => 'Міцне', 'slug' => 'mitsne']);
    }
}
