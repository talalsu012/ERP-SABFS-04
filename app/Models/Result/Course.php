<?php

namespace App\Models\Result;

use App\Models\Reg\Batch;
// use App\Models\Result\Course;
use App\Models\Result\Subject;
use Spatie\Activitylog\LogOptions;
use App\Models\Result\CourseSubject;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use LogsActivity;

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $fillable = [
        'code', 'name',  'start', 'end',
        'parent_code', 'is_visible', 'status',
        'created_at', 'updated_at'
    ];
    // protected $with = ['subjects'];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }
    public function children(): HasMany
    {
        return $this->hasMany(Course::class, 'parent_code');
    }


    public function parent(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'parent_code');
    }
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'course_code');
    }

    //     public function batches(): HasMany
    // {
    //     return $this->hasMany(Batch::class, 'course_code');
    // }
    public function courseSubjects()
    {
        return $this->hasMany(CourseSubject::class, 'course_code', 'code');
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class,  'course_code', 'code');
    }

    public function students()
    {
        return $this->batch->students();
    }


    public function subjects(): BelongsToMany
    {
        return $this->BelongsToMany(Subject::class, 'course_subject', 'course_code', 'subject_code')
         ->withPivot('batch_code') // This is crucial
        ->withTimestamps();
    }

//     public function subjects()
// {
//     return $this->belongsToMany(Subject::class)
//         ->withPivot('batch_code'); // This is crucial
// }
}
