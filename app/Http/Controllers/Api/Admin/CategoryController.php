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
        return Category::select('id', 'name', 'slug', 'created_at', 'parent_id')
            ->with(['children' => function ($q) {
                $q->select('id', 'name', 'slug', 'created_at', 'parent_id');
            }])
            ->whereNull('parent_id')
            ->get();
    }

    public function show($id)
    {
        return response()->json(
            Category::select('id', 'name')->findOrFail($id)
        );
    }

    public function allFlat()
    {
        return Category::select('id', 'name', 'parent_id')->orderBy('name')->get();
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:60|unique:categories,slug',
        ]);

        $category->name = $validated['name'];
        $category->slug = $validated['slug'] ?? Str::slug($validated['name']);
        $category->save();

        return response()->json(['message' => 'Category updated successfully']);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
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
        $category->parent_id = $request->parent_id ?? null;
        $category->save();

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }
}
