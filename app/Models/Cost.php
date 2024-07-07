<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cost extends Model
{
    protected $table = 'cost_summary';

    protected $fillable = [
        "total_cost",
        "summary",
        "order_id",
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function summary(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => unserialize($value, ['allowed_classes' => false]),
            set: static fn ($value) => serialize($value)
        );
    }
}
