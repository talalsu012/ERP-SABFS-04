<?php

namespace App\Services;


class ResultService
{
    /**
     * Get the current academic year.
     *
     * @return string
     */
    public function getCurrentAcademicYear(): string
    {
        return date('Y') . '-' . (date('Y') + 1);
    }

    /**
     * Get the current semester.
     *
     * @return string
     */
    public function getCurrentSemester(): string
    {
        return date('n') <= 6 ? '1st Semester' : '2nd Semester';
    }

    // public static function InsertMark($university_no, $subjectCode, $mark)
    // {
    //           $student = Student::where('university_no', $university_no)
    //                 ->join('batches', 'students.batch_code', '=', 'batches.code')
    //                 ->join('courses', 'batches.course_code', '=', 'courses.code')
    //                 ->select('students.level_code','courses.status', 'batches.batch_start', 'courses.code as course_code','batches.code as batch_code')
    //                 ->first();

    //             Mark::where('university_no', $university_no)
    //             ->Where('subject_code', '>=', $subjectCode)
    //             ->Where('status', '>=', $student->status)
    //             ->delete();

    //             $grade = Grade::getGradeTitle($mark);



    //             try {
    //                 Mark::create([
    //                     'university_no' => $university_no ,
    //                     'subject_code' => $subjectCode,

    //                     //course_code,batch_code,level_code,`status`
    //                     'status' => $student->status,
    //                     'course_code' => $student->course_code,
    //                     'batch_code' => $student->batch_code,
    //                     'level_code' => $student->level_code,

    //                     'mark' => $mark,
    //                     'point' => $grade->point,
    //                     'grade' => $grade->title,
    //                 ]);

    //                 Notification::make()
    //                     ->title('Success!')
    //                     ->body('Data saved successfully.')
    //                     ->success() // Sets the notification type to "success"
    //                     ->send();
    //             } catch (\Exception $e) {
    //                 Notification::make()
    //                     ->title('Error!')
    //                     ->body('Failed to save data: ' . $e->getMessage())
    //                     ->danger()
    //                     ->send();
    //             }

    // }
}
