<?php

namespace App\Models\Reg;

use App\Models\Reg\Faculty;
use App\Models\Reg\Student;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'code';
    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean'
        ];
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
         ->logAll();
    }
    public function faculty() : BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function students() : HasMany
    {
        return $this->hasMany(Student::class);
    }

}
