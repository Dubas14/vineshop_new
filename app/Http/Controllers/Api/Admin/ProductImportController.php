<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Barcode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ProductImportController extends Controller
{
    /**
     * Повертає превʼю перших 5 рядків з Excel (назви колонок + дані)
     */
    public function preview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xls,xlsx,csv|max:10240' // Максимум 10MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = Excel::toArray([], $request->file('file'))[0];

            if (count($data) === 0) {
                return response()->json(['message' => 'Файл порожній'], 422);
            }

            $columns = [];
            // Якщо в першому рядку текстові дані — це заголовки
            if (count($data) > 0 && $this->rowLooksLikeHeader($data[0])) {
                $columns = array_map('trim', $data[0]);
                $data = array_slice($data, 1); // пропускаємо заголовок
            } else {
                // Генеруємо назви типу "Колонка 1", "Колонка 2", ...
                $columns = array_map(fn($i) => 'Колонка ' . ($i + 1), array_keys($data[0]));
            }

            $previewRows = array_slice($data, 0, 5);

            return response()->json([
                'columns' => $columns,
                'rows' => $previewRows,
                'total_rows' => count($data),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Помилка при читанні файлу: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Імпортує товари із Excel-файлу та мапінгу колонок
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xls,xlsx,csv|max:10240',
            'mapping' => 'required|json',
            'update_existing' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mapping = json_decode($request->input('mapping'), true);
        $updateExisting = $request->input('update_existing', true);

        try {
            $rawData = Excel::toArray([], $request->file('file'))[0];
        } catch (\Exception $e) {
            return response()->json(['message' => 'Помилка при читанні файлу: ' . $e->getMessage()], 500);
        }

        if (count($rawData) === 0) {
            return response()->json(['message' => 'Файл порожній'], 422);
        }

        // Визначаємо, чи є заголовки
        $hasHeader = $this->rowLooksLikeHeader($rawData[0]);
        if ($hasHeader) {
            $rawData = array_slice($rawData, 1);
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($rawData as $rowNum => $row) {
                $rowNumForError = $hasHeader ? $rowNum + 2 : $rowNum + 1;

                // Пропускаємо повністю порожні рядки
                if (count(array_filter($row, fn($v) => !empty(trim($v ?? '')))) === 0) {
                    $skipped++;
                    continue;
                }

                // Обробляємо дані рядка
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
                    'description'    => null,
                ];

                foreach ($mapping as $colIdx => $field) {
                    if (!$field || !isset($row[$colIdx])) {
                        continue;
                    }

                    $value = trim($row[$colIdx] ?? '');

                    if ($field === 'barcode') {
                        if (!empty($value)) {
                            $barcodes[] = $value;
                        }
                    } elseif (isset($productData[$field])) {
                        $productData[$field] = $value ?: null;
                    }
                }

                // Валідація обов'язкових полів
                if (!$productData['name']) {
                    $errors[] = "Рядок {$rowNumForError}: не вказано назву товару";
                    continue;
                }

                if (empty($barcodes)) {
                    $errors[] = "Рядок {$rowNumForError}: не вказано жодного штрихкоду";
                    continue;
                }

                // Перетворення числових полів
                if ($productData['purchase_price'] !== null) {
                    $productData['purchase_price'] = $this->parseNumber($productData['purchase_price']);
                }

                if ($productData['sale_price'] !== null) {
                    $productData['sale_price'] = $this->parseNumber($productData['sale_price']);
                }

                if ($productData['quantity'] !== null) {
                    $productData['quantity'] = $this->parseNumber($productData['quantity']);
                }

                // Пошук існуючого товару
                $product = null;
                foreach ($barcodes as $barcode) {
                    $barcodeRow = Barcode::where('barcode', $barcode)->first();
                    if ($barcodeRow) {
                        $product = $barcodeRow->product;
                        break;
                    }
                }

                if ($product) {
                    // Оновлення існуючого товару
                    if ($updateExisting) {
                        $product->fill(array_filter($productData, fn($v) => $v !== null));
                        $product->save();
                        $updated++;
                    } else {
                        $skipped++;
                        continue;
                    }
                } else {
                    // Створення нового товару
                    $product = Product::create(array_merge(
                        [
                            'sku' => 'SKU_' . Str::upper(Str::random(8)),
                        ],
                        array_filter($productData, fn($v) => $v !== null)
                    ));
                    $imported++;
                }

                // Додаємо штрихкоди
                foreach ($barcodes as $barcode) {
                    Barcode::firstOrCreate([
                        'product_id' => $product->id,
                        'barcode'    => $barcode,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Помилка при імпорті: ' . $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTrace() : null,
            ], 500);
        }

        return response()->json([
            'imported' => $imported,
            'updated' => $updated,
            'skipped' => $skipped,
            'errors' => $errors,
            'has_errors' => !empty($errors),
        ]);
    }

    /**
     * Перевіряє, чи рядок виглядає як заголовок
     */
    private function rowLooksLikeHeader(array $row): bool
    {
        return collect($row)
                ->filter(fn($val) => is_string($val) && !is_numeric(trim($val)))
                ->count() > count($row) / 2;
    }

    /**
     * Парсить число з різних форматів
     */
    private function parseNumber($value)
    {
        if (is_numeric($value)) {
            return $value;
        }

        // Спроба обробити роздільники тисяч
        $value = str_replace([' ', ','], '', trim($value));

        return is_numeric($value) ? $value : null;
    }
}
