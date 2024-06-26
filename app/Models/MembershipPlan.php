<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
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
