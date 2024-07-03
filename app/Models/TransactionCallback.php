<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionCallback extends Model
{
    protected $fillable = [
        'transaction_id',
        'callback_payload'
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function callbackPayload(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => unserialize($value, ['allowed_classes' => true]),
            set: static fn ($value) => serialize($value)
        );
    }
}
