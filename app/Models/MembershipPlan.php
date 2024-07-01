<?php

namespace App\Models;

use App\Services\Transaction\Contracts\ProductInterface;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model implements ProductInterface
{
    protected $fillable = [
        'name',
        'duration',
        'price'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
