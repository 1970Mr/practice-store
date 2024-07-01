<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Order extends Model
{
    protected $fillable = [
        'amount',
        'user_id',
        'salable_id',
        'salable_type',
    ];

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'salable');
    }

    public function membershipPlans(): MorphToMany
    {
        return $this->morphedByMany(MembershipPlan::class, 'salable');
    }
}
