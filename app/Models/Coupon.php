<?php

namespace App\Models;

use App\Enums\CouponType;
use App\Traits\HasValidTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory, HasValidTime;

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
            'amount_type' => CouponType::class,
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public function exceededUsageLimit(): bool
    {
        $hasUsageLimit = $this->usage_limit !== null;
        $exceeded = $this->used_count >= $this->usage_limit;
        return $hasUsageLimit && $exceeded;
    }

    public function scopeNotExceededUsageLimit(Builder $query): Builder
    {
        return $query->whereNull('usage_limit')->OrWhereColumn('used_count', '<', 'usage_limit');
    }

    public function scopeGreaterThanMinimumAmount(Builder $query, int $amount): Builder
    {
        return $query->where('minimum_amount', '<=', $amount);
    }

    public function isGreaterThanMinimumAmount(int $amount): bool
    {
        return $this->minimum_amount <= $amount;
    }
}
