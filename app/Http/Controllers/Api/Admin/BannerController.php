<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    // Повертає всі банери для адмінки
    public function index()
    {
        return Banner::all();
    }

    // Створює новий банер
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'subtitle'     => 'nullable|string|max:255',
            'image'        => 'required|image|max:2048',
            'link_type'    => 'required|in:product,category,custom',
            'link_target'  => 'required|string',
            'button_text'  => 'nullable|string|max:255',
            'is_active'    => 'required|boolean', // Обов’язково має бути 0 або 1!
        ]);

        // Зберігаємо зображення якщо є
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        return Banner::create($data);
    }

    // Повертає один банер по id
    public function show(Banner $banner)
    {
        return $banner;
    }

    // Оновлює існуючий банер
    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'subtitle'     => 'nullable|string|max:255',
            'image'        => 'nullable|image|max:2048',
            'link_type'    => 'required|in:product,category,custom',
            'link_target'  => 'required|string',
            'button_text'  => 'nullable|string|max:255',
            'is_active'    => 'required|boolean', // Теж обов'язково 0 або 1
        ]);

        // Якщо новий файл — видаляємо старий, зберігаємо новий
        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return $banner;
    }

    // Видаляє банер разом з файлом
    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();

        return response()->json(['message' => 'Банер видалено']);
    }

    // Видає тільки активні банери для фронта
    public function public()
    {
        return Banner::where('is_active', true)
            ->orderByDesc('created_at')
            ->get();
    }
}
