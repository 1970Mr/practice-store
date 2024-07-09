<?php

namespace App\Models;

use App\Traits\HasValidTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommonDiscount extends Model
{
    use HasFactory, HasValidTime;

    protected $fillable = [
        'title',
        'percent',
        'minimum_amount',
        'discount_ceiling',
        'start_date',
        'end_date'
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }


}
