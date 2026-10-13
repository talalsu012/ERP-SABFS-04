<?php

namespace App\Http\Controllers;

use App\Models\Reg\Faculty as Course; // Using our conceptual Course model
use App\Models\Reg\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResultCrosstabController extends Controller
{
    /**
     * Display a crosstabulated report of student grades.
     *
     * @param string $course_code The faculty/department code representing the course.
     * @param string $level_code The level of study.
     * @param string $semester The semester to display results for (e.g., '1', '2').
     * @param string $examType The type of exam (e.g., '1' for Basic).
     * @return View
     */
    public function show(string $course_code, string $level_code, string $semester, string $examType): View
    {
        // 1. Fetch the primary "course" (e.g., faculty) and its associated subjects
        // for the specific level and semester. We key subjects by their code for efficiency.
        $course = Course::findOrFail($course_code);
        $subjects = $course->subjects()
                           ->where('level_code', $level_code)
                           ->where('semester_id', $semester)
                           ->get()
                           ->keyBy('code');

        // 2. Fetch all students for the course and level, eager-loading relations
        // to prevent thousands of database queries (N+1 problem).
        $students = Student::where('faculty_code', $course_code)
            ->where('level_code', $level_code)
            ->with([
                // Load only the marks for the subjects we are interested in.
                'marks' => function ($query) use ($subjects) {
                    $query->whereIn('subject_code', $subjects->keys());
                },
                // Load the specific recommendation record matching the full context.
                'recom' => function ($query) use ($course_code, $level_code, $semester, $examType) {
                    $query->where('faculty_code', $course_code)
                          ->where('level_code', $level_code)
                          ->where('semester', $semester)
                          ->where('examType', $examType);
                },
                'batch',
            ])
            ->orderBy('university_no')
            ->get();

        // 3. Transform marks into a "pivoted" structure for easy access in Blade.
        // This is a crucial step for a clean view file.
        $studentsWithPivotedMarks = $students->map(function ($student) use ($subjects) {
            $student->pivoted_marks = $student->marks->keyBy('subject_code');
            return $student;
        });

        // 4. Pre-calculate statistics for each subject's footer row.
        $subjectStats = $subjects->mapWithKeys(function ($subject) use ($studentsWithPivotedMarks) {
            $marksForSubject = $studentsWithPivotedMarks->pluck('pivoted_marks.' . $subject->code . '.mark')->filter();
            $total = $marksForSubject->count();
            $passed = $marksForSubject->filter(fn ($mark) => $mark >= 50)->count();

            return [
                $subject->code => [
                    'passed' => $passed,
                    'failed' => $total - $passed,
                    'total' => $total,
                    'average' => $total > 0 ? round($marksForSubject->avg(), 2) : 0,
                ]
            ];
        });

        // 5. Pass the clean, prepared data collections to the view.
        return view('results.crosstab', [
            'course' => $course,
            'levelCode' => $level_code,
            'students' => $studentsWithPivotedMarks,
            'subjects' => $subjects,
            'subjectStats' => $subjectStats,
        ]);
    }
}
