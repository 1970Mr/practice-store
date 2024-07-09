<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'amount',
        'amount_type',
        'minimum_amount',
        'discount_ceiling',
        'usage_limit',
        'used_count',
        'start_time',
        'end_time',
        'user_id'
    ];

    protected function casts(): array
    {
        return [
            'expire_time' => 'datetime'
        ];
    }

    public function couponable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isValid(): bool
    {
        return now()->isAfter($this->start_time) && now()->isBefore($this->end_time);
    }

    public function scopeValid($query)
    {
        return $query->where('start_time', '<=', now())->where('end_time', '>=', now());
    }

    public function exceededUsageLimit(): bool
    {
        $hasUsageLimit = $this->usage_limit !== null;
        $exceeded = $this->used_count >= $this->usage_limit;
        return $hasUsageLimit && $exceeded;
    }
}
