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
        'percent',
        'amount_limit',
        'usage_limit',
        'used_count',
        'expire_time',
        'couponable_id',
        'couponable_type',
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

    public function isExpired(): bool
    {
        return now()->isAfter($this->expire_time);
    }

    public function exceededUsageLimit(): bool
    {
        $hasUsageLimit = $this->usage_limit !== null;
        $exceeded = $this->used_count >= $this->usage_limit;
        return $hasUsageLimit && $exceeded;
    }
}
