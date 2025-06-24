<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'category_id',
        'slug',
        'sku',
        'supplier_code',
        'country',
        'manufacturer',
        'brand',
        'purchase_price',
        'sale_price',
        'quantity',
        'multiplicity',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
