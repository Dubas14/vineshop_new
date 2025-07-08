<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Barcode;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ProductImageImportController extends Controller
{
    /**
     * Обробка завантаження файлів (по штрихкоду в імені файлу).
     */
    public function import(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'file|mimes:jpg,jpeg,png|max:5120', // Макс 5MB
            'set_as_main' => 'sometimes|boolean', // Опція встановлення як головного зображення
            'overwrite' => 'sometimes|boolean', // Опція перезапису існуючих зображень
        ]);

        $imported = [];
        $not_found = [];
        $skipped = [];
        $errors = [];

        foreach ($request->file('images') as $file) {
            try {
                // Беремо штрихкод з назви файлу (до розширення)
                $barcodeValue = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                if (empty($barcodeValue)) {
                    $errors[] = __('messages.file_no_name', ['filename' => $file->getClientOriginalName()]);
                    continue;
                }

                $barcode = Barcode::where('barcode', $barcodeValue)->first();

                if (!$barcode) {
                    $not_found[] = $barcodeValue;
                    continue;
                }

                $product = Product::find($barcode->product_id);
                if (!$product) {
                    $not_found[] = $barcodeValue . ' ' . __('messages.product_not_found');
                    continue;
                }

                // Генеруємо унікальне ім'я файлу
                $extension = strtolower($file->getClientOriginalExtension());
                $newFileName = Str::slug($product->name) . '_' . uniqid() . '.' . $extension;
                $storagePath = 'products/' . $newFileName;

                // Читаємо зображення
                $image = Image::read($file);

                // Зменшення розміру якщо потрібно
                if ($image->width() > 2000 || $image->height() > 2000) {
                    $image = $image->resize(2000, 2000);
                }

                // Збереження оптимізованого зображення (JPEG/PNG)
                if ($extension === 'jpg' || $extension === 'jpeg') {
                    Storage::disk('public')->put($storagePath, (string) $image->toJpeg(80));
                } elseif ($extension === 'png') {
                    Storage::disk('public')->put($storagePath, (string) $image->toPng());
                } else {
                    Storage::disk('public')->put($storagePath, (string) $image->toJpeg(80));
                }

                // Перевірка на наявність такого зображення
                if ($request->input('overwrite', false)) {
                    ProductImage::where('product_id', $product->id)
                        ->where('path', $storagePath)
                        ->delete();
                } elseif (ProductImage::where('product_id', $product->id)
                    ->where('path', $storagePath)
                    ->exists()) {
                    $skipped[] = $barcodeValue . ' ' . __('messages.image_already_exists');
                    continue;
                }

                // Додаємо в галерею
                $imageRecord = ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $storagePath,
                    'is_main' => false,
                ]);

                // Встановлення як головного зображення
                if ($request->input('set_as_main', false) || !$product->image) {
                    $product->image = $storagePath;
                    $product->save();
                    $imageRecord->update(['is_main' => true]);
                }

                $imported[] = [
                    'barcode' => $barcodeValue,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'image_path' => $storagePath,
                ];
            } catch (\Exception $e) {
                $errors[] = __('messages.error_processing_file', [
                    'filename' => $file->getClientOriginalName(),
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }

        return response()->json([
            'success' => true,
            'imported' => $imported,
            'not_found' => $not_found,
            'skipped' => $skipped,
            'errors' => $errors,
            'stats' => [
                'total' => count($request->file('images')),
                'imported' => count($imported),
                'not_found' => count($not_found),
                'skipped' => count($skipped),
                'errors' => count($errors),
            ],
            'message' => __('messages.processing_completed'),
        ]);
    }
}
