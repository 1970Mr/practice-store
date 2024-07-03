<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'amount',
        'status',
        'user_id',
    ];

    public const TRANSPORTATION_COSTS = 10000;

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
