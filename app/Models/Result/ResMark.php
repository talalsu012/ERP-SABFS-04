<?php

namespace App\Models\Result;

use App\Models\Reg\Batch;      // Assuming Batch model is in App\Models\Reg
use App\Models\Reg\Faculty;    // Assuming Faculty model is in App\Models\Reg
use App\Models\Reg\Level;      // Assuming Level model is in App\Models\Reg
use App\Models\Reg\Student;    // Assuming Student model is in App\Models\Reg
use App\Models\Result\ResResons; // Assuming ResReson model is in App\Models\Result


use App\Models\Result\Subject; // Assuming Subject model is in App\Models\Result or App\Models\Reg
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ResMark extends Model //implements Auditable
{
    use HasFactory, SoftDeletes;
    use LogsActivity;
   // use \OwenIt\Auditing\Auditable;


    // Explicitly define the table name if it doesn't follow Laravel's pluralization convention
    protected $table = 'res_marks';
    //university_no, subject_code, faculty_code, level_code, batch_code, semester, examType


    // Since 'id' is the primary key and it's auto-incrementing,
    // you don't need to specify protected $primaryKey or public $incrementing.

    // Define which attributes are mass assignable
    // For simplicity, we'll guard nothing, allowing all fields to be mass-assigned.
    // In production, consider using `$fillable` instead to list allowed fields.
    protected $guarded = [];

    // Define attribute casting for specific column types
    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'is_locked' => 'boolean',
            'is_released' => 'boolean',
            'is_recheck' => 'boolean',
            'is_recheck_passed' => 'boolean',
            'point' => 'float', // Cast point to float
        ];
    }

    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }
    /*
    |--------------------------------------------------------------------------
    | Calculation MARK
    |--------------------------------------------------------------------------
    */
    public static function InsertMark($university_no, $subject_code, $mark, $examType, $batch_code)
    {

        // Check if the student has a valid course and batch
        $grade = Grade::getGradeTitle($mark);
        $subject = self::getSubject($subject_code);
        try {
            if ($subject_code && is_numeric($mark)) {
               $resMarkSuccess = ResMark::updateOrCreate(
                    [
                        'university_no' => $university_no,
                        'subject_code' => $subject_code,
                        'faculty_code' => $subject['faculty_code'],
                        'level_code' => $subject['level_code'],
                        'semester' => $subject['semester_id'],
                        'batch_code' => $batch_code,
                        'examType' => $examType,
                    ],
                    [
                        'mark' => (float) $mark,
                        //  'point' => $grade->point,
                        'grade' => $grade->titlear,
                        'reson_code' => null
                    ]
                );
                if ($resMarkSuccess) {
                    self::updateRecommendationStats(
                        $university_no,
                        $subject['faculty_code'],
                        $subject['level_code'],
                        $batch_code,
                        $subject['semester_id'],
                        $examType,
                        (float) $mark
                    );
                }
            }

            Notification::make()
                ->title('Success!')
                ->body('Data saved successfully.')
                ->success() // Sets the notification type to "success"
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error!')
                ->body('Failed to save data: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public static function updateRecommendationStats(
        $university_no,
        $faculty_code,
        $level_code,
        $batch_code,
        $semester,
        $examType,
        $mark
    ) {
        // الحصول على السجل الحالي من جدول التوصيات
        $recommendation = ResRecoms::firstOrNew([
            'university_no' => $university_no,
            'faculty_code' => $faculty_code,
            'level_code' => $level_code,
            'batch_code' => $batch_code,
            'semester' => $semester,
            'examType' => $examType,
        ]);

        // حساب القيم الجديدة
        $currentCount = $recommendation->countSubject ?? 0;
        $currentSum = $recommendation->sumMarks ?? 0;

        // تحديث القيم
        $recommendation->countSubject = $currentCount + 1;
        $recommendation->sumMarks = $currentSum + $mark;


        // حفظ التغييرات
        $recommendation->save();

        return $recommendation;
    }
    public static function InsertReson($university_no, $subject_code, $reson, $examType, $batch_code)
    {

        // Check if the student has a valid course and batch
        $subject = self::getSubject($subject_code);
        try {
            // Check if required parameters are valid
            if (empty($subject_code) || empty($reson) || !is_numeric($reson)) {
                throw new \InvalidArgumentException('Invalid subject  or reason ');
            }

            // Get subject details
            $subject = self::getSubject($subject_code);
            if (!$subject) {
                throw new \RuntimeException('Subject not found');
            }

            // Prepare update data
            $updateData = ['reson_code' => $reson];

            // If reason code is greater than 2000, get the reason description
            if ($reson > 2000) {
                $resonDesc = ResResons::where('code', $reson)->first();
                if ($resonDesc) {
                    $updateData  =
                        [
                            'mark' => '0',
                            'grade' => $resonDesc->description,
                            'reson_code' => $reson
                        ];
                }
            }

            if ($subject_code && is_numeric($reson)) {
                ResMark::updateOrCreate(
                    [
                        'university_no' => $university_no,
                        'subject_code' => $subject_code,
                        'faculty_code' => $subject['faculty_code'],
                        'level_code' => $subject['level_code'],
                        'semester' => $subject['semester_id'],
                        'batch_code' => $batch_code,
                        'examType' => $examType,
                    ],
                    $updateData,
                );
            }

            Notification::make()
                ->title('Success!')
                ->body('Data saved successfully.')
                ->success() // Sets the notification type to "success"
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error!')
                ->body('Failed to save data: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Get the current Subject.
     *
     * @return array
     */
    protected static function getSubject($subject_code): array
    {
        // code, pin, name_ar, crch, plan, `status`, `type`, is_visible, faculty_code, department_code, level_code, semester_id
        // This method returns the current subject's code and name.
        $subject = Subject::where('code', $subject_code)->first();

        // Assuming you have a method to get the current subject
        return [
            'code' => $subject->code ?? null,
            'name_ar' => $subject->name_ar ?? null,
            'crch' => $subject->crch ?? null,
            'plan' => $subject->plan ?? null,
            'status' => $subject->status ?? null,
            'type' => $subject->type ?? null,
            'is_visible' => $subject->is_visible ?? false,
            'faculty_code' => $subject->faculty_code ?? null,
            'department_code' => $subject->department_code ?? null,
            'level_code' => $subject->level_code ?? null,
            'semester_id' => $subject->semester_id ?? null
        ];
        // return date('n') <= 6 ? '1st Semester' : '2nd Semester';
    }
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the student that owns the mark.
     */
    public function student(): BelongsTo
    {
        // 'university_no' is the foreign key on 'res_marks' table
        // 'university_no' is the local key on 'students' table
        return $this->belongsTo(Student::class, 'university_no', 'university_no');
    }

    /**
     * Get the subject that owns the mark.
     */
    public function subject(): BelongsTo
    {
        // 'subject_code' is the foreign key on 'res_marks' table
        // 'code' is the local key on 'subjects' table
        return $this->belongsTo(Subject::class, 'subject_code', 'code');
    }

    /**
     * Get the faculty that owns the mark.
     */
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

    /**
     * Get the reason (res_reson) for the mark, if any.
     */
    public function reason(): BelongsTo
    {
        // 'reson_code' is the foreign key on 'res_marks' table
        // 'code' is the local key on 'res_resons' table
        return $this->belongsTo(ResResons::class, 'reson_code', 'code');
    }
}
