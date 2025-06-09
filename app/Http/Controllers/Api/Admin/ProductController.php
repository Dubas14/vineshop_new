<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductImage;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('images')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        // збереження головного зображення
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        // 🟢 генеруємо slug з назви
        $data['slug'] = Str::slug($data['name']);

        $product = Product::create($data);

        return response()->json([
            'message' => 'Товар створено',
            'product' => $product
        ], 201);
    }

    public function show(Product $product)
    {
        return $product->load('images');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'image|max:2048',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $imgPath = $img->store('products', 'public');
                $product->images()->create(['path' => $imgPath]);
            }
        }

        $product->update($data);

        return response()->json(['message' => 'Товар оновлено']);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
