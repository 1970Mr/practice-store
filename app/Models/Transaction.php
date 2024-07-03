<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'internal_code',
        'transaction_id',
        'amount',
        'payment_method',
        'gateway',
        'callback_payload',
        'reference_id',
        'status',
        'order_id',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function callbackPayload(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => unserialize($value, ['allowed_classes' => true]),
            set: static fn ($value) => serialize($value)
        );
    }
}
