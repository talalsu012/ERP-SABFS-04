<?php

namespace App\Models\Reg;

use App\Models\Reg\Student;
use App\Models\Result\Course;
use App\Models\Result\Subject;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Batch extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'code';



    public $incrementing = false;

    protected $guarded = [];



    protected function casts(): array
    {
        return [

        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
         ->logAll();
    }


    /**
     * Get the course that the batch belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @property-read \App\Models\Reg\Course $course The course that the batch belongs to.
     */
    public function courseOLD() : BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

//     public function students(): HasMany
// {
//     // Use hasMany instead of belongsToMany
//     return $this->hasMany(Student::class, 'batch_code', 'code');
//     // 'batch_code' is the foreign key in students table
//     // 'code' is the primary key in batches table
// }

public function course(): BelongsTo
{
    return $this->belongsTo(Course::class, 'course_code', 'code');
}

public function students() : HasMany
{
    return $this->hasMany(Student::class, 'batch_code', 'code');
}


    public function studentsOLD() : BelongsToMany
    {
        return $this->BelongsToMany(Student::class);
    }

    public function students_count() : HasMany
    {
        return $this->HasMany(Student::class,'batch_code');
    }


    public function subjects() : BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }

}
