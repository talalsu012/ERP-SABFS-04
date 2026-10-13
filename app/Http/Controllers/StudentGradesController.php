<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Reg\Student;
use App\Models\Result\Grade;
use Illuminate\Http\Request;
use App\Models\Result\Course;
use App\Models\Result\ResMark;
use App\Models\Result\ResRecoms;
use Illuminate\Support\Facades\DB;

class StudentGradesController extends Controller
{
    /**
     * Display a crosstabulated report of student grades for a specific course.
     *
     * @param string $course_code
     * @return View
     */
    public function printGrades($dataForm)
    {
        $data = json_decode(base64_decode($dataForm), true);

        if (!$data) {
            abort(400, 'Invalid data format');
        }

        // Access parameters like:
        $faculty = $data['faculty'];
        $level = $data['level'];
        $semester = $data['semester'];

        // Get students matching the criteria
            // Query students with their marks and recommendations
            $query = Student::with(['marks','recom'])
            //     'marks' =>
            //         function($query) use ($data) {
            //             $query->where('semester', $data['semester']);
            // },
            //     'recom' =>
            //         function($query) use ($data) {
            //             $query->where('semester', $data['semester']);
            // }])
            ->where('faculty_code', $data['faculty'])
            ->where('level_code', $data['level'])
            ->where('batch_code', $data['batch']);

        // Filter for absent students if requested
        // if ($data['absent']) {
        //     $query->whereDoesntHave('marks', function($q) use ($data) {
        //         $q->where('semester', $data['semester']);
        //     });
        // }

        $students = $query->get();


        $subjects = ResMark::where('semester', $semester)
            ->where('faculty_code', $faculty)
            ->where('level_code', $level)
            ->with('subject')
            ->get()
            ->unique('subject_code')
            ->pluck('subject');
            $dataForm = [
                'students' => $students,
                'subjects' => $subjects,
                'faculty' => $faculty,
                'level' => $level,
                'batch' => '2021',
                'semester' => $semester,
            ];
        return view('filament.pages.print-grades', compact([
           'dataForm'
        ]));

        // return view('filament.pages.print-grades', compact(
        //     'data',
        // ));
    }
}
