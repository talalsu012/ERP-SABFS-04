<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasApprovedScope
{
    public function scopeApproved(Builder $query): void
    {
        $query->where('is_approved', true);
    }
}
