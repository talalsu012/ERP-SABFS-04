<?php

namespace App\Models\Result;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResLtRecoms extends Model
{
    use HasFactory;

    // Explicitly define the table name
    protected $table = 'res_ltrecoms';

    // Define the primary key, as it's 'code' and not 'id'
    protected $primaryKey = 'code';

    // Since 'code' is unsignedBigInteger and explicitly primary key,
    // it's likely not auto-incrementing. If it is auto-incrementing, remove this line.
    public $incrementing = false;

    // Define the data type of the primary key
    protected $keyType = 'string'; // Or 'int' if code is purely numeric, but 'string' is safer if it could contain non-numeric. Let's assume numeric based on unsignedBigInteger.
    // Define which attributes are mass assignable
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'code' => 'int', // Cast the primary key to int
            'is_visible' => 'boolean',
        ];
    }
}
