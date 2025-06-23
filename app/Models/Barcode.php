<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barcode extends Model
{
    protected $fillable = [
        'product_id',   // ← ДОДАЙ ЦЕ
        'barcode',
        // ...інші поля, якщо є...
    ];
}
