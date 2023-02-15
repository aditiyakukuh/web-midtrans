<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id'
    ];

     /**
     * Get the user that owns the Cart
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
        
    }

    /**
     * Get all of the comments for the Cart
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function item(): HasMany
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'id');
    }

    public function getTotalAmount()
    {
        $total = 0;
        foreach ($this->item as $value) {
           $total += ($value['price'] * $value['qty']);
        }
        return $total;
    }
}
