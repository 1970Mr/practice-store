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
        'limit',
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
}
