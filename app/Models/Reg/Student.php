<?php

namespace App\Models\Reg;

use App\Models\Result\Mark;
use App\Models\Reg\Lt_column;
use App\Models\Result\ResMark;
use App\Models\Result\Subject;
use App\Traits\HasVisibleScope;
use App\Models\attendances\Room;
use App\Models\Reg\StudentAccount;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use App\Models\attendances\Lecture;
use Illuminate\Support\Facades\Log;
use App\Models\attendances\Grouping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Models\attendances\LectureStudent;
use App\Models\attendances\GroupingStudent;
use App\Models\Result\ResRecom;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;
    use LogsActivity;
    use HasVisibleScope;
    use SoftDeletes;

    protected $table = 'students';
    protected $primaryKey = 'university_no';
    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'parent_alive' => 'boolean',
            'mother_alive' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }

    // protected static function booted()
    // {
    //     static::updated(function ($student) {
    //         $fieldsToWatch = [
    //             'reg_fees', 'fees', 'fees1', 'fees2', 'fees3',
    //             'fees4', 'fees6', 'fees7', 'fees8',
    //             'installment_code', 'discount_percent'
    //         ];

    //         if ($student->wasChanged($fieldsToWatch)) {
    //             try {
    //                 $results = DB::select(
    //                     'CALL CalculateInstallmentsWithDiscount(?, @all_fees, @xmod, @pay, @fees10, @pay1, @pay2, @pay3, @pay4)',
    //                     [$student->university_no]
    //                 );

    //                 // Get output parameters
    //                 $output = DB::select('
    //                     SELECT @all_fees as all_fees, @xmod as xmod, @pay as pay,
    //                            @fees10 as fees10, @pay1 as pay1, @pay2 as pay2,
    //                            @pay3 as pay3, @pay4 as pay4
    //                 ')[0];

    //                 // Update the student model with results
    //                 $student->update([
    //                     'all_fees' => $output->all_fees,
    //                     'xmod' => $output->xmod,
    //                     'pay' => $output->pay,
    //                     'fees10' => $output->fees10,
    //                     'pay1' => $output->pay1,
    //                     'pay2' => $output->pay2,
    //                     'pay3' => $output->pay3,
    //                     'pay4' => $output->pay4
    //                 ]);

    //             } catch (\Exception $e) {
    //                 Log::error("Failed to calculate installments: " . $e->getMessage());
    //                 // Optionally notify admin or handle the error
    //             }
    //         }
    //     });
    // }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    public function StudentAccount(): HasOne
    {
        return $this->hasOne(StudentAccount::class, 'university_no');
    }


    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }


    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function special(): BelongsTo
    {
        return $this->belongsTo(Special::class);
    }
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function batchold(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_code');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_code', 'code');
    }


    public function Payment(): HasMany
    {
        return $this->hasMany(Payment::class, 'university_no');
    }


    public function gender(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'gender_code');
    }
    public function admission(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'admission_code');
    }
    public function stady(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'stady_code');
    }
    
      public function AccountStatus(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'student_account', 'code')
            ->where('grouped', 4002);
    }
    
    public function academicyear(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'academic_year_code');
    }
    public function rstatus(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'status_code');
    }
    public function nationalty(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'nationality_code');
    }
    public function state(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'state_code');
    }
    public function installment(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'installment_code');
    }
    public function discount_percent(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'discount_percent');
    }
    public function x_status(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'xstatus');
    }

    public function getImageUrlAttribute()
    {
        return $this->photo ? Storage::url($this->photo) : null;
    }

    //Associated
    public function subjects(): BelongsToMany
    {
        return  $this->BelongsToMany(Subject::class, 'marks', 'university_no', 'subject_code')->withTimestamps();
    }



    public function marks(): HasMany
    {
        return $this->hasMany(ResMark::class, 'university_no');
    }

    public function recom(): HasMany
    {
        return $this->HasMany(ResRecom::class, 'university_no', 'university_no');
    }
    public function getRecommendationNamesAttribute()
    {
        return $this->recom->map(function ($item) {
            return $item->recomName ? $item->recomName->name : null;
        })->filter()->implode(', ');
    }

    public function grouping()
    {
        return $this->belongsToMany(Grouping::class, 'att_grouping_student', 'university_no', 'grouping_id')
            ->using(GroupingStudent::class)
            ->withPivot(['batch_code', 'room_id'])
            ->withTimestamps();
    }
    public function groupings()
    {
        return $this->belongsToMany(Grouping::class, 'att_grouping_student', 'university_no', 'grouping_id')
            ->using(GroupingStudent::class)
            ->withPivot(['batch_code', 'room_id'])
            ->withTimestamps();
    }

    public function lectures()
    {
        return $this->belongsToMany(Lecture::class, 'att_lecture_student', 'university_no', 'lecture_id')
            ->withPivot('status')
            ->using(LectureStudent::class);
    }
}