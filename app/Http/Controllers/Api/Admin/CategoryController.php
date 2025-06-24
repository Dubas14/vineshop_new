<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::select('id', 'name', 'slug', 'created_at')->get();
    }

    public function show($id)
    {
        return response()->json(
            Category::select('id', 'name')->findOrFail($id)
        );
    }

    // Змінено: Використання $id замість моделі
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id); // Явне отримання моделі

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|regex:/^[a-z0-9\-]+$/',
        ]);

        $category->name = $validated['name'];
        $category->slug = $validated['slug'] ?? Str::slug($validated['name']);
        $category->save();

        return response()->json(['message' => 'Категорію оновлено']);
    }
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Категорію видалено']);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'slug' => 'required|string|max:60|unique:categories,slug',
        ]);

        $category = new Category();
        $category->name = $validated['name'];
        $category->slug = $validated['slug'];
        $category->save();

        return response()->json([
            'message' => 'Категорію створено успішно',
            'category' => $category
        ], 201);
    }
}
