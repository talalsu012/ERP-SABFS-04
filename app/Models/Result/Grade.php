<?php

namespace App\Models\Result;

use App\Models\Reg\Lt_column;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
        ];
    }

    public function stady(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'plan');
    }


}

