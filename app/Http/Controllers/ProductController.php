<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with('category', 'images')
            ->where('slug', $slug)
            ->firstOrFail();
        return view('pages.product', compact('product'));
    }
}
