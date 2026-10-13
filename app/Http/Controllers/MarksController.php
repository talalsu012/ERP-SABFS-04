<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class MarksController extends Controller
{
    public function index(Request $request)
    {
        $batchCode = $request->input('batch_code');

        // if (!$batchCode) {
        //     return view('fialment_form'); // A simple form to select batch
        // }

        //Get batch with course and subjects
        // $batch = Batch::with(['course.subjects' => function($query) {
        //     $query->orderBy('subject_name_ar');
        // }])->findOrFail($batchCode);

        $batch = Batch::with(['course.subjects' => function($query) {
            $query->orderBy('name_ar'); // Use the actual column name here
        }])->findOrFail($batchCode);

        // Get all students in this batch with their marks
        $students = Student::with(['marks.subject' => function($query) use ($batch) {
            $query->whereIn('code', $batch->course->subjects->pluck('code'));
        }])->where('batch_code', $batchCode)
           ->orderBy('name_ar')
           ->get();
// \dd($students);
        return view('fialment', [
            'batch' => $batch,
            'students' => $students,
            'subjects' => $batch->course->subjects
        ]);
    }

    // public function export(Request $request)
    // {
    //     $batchCode = $request->input('batch_code');
    //     return Excel::download(new FialmentExport($batchCode), "grades_batch_{$batchCode}.xlsx");
    // }
}
