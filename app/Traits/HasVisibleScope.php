<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasVisibleScope
{
    public function scopeVisible(Builder $query): void
    {
        $query->where('is_visible', true);
    }
}
