<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommonDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'percent',
        'minimum_amount',
        'discount_ceiling',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date'
    ];
}
