<?php

namespace App\Models\Result;

use Illuminate\Database\Eloquent\Model;

class ResResons extends Model
{
    protected $table = 'res_resons';

    // 2. Primary Key:
    // Laravel assumes 'id' as the primary key. If your primary key is different (e.g., 'reson_id'), specify it:
     protected $primaryKey = 'code';

  // If you want all attributes to be mass-assignable (be careful with this in production for security):
    public $incrementing = false;

    // Define the data type of the primary key
    // For unsignedBigInteger, 'int' should be sufficient.
    protected $keyType = 'int';

     protected $fillable = [ 'code', 'name', 'name_en', 'slug', 'description', 'is_visible'];
     protected function casts(): array
     {
         return [
             'code' => 'int',       // Cast the primary key to int
             'is_visible' => 'boolean',
         ];
     }

    // 3. Timestamps:
    //public $timestamps = false; // Set to true if your table has 'created_at' and 'updated_at' columns
}

