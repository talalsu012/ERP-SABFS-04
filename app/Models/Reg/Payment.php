<?php

namespace App\Models\Reg;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use LogsActivity;
    use SoftDeletes;


    protected $guarded = [];

    protected function casts(): array
    {
        return [
        ];
    }

    public function student() :BelongsTo
     {
        return $this->belongsTo(Student::class, 'university_no');
    }
    public function batch() :BelongsTo
     {
        return $this->belongsTo(Batch::class, 'batch_code');
    }
    public function level() :BelongsTo
     {
        return $this->belongsTo(Level::class, 'level_code');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
         ->logAll();
    }
}
