<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('images')
            ->orderByDesc('id')
            ->paginate(15); // або скільки хочеш на сторінку

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'brand' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'classification' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'package_type' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'sugar_content' => 'nullable|string|max:255',
            'volume' => 'nullable|numeric',
            'sort' => 'nullable|string|max:255',
            'taste' => 'nullable|string',
            'aroma' => 'nullable|string',
            'pairing' => 'nullable|string',
            'old_price' => 'nullable|numeric',
        ]);

        // збереження головного зображення
        $path = $request->file('image')->store('products', 'public');
        $data['image'] = $path;

        // генеруємо slug з назви
        $data['slug'] = Str::slug($data['name']);

        $product = Product::create($data);

        // додаємо головне зображення до галереї
        $product->images()->create(['path' => $path]);

        // збереження додаткових зображень (галереї)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $imgPath = $img->store('products', 'public');
                $product->images()->create(['path' => $imgPath]);
            }
        }

        return response()->json([
            'message' => 'Товар створено',
            'product' => $product->load('images')
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
            'images.*' => 'nullable|image|max:2048',
            'brand' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'classification' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'package_type' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'sugar_content' => 'nullable|string|max:255',
            'volume' => 'nullable|numeric',
            'sort' => 'nullable|string|max:255',
            'taste' => 'nullable|string',
            'aroma' => 'nullable|string',
            'pairing' => 'nullable|string',
            'old_price' => 'nullable|numeric',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;

            // додаємо нове головне зображення також у галерею
            $product->images()->create(['path' => $path]);
        } elseif ($request->input('image_deleted')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = null;
        } else {
            $data['image'] = $product->image;
        }

        $product->update($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $imgPath = $img->store('product_gallery', 'public');
                $product->images()->create(['path' => $imgPath]);
            }
        }

        return response()->json([
            'message' => 'Товар оновлено',
            'product' => $product->load('images')
        ]);
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $product->delete();
        return response()->json(['message' => 'Товар видалено']);
    }

    public function destroyImage(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
            $product->image = null;
            $product->save();
        }

        return response()->json(['message' => 'Головне зображення видалено']);
    }

    public function destroyGalleryImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return response()->json(['message' => 'Зображення галереї видалено']);
    }
}
