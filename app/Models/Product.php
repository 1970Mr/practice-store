<?php

namespace App\Models;

use App\Services\Transaction\Contracts\ProductInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model implements ProductInterface
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'stock',
    ];

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function purchases(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'product');
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'product');
    }

    public function orders(): MorphToMany
    {
        return $this->morphToMany(Order::class, 'salable');
    }

    public function hasStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }
}
