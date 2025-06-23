<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Barcode;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductImportController extends Controller
{
    public const MAX_IMPORT_ROWS = 5000;
    public const FALLBACK_CATEGORY_ID = 1;

    public function preview(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt']);

        try {
            $file = $request->file('file');
            $delimiter = $this->detectDelimiter($file->getPathname());
            $rows = [];
            $columns = [];

            if (($handle = fopen($file->getPathname(), 'r')) !== false) {
                $rowIndex = 0;
                while (($data = fgetcsv($handle, 10000, $delimiter)) !== false && count($rows) < 6) {
                    if ($rowIndex === 0) {
                        $columns = array_map('trim', $data);
                    } else {
                        $rows[] = array_map('trim', $data);
                    }
                    $rowIndex++;
                }
                fclose($handle);
            }

            if (empty($columns) || count($rows) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Файл порожній або невалідний'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'columns' => $columns,
                'rows' => array_slice($rows, 0, 5),
                'delimiter' => $delimiter,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка при обробці файлу: ' . $e->getMessage()
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'mapping' => 'required|json',
        ]);

        $mapping = json_decode($request->input('mapping'), true);
        $file = $request->file('file');
        $delimiter = $this->detectDelimiter($file->getPathname());

        Log::info('Mapping:', $mapping);

        $rawData = [];
        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            // Читаємо заголовок
            $header = fgetcsv($handle, 10000, $delimiter);

            // Зчитуємо всі рядки даних
            while (($data = fgetcsv($handle, 10000, $delimiter)) !== false) {
                $trimmedRow = array_map('trim', $data);
                if (count(array_filter($trimmedRow, fn($v) => trim($v) !== '')) === 0) {
                    continue;
                }
                $rawData[] = $trimmedRow;
            }
            fclose($handle);
        }

        if (count($rawData) === 0) {
            return response()->json(['message' => 'Файл порожній або невалідний'], 422);
        }

        $imported = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($rawData as $rowNum => $row) {
                // Перевірка на повністю пустий рядок
                if (count(array_filter($row, fn($v) => $v !== '')) === 0) {
                    continue;
                }

                $barcodes = [];
                $productData = [
                    'supplier_code'  => null,
                    'name'           => null,
                    'country'        => null,
                    'manufacturer'   => null,
                    'brand'          => null,
                    'purchase_price' => null,
                    'sale_price'     => null,
                    'quantity'       => null,
                    'multiplicity'   => null,
                    'category_id'    => null,
                    'price'          => null,
                ];

                // Мапінг колонок на поля
                foreach ($mapping as $colIdx => $field) {
                    $field = ltrim($field, "\xEF\xBB\xBF");
                    if (!$field || !isset($row[$colIdx])) {
                        continue;
                    }
                    $value = $row[$colIdx];
                    if ($value === '') {
                        continue;
                    }

                    if ($field === 'barcode') {
                        $barcodes[] = $value;
                    } elseif ($field === 'category_id') {
                        $categoryName = trim($value);
                        $slug = Str::slug($categoryName);
                        if (empty($slug)) {
                            $slug = 'category-' . uniqid();
                        }
                        $cat = Category::firstOrCreate(
                            ['name' => $categoryName],
                            ['slug' => $slug]
                        );
                        $productData['category_id'] = $cat->id;
                    } elseif (in_array($field, ['purchase_price', 'sale_price', 'quantity', 'multiplicity'])) {
                        $productData[$field] = $this->cleanValue($value, $field);
                    } else {
                        $productData[$field] = $value;
                    }
                }

                // Якщо категорію не вказано, fallback
                if (!$productData['category_id']) {
                    $productData['category_id'] = self::FALLBACK_CATEGORY_ID;
                }

                // Валідація обов'язкових полів
                $nameValue = $productData['name'] ?? null;
                if (!$nameValue) {
                    $errors[] = "Рядок " . ($rowNum + 2) . ": не вказано назву товару";
                    continue;
                }

                if (empty($barcodes)) {
                    $errors[] = "Рядок " . ($rowNum + 2) . ": не вказано жодного штрихкоду";
                    continue;
                }

                // Перевірка числових полів
                $numericErrors = [];
                foreach (['purchase_price', 'sale_price', 'quantity', 'multiplicity'] as $field) {
                    // Перевіряємо ТІЛЬКИ якщо value не null і не ''
                    if (isset($productData[$field]) && $productData[$field] !== null && $productData[$field] !== '') {
                        if (!is_numeric($productData[$field])) {
                            $numericErrors[] = $field;
                        }
                    }
                }
                if (!empty($numericErrors)) {
                    $errors[] = "Рядок " . ($rowNum + 2) . ": некоректні числові значення для полів: " .
                        implode(', ', $numericErrors);
                    continue;
                }

                // Розрахунок ціни (якщо потрібно)
                $productData['price'] = $this->calculatePrice($productData);

                // Пошук існуючого товару за штрихкодом
                $product = null;
                foreach ($barcodes as $barcode) {
                    $barcodeRow = Barcode::where('barcode', $barcode)->first();
                    if ($barcodeRow) {
                        $product = $barcodeRow->product;
                        break;
                    }
                }

                // Створення або оновлення товару
                if (!$product) {
                    $product = Product::create([
                        'sku'            => $this->generateUniqueSku($productData['supplier_code']),
                        'name'           => $nameValue,
                        'slug'           => Str::slug($nameValue) . '-' . Str::random(4),
                        'supplier_code'  => $productData['supplier_code'],
                        'country'        => $productData['country'],
                        'manufacturer'   => $productData['manufacturer'],
                        'brand'          => $productData['brand'],
                        'purchase_price' => $productData['purchase_price'] ?? 0,
                        'sale_price'     => $productData['sale_price'] ?? 0,
                        'price'          => $productData['price'] ?? 0,
                        'quantity'       => $productData['quantity'] ?? 0,
                        'multiplicity'   => $productData['multiplicity'] ?? 1,
                        'category_id'    => $productData['category_id'],
                    ]);
                } else {
                    $updateData = array_filter($productData, fn($v) => !is_null($v));
                    unset($updateData['price']); // Не оновлюємо розрахункове поле
                    $product->fill($updateData);
                    $product->save();
                }

                // Додаємо штрихкоди
                foreach ($barcodes as $barcode) {
                    Barcode::firstOrCreate([
                        'product_id' => $product->id,
                        'barcode'    => $barcode,
                    ]);
                }

                $imported++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Помилка при імпорті: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['message' => 'Помилка при імпорті: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'count' => $imported,
            'errors' => $errors,
        ]);
    }

    private function detectDelimiter($csvFile)
    {
        $bom = pack('H*', 'EFBBBF');
        $firstLine = file_get_contents($csvFile, false, null, 0, 1000);

        // Видалення BOM
        if (strpos($firstLine, $bom) === 0) {
            $firstLine = substr($firstLine, 3);
        }

        // Перевірка на пустий файл
        if (empty(trim($firstLine))) {
            return ',';
        }

        $delimiters = [",", ";", "\t", "|"];
        $maxCount = 0;
        $detected = ',';

        // Знаходимо перший непустий рядок
        $lines = explode("\n", $firstLine);
        foreach ($lines as $line) {
            if (!empty(trim($line))) {
                $firstLine = $line;
                break;
            }
        }

        // Визначаємо роздільник
        foreach ($delimiters as $d) {
            $fields = str_getcsv($firstLine, $d);
            if (count($fields) > $maxCount) {
                $maxCount = count($fields);
                $detected = $d;
            }
        }
        return $detected;
    }

    private function cleanValue($value, $field)
    {
        switch ($field) {
            case 'purchase_price':
            case 'sale_price':
                // Видаляємо всі нецифрові символи крім крапки та коми
                $cleaned = preg_replace('/[^0-9.,\-]/', '', $value);
                // Замінюємо коми на крапки для float
                $cleaned = str_replace(',', '.', $cleaned);
                return is_numeric($cleaned) ? (float)$cleaned : null;
            case 'quantity':
            case 'multiplicity':
                return (int) preg_replace('/[^0-9\-]/', '', $value);
            default:
                return $value;
        }
    }

    private function calculatePrice(array $productData)
    {
        if (isset($productData['sale_price']) && is_numeric($productData['sale_price'])) {
            return (float) $productData['sale_price'];
        }
        if (isset($productData['purchase_price']) && is_numeric($productData['purchase_price'])) {
            return round((float) $productData['purchase_price'] * 1.2, 2);
        }
        return 0;
    }

    private function generateUniqueSku($supplierCode = null)
    {
        $prefix = 'SKU';
        if ($supplierCode) {
            $cleanCode = preg_replace('/[^A-Z0-9]/i', '', $supplierCode);
            $prefix = Str::upper(Str::limit($cleanCode, 3, ''));
        }
        $sku = $prefix . '_' . Str::upper(Str::random(6));
        while (Product::where('sku', $sku)->exists()) {
            $sku = $prefix . '_' . Str::upper(Str::random(6));
        }
        return $sku;
    }
}
