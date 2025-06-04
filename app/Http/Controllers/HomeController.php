<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::limit(4)->get();
        $products = Product::latest()->limit(8)->get();
        $banners = Banner::where('is_active', true)
            ->orderByDesc('created_at')
            ->get();

        return view('pages.home', compact('categories', 'products', 'banners'));
    }
}
