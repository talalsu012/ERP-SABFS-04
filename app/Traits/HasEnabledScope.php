<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasEnabledScope
{
    public function scopeEnabled(Builder $query): void
    {
        $query->where('is_enabled', true);
    }
}
