<?php

namespace App\Models\Result;

use App\Models\Reg\Level;
use App\Models\Reg\Student;
use App\Models\Result\Grade;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mark extends Model
{

    protected $guarded = [];
    // public $timestamps = false;
    protected function casts(): array
    {
        return [
        ];
    }

    public function course() : BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_code');
    }

    public function Student(): HasMany
    {
        return $this->hasMany(Student::class,'university_no','university_no');
    }
    public function Subject(): HasMany
    {
        return $this->hasMany(Subject::class,'code','subject_code');
    }
    public function Level(): HasMany
    {
        return $this->hasMany(Level::class,'code','level_code');
    }

    public  function std()
    {
        $data  = DB::table('courses')
        ->join('batches', 'batches.course_code', '=', 'courses.code')
        ->join('students', 'students.batch_code', '=', 'batches.code')
        ->select('students.*', 'batches.batch_start', 'courses.name')
        ->get();

        return $data;
    }


    public static function InsertMark($university_no, $subjectCode, $mark)
    {
              $student = Student::where('university_no', $university_no)
                    ->join('batches', 'students.batch_code', '=', 'batches.code')
                    ->join('courses', 'batches.course_code', '=', 'courses.code')
                    ->select('students.level_code','courses.status', 'batches.batch_start', 'courses.code as course_code','batches.code as batch_code')
                    ->first();

                Mark::where('university_no', $university_no)
                ->Where('subject_code', '>=', $subjectCode)
                ->Where('status', '>=', $student->status)
                ->delete();

                $grade = Grade::getGradeTitle($mark);



                try {
                    Mark::create([
                        'university_no' => $university_no ,
                        'subject_code' => $subjectCode,

                        //course_code,batch_code,level_code,`status`
                        'status' => $student->status,
                        'course_code' => $student->course_code,
                        'batch_code' => $student->batch_code,
                        'level_code' => $student->level_code,

                        'mark' => $mark,
                        'point' => $grade->point,
                        'grade' => $grade->title,
                    ]);

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
}
