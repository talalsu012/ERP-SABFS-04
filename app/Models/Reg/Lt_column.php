<?php

namespace App\Models\Reg;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lt_column extends Model
{
    use HasFactory;
    use LogsActivity;

   protected $table = 'ltcolumns';
   protected $primaryKey = 'code';

   public $incrementing = false;

   protected $guarded = [];

   protected function casts(): array
       {
           return [
               'is_visible' => 'boolean',
           ];
       }

   public static function Visible(Builder $query): void
       {
           $query->where('is_visible', true);
       }


   public function getActivitylogOptions(): LogOptions
   {
       return LogOptions::defaults()
        ->logAll();
   }
}
