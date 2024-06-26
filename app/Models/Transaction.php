<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    protected $fillable = [
        'payment_id',
        'transaction_id',
        'amount',
        'product_type',
        'product_id',
        'invoice_details',
        'transaction_result',
        'reference_id',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product(): MorphTo
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }

    protected function invoiceDetails(): Attribute
    {
        return Attribute::make(
            get: static fn(mixed $value) => unserialize($value, ['allowed_classes' => true]),
            set: static fn(mixed $value) => serialize($value),
        );
    }

    protected function transactionResult(): Attribute
    {
        return Attribute::make(
            get: static fn(mixed $value) => unserialize($value, ['allowed_classes' => true]),
            set: static fn(mixed $value) => serialize($value),
        );
    }
}
