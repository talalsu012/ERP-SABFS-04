<?php

namespace App\Models\Result;

use App\Models\Reg\Batch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseSubject extends Pivot //Model
{
    protected $table = "course_subject";

     protected $fillable = [
        'course_code', 'subject_code', 'batch_code',
        'created_at', 'updated_at'
     ];
    protected function casts(): array
    {
        return [

        ];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_code', 'code');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_code', 'code');
    }
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_code', 'code');
    }
}
