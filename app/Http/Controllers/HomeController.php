<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')->with('children.children')->get();
        $products = Product::with('images')
            ->where('is_active', true)   // Тільки активні!
            ->latest()
            ->limit(8)
            ->get();
        $banners = Banner::where('is_active', true)
            ->orderByDesc('created_at')
            ->get();

        return view('pages.home', compact('categories', 'products', 'banners'));
    }
}
