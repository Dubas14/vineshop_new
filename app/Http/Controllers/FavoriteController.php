<?php

namespace App\Http\Controllers;

use App\Models\Product;

class FavoriteController extends Controller
{
    public function index()
    {
        $products = auth()->user()->favoriteProducts()->with('category')->get();
        return view('account.favorites.index', compact('products'));
    }

    public function store(Product $product)
    {
        auth()->user()->favoriteProducts()->syncWithoutDetaching($product->id);
        return back();
    }

    public function destroy(Product $product)
    {
        auth()->user()->favoriteProducts()->detach($product->id);
        return back();
    }
}
