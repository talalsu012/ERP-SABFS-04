<?php

namespace App\Models\Reg;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Special extends Model
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
    public function department() : BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function students() : HasMany
    {
        return $this->hasMany(Student::class);
    }
}
