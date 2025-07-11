<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('images');
        $category = null;

        // Фільтрація за slug
        if ($request->has('category')) {
            $category = Category::with('children')->where('slug', $request->category)->first();

            if ($category) {
                // Отримуємо всі ID підкатегорій, включаючи поточну
                $categoryIds = $this->getAllCategoryIds($category);
                $query->whereIn('category_id', $categoryIds);
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

    // Якщо використовуєш byCategory — теж рекурсивно:
    public function byCategory($id)
    {
        $category = Category::with('children')->findOrFail($id);
        $categoryIds = $this->getAllCategoryIds($category);
        $products = Product::with('images')
            ->whereIn('category_id', $categoryIds)
            ->latest()
            ->paginate(12);

        return view('pages.catalog', compact('products', 'category'));
    }

    // Рекурсивно збирає всі id (поточної + дочірніх + онуків і т.д.)
    private function getAllCategoryIds($category)
    {
        $ids = [$category->id];
        foreach ($category->children as $child) {
            $ids = array_merge($ids, $this->getAllCategoryIds($child));
        }
        return $ids;
    }
}
