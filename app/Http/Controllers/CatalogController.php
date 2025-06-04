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
        $category = null;

        // Фільтрація за slug
        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();

            if ($category) {
                $query->where('category_id', $category->id);
            } else {
                // Якщо slug не знайдено — відобразити пустий результат
                return view('pages.catalog', [
                    'products' => collect(),
                    'category' => null,
                ]);
            }
        }

        $products = $query->latest()->paginate(12);

        return view('pages.catalog', [
            'products' => $products,
            'category' => $category,
        ]);
    }
    public function byCategory($id)
    {
        $category = Category::findOrFail($id);
        $products = Product::where('category_id', $id)->latest()->paginate(12);

        return view('pages.catalog', compact('products', 'category'));
    }
}
