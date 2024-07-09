<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasValidTime
{
    public function scopeValidTime(Builder $query): Builder
    {
        return $query->where('start_time', '<=', now())->where('end_time', '>=', now());
    }

    public function hasValidTime(): bool
    {
        return now()->isAfter($this->start_time) && now()->isBefore($this->end_time);
    }
}
