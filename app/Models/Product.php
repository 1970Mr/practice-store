<?php

namespace App\Models;

use App\Traits\HasDiscount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory, HasDiscount;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'stock',
    ];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }

    public function hasStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }

    public function commonDiscount(): ?CommonDiscount
    {
        return CommonDiscount::validTime()->latest('id')->first();
    }

    public function amazingSale(): HasOne
    {
        return $this->hasOne(AmazingSale::class);
    }
}
