<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with('category', 'images')
            ->where('slug', $slug)
            ->where('is_active', true) // <--- Додаємо цю умову!
            ->firstOrFail();
        return view('pages.product', compact('product'));
    }
}
