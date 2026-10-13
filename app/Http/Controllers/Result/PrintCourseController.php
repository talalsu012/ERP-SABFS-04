<?php

namespace App\Http\Controllers\Result;


use Illuminate\Http\Request;
use App\Models\Result\Course;
use App\Http\Controllers\Controller;

class PrintCourseController extends Controller
{
    public function printCourse()
{
    // Get the course_id from the request
    $courseId = request()->input('course_id');

    // Query with join
    $courseData = Course::where('courses.code', $courseId)
        ->join('batches', 'batches.course_code', '=', 'courses.code') // adjust join conditions
        ->select('courses.*', 'batches.code as batch_code') // select needed columns
        ->first();

    // Alternatively, using relationships if defined
    $courseData = Course::with('batches')->where('code', $courseId)->first();

    // if (!$courseData) {
    //     abort(404); // or handle missing course
    // }

    // return view('print.course', compact('courseData'));
    return view('Result.Print.Course');
}
}
