<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Фільтрація за категорією
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Сортування за ціною
        if ($request->sort === 'price_asc') {
            $query->orderBy('price');
        } elseif ($request->sort === 'price_desc') {
            $query->orderByDesc('price');
        } else {
            $query->latest(); // за замовчуванням — нові
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('pages.catalog', compact('products', 'categories'));
    }
}
