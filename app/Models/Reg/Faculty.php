<?php

namespace App\Models\Reg;

use App\Models\Result\Subject;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faculty extends Model
{
    use HasFactory;
    use LogsActivity;

   protected $primaryKey = 'code';
   public $incrementing = false;

   protected $guarded = [];

   protected function casts(): array
   {
       return [];
   }

   public function getActivitylogOptions(): LogOptions
   {
       return LogOptions::defaults()
        ->logAll();
   }
   public function campus() : BelongsTo
   {
       return $this->belongsTo(Campus::class);
   }

   public function students() : HasMany
   {
       return $this->hasMany(Student::class);
   }

   public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'faculty_code', 'code');
    }
}
