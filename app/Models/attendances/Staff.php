<?php

namespace App\Models\attendances;

use App\Models\Result\Subject;
use App\Models\Reg\Lt_column;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Staff extends Model
{
    protected $table = 'att_staff';

    protected $fillable  =[
        'name', 'degree', 'date_degree','crch_load',
         'specialization', 'appointment', 'gender_code'
    ];
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'att_staff_subject')
            ->withPivot('batch_code');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'staff_id');
    }

    public function attendanceStaff()
    {
        return $this->hasMany(AttendanceStaff::class, 'staff_id');
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'gender_code');
    }


}


