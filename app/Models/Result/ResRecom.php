<?php

namespace App\Models\Result;

use App\Enums\AbsStatus;
use App\Enums\Semester;
use App\Models\Reg\Batch;
use App\Models\Reg\Level;
use App\Models\Reg\Faculty;
use App\Models\Reg\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResRecom extends Model
{
    use SoftDeletes;


    // Explicitly define the table name if it doesn't follow Laravel's pluralization convention
    protected $table = 'res_recoms';

    protected $guarded = [];

    // protected $casts = [
    //     'semester' => Semester::class,
    //     'examType' => AbsStatus::class,
    // ];
    // Define attribute casting for specific column types
    // protected function casts(): array
    // {
    //     return [
    //         'is_visible' => 'boolean',
    //         'is_locked' => 'boolean',
    //         'is_released' => 'boolean',
    //         'point' => 'float', // Cast point to float
    //     ];
    // }

    protected function casts(): array
    {
        return [
            'semester' => Semester::class,
            'examType' => AbsStatus::class,
            'is_visible' => 'boolean',
            'is_locked' => 'boolean',
            'is_released' => 'boolean',
            'point' => 'float',
        ];
    }


    public function recomName(): BelongsTo
    {
        return $this->belongsTo(ResLtRecoms::class, 'recom_code', 'code');
    }

    public function student(): BelongsTo
    {
        // 'university_no' is the foreign key on 'res_marks' table
        // 'university_no' is the local key on 'students' table
        return $this->belongsTo(Student::class, 'university_no', 'university_no');
    }


    public function faculty(): BelongsTo
    {
        // 'faculty_code' is the foreign key on 'res_marks' table
        // 'code' is the local key on 'faculties' table
        return $this->belongsTo(Faculty::class, 'faculty_code', 'code');
    }

    /**
     * Get the level that owns the mark.
     */
    public function level(): BelongsTo
    {
        // 'level_code' is the foreign key on 'res_marks' table
        // 'code' is the local key on 'levels' table
        return $this->belongsTo(Level::class, 'level_code', 'code');
    }

    /**
     * Get the batch that owns the mark.
     */
    public function batch(): BelongsTo
    {
        // 'batch_code' is the foreign key on 'res_marks' table
        // 'code' is the local key on 'batches' table
        return $this->belongsTo(Batch::class, 'batch_code', 'code');
    }
}
