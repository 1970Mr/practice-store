<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image'
    ];

    public function cartItems()
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
}
