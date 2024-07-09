<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazingSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'percent',
        'usage_limit',
        'used_count',
        'start_time',
        'end_time',
        'product_id'
    ];
}
