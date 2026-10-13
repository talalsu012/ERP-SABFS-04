<?php

namespace App\Models\Result;

use App\Models\Reg\Level;
use App\Models\Reg\Faculty;
use App\Models\Reg\Student;
use App\Models\Reg\Lt_column;
use App\Models\Reg\Department;
use App\Models\attendances\Staff;
use Spatie\Activitylog\LogOptions;
use App\Models\Result\CourseSubject;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;


class Subject extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    #[Scope]
    protected function Exam(Builder $query): void
    {
        $query->where('type', 9002001);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }
    public function plans(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'plan');
    }
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_code');
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_code');
    }
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_code');
    }
    public function types(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'type');
    }


    public function children(): HasMany
    {
        return $this->hasMany(Course::class, 'parent_code');
    }


    public function parent(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'parent_code');
    }

    public function courseSubjects()
    {
        return $this->hasMany(CourseSubject::class, 'subject_code', 'code');
    }
    // public function Courses(): BelongsToMany
    // {
    //     return $this->BelongsToMany(Course::class, 'course_subject', 'subject_code', 'course_code')
    //          ->withPivot('batch_code')
    //         ->withTimestamps();
    // }

    public function courses()
    {
        return $this->belongsToMany(Course::class)
            ->withPivot('batch_code');
    }

    //studets_marks
    public function Students(): BelongsToMany
    {
        return  $this->BelongsToMany(Student::class, 'marks', 'subject_code', 'university_no')->withTimestamps();
    }

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'att_staff_subject', 'subject_code', 'staff_id');
    }
}
