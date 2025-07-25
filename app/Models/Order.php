<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'status', 'user_id'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
