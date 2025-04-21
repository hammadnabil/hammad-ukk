<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'cashier_id',
        'transaction_code',
        'total_price',
        'cash',
        'change',
        'paid_at',

    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($transaction) {
            $transaction->transaction_code = 'TXN-' . strtoupper(Str::random(8));
        });
    }

    protected $casts = [
        'paid_at' => 'datetime',  
    ];
}
