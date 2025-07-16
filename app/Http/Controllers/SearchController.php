<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function autocomplete(Request $request)
    {
        $q = mb_strtolower(trim($request->get('query')));

        if (strlen($q) < 2) {
            return response()->json([
                'products' => [],
                'categories' => [],
            ]);
        }

        // Розбиваємо пошуковий запит на окремі слова
        $searchTerms = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);

        $products = Product::where('is_active', true)
            ->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->where(function ($q) use ($term) {
                        $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                            ->orWhereRaw('LOWER(sku) LIKE ?', ["%{$term}%"])
                            ->orWhereRaw('LOWER(brand) LIKE ?', ["%{$term}%"])
                            ->orWhereRaw('LOWER(manufacturer) LIKE ?', ["%{$term}%"])
                            ->orWhereRaw('LOWER(type) LIKE ?', ["%{$term}%"])
                            ->orWhereRaw('LOWER(color) LIKE ?', ["%{$term}%"])
                            ->orWhereRaw('LOWER(sugar_content) LIKE ?', ["%{$term}%"])
                            ->orWhereRaw('LOWER(taste) LIKE ?', ["%{$term}%"])
                            ->orWhereRaw('LOWER(aroma) LIKE ?', ["%{$term}%"])
                            ->orWhereRaw('LOWER(pairing) LIKE ?', ["%{$term}%"]);
                    });
                }
            })
            ->orWhereHas('category', function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]);
                }
            })
            ->limit(10)
            ->get(['id', 'name', 'slug', 'price', 'image', 'sku']);

        $categories = Category::where(function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]);
            }
        })
            ->limit(5)
            ->get(['id', 'name', 'slug']);

        return response()->json([
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
