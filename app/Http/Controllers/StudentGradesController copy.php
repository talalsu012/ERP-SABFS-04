<?php
namespace App\Http\Controllers;

use App\Models\Reg\Student;
use App\Models\Result\Course;
use App\Models\Result\Grade;

class a2StudentGradesController extends Controller
{

    public function printGrades($course_code)
    {
        // Load course with necessary relationships
        $course = Course::with(['parent', 'subjects'])
            ->where('code', $course_code)
            ->firstOrFail();
        // // Load course with necessary relationships
        // $grade = Grade::firstOrFail();

        // Get students with their marks, batch, and level
        $students = Student::whereHas('batch', function($query) use ($course_code) {
                $query->where('course_code', $course_code);
            })
            ->with([
                'marks' => function($query) use ($course_code) {
                    $query->where('course_code', $course_code);
                },
                'batch',
                'level'
            ])
            ->orderBy('name_ar')
            ->get();

            // \dd('34');
        $subjects = $course->subjects;
        $grades = Grade::orderByDesc('upper')->get();

        // Calculate subject statistics
        $subjectStats = [];
        foreach ($subjects as $subject) {
            $passed = 0;
            $failed = 0;
            $marks = [];

            foreach ($students as $student) {
                $mark = $student->marks->firstWhere('subject_code', $subject->code);
                if ($mark) {
                    $marks[] = $mark->mark;
                    if ($mark->mark >= 50) {
                        $passed++;
                    } else {
                        $failed++;
                    }
                }
            }

            $total = count($marks);
            $subjectStats[$subject->code] = [
                'passed' => $passed,
                'failed' => $failed,
                'total' => $total,
                'average' => $total > 0 ? round(array_sum($marks) / $total, 2) : 0,
                'max' => $total > 0 ? max($marks) : 0,
                'min' => $total > 0 ? min($marks) : 0,
            ];
        }

        //  \dd( $students);

        // Return the view with the data
        return view('filament.pages.print-grades', compact(
            'course',
            'students',
            'subjects',
            'grades',
            'subjectStats'
        ));
    }
}
// Compare this snippet from app/Models/Result/Grade.php:
