<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'name', 'email', 'phone', 'address',
        'city', 'subtotal', 'shipping', 'total', 'payment_method', 'status', 'notes',
        'payment_intent_id', 'card_brand', 'card_last4', 'is_paid',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
