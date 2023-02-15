<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_code',
        'payment_type',
        'status',
        'transaction_id',
        'invoice_url',
        'total_amount'
    ];
}
