<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Barcode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProductImportController extends Controller
{
    /**
     * Повертає превʼю перших 5 рядків з Excel (назви колонок + дані)
     */
    public function preview(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xls,xlsx']);
        $data = Excel::toArray([], $request->file('file'))[0];

        if (count($data) === 0) {
            return response()->json(['message' => 'Файл порожній'], 422);
        }

        $columns = [];
        // Якщо в першому рядку текстові дані — це заголовки
        if (count($data) > 0 && $this->rowLooksLikeHeader($data[0])) {
            $columns = $data[0];
            $data = array_slice($data, 1); // пропускаємо заголовок
        } else {
            // Генеруємо назви типу "Колонка 1", "Колонка 2", ...
            $columns = array_map(fn($i) => 'Колонка ' . ($i + 1), array_keys($data[0]));
        }

        $previewRows = array_slice($data, 0, 5);

        return response()->json(
            [
            'columns' => $columns,
            'rows' => $previewRows,
            ]
        );
    }

    /**
     * Імпортує товари із Excel-файлу та мапінгу колонок
     */
    public function import(Request $request)
    {
        $request->validate(
            [
            'file' => 'required|file|mimes:xls,xlsx',
            'mapping' => 'required|json',
            ]
        );

        $mapping = json_decode($request->input('mapping'), true); // [0 => 'barcode', 1 => 'name', ...]
        $rawData = Excel::toArray([], $request->file('file'))[0];

        if (count($rawData) === 0) {
            return response()->json(['message' => 'Файл порожній'], 422);
        }

        // Визначаємо, чи є заголовки
        if ($this->rowLooksLikeHeader($rawData[0])) {
            $rawData = array_slice($rawData, 1);
        }

        $imported = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($rawData as $rowNum => $row) {
                // Збираємо всі штрихкоди для рядка (може бути декілька колонок з 'barcode')
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
                ];
                foreach ($mapping as $colIdx => $field) {
                    if (!$field) { continue;
                    }
                    $value = $row[$colIdx] ?? null;
                    if ($field === 'barcode' && $value) {
                        $barcodes[] = trim($value);
                    } elseif (isset($productData[$field])) {
                        $productData[$field] = $value;
                    }
                }

                // Валідація: назва має бути обовʼязкова
                if (!$productData['name']) {
                    $errors[] = "Рядок ".($rowNum+2).": не вказано назву товару";
                    continue;
                }
                if (empty($barcodes)) {
                    $errors[] = "Рядок ".($rowNum+2).": не вказано жодного штрихкоду";
                    continue;
                }

                // Шукаємо товар по першому знайденому штрихкоду
                $product = null;
                foreach ($barcodes as $barcode) {
                    $barcodeRow = Barcode::where('barcode', $barcode)->first();
                    if ($barcodeRow) {
                        $product = $barcodeRow->product;
                        break;
                    }
                }
                // Якщо не знайдено — створюємо новий товар
                if (!$product) {
                    $product = Product::create(
                        [
                        'sku'  => 'SKU_' . Str::upper(Str::random(8)),
                        'name' => $productData['name'],
                        'supplier_code'  => $productData['supplier_code'],
                        'country'        => $productData['country'],
                        'manufacturer'   => $productData['manufacturer'],
                        'brand'          => $productData['brand'],
                        'purchase_price' => $productData['purchase_price'],
                        'sale_price'     => $productData['sale_price'],
                        'quantity'       => $productData['quantity'],
                        'multiplicity'   => $productData['multiplicity'],
                        ]
                    );
                } else {
                    // Якщо знайдено — оновлюємо тільки ті поля, які прийшли
                    $product->fill(array_filter($productData, fn($v) => !is_null($v)));
                    $product->save();
                }

                // Додаємо ВСІ штрихкоди для товару (тільки нові)
                foreach ($barcodes as $barcode) {
                    Barcode::firstOrCreate(
                        [
                        'product_id' => $product->id,
                        'barcode'    => $barcode,
                        ]
                    );
                }

                $imported++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Помилка при імпорті: '.$e->getMessage()], 500);
        }

        return response()->json(
            [
            'count' => $imported,
            'errors' => $errors,
            ]
        );
    }
    // formatting update
    private function rowLooksLikeHeader($row)
    {
        // якщо рядок складається з нечислових даних — це заголовки
        return collect($row)->filter(fn($val) => !is_numeric($val))->count() > count($row)/2;
    }
}
