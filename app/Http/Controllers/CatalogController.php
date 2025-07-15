<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('images')
            ->where('is_active', true);
        $category = null;

        if ($request->has('category')) {
            $category = Category::with('children')->where('slug', $request->category)->first();

            if ($category) {
                $categoryIds = $this->getAllCategoryIds($category);
                $query->whereIn('category_id', $categoryIds);
            } else {
                return view('pages.catalog', [
                    'products' => collect(),
                    'category' => null,
                ]);
            }
        }

        // --- ДОДАТКОВІ ФІЛЬТРИ ---
        if ($request->filled('country')) {
            $query->where('country', $request->input('country'));
        }
        if ($request->filled('manufacturer')) {
            $query->where('manufacturer', $request->input('manufacturer'));
        }
        if ($request->filled('color')) {
            $query->where('color', $request->input('color'));
        }
        if ($request->filled('sugar')) {
            $query->where('sugar_content', $request->input('sugar'));
        }
        if ($request->filled('volume')) {
            $query->where('volume', $request->input('volume'));
        }
        if ($request->filled('brand')) {
            $query->where('brand', $request->input('brand'));
        }
        // Додавай аналогічно інші параметри (type, region, etc.)

        $products = $query->latest()->paginate(12);

        return view('pages.catalog', [
            'products' => $products,
            'category' => $category,
            // можливо, сюди ж повернути request()->all() для відображення активних фільтрів
        ]);
    }
    public function byCategory($id)
    {
        $category = Category::with('children')->findOrFail($id);
        $categoryIds = $this->getAllCategoryIds($category);
        $products = Product::with('images')
            ->whereIn('category_id', $categoryIds)
            ->where('is_active', true)
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
