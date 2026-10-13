<?php

namespace App\Models\Result;

use Illuminate\Database\Eloquent\Model;

class Printed extends Model
{
    protected $table = 'prints';
    protected $guarded = [];
    // public $timestamps = false;
    protected function casts(): array
    {
        return [
        ];
    }
}
