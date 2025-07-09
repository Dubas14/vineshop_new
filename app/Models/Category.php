<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'image']; // або інші поля, якщо є

    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'category_id');
    }
}
