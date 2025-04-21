<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['order_code', 'items', 'status'];

    protected $casts = [
        'items' => 'array', 
    ];

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function items()
{
    return $this->hasMany(Order::class);
}

public function setItems($items)
{
    $this->items = $items;
}
}
