<?php

use App\Http\Controllers\BatchReportController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\Res\PrintSummaryController;
use App\Http\Controllers\Result\PrintCourseController;
use App\Http\Controllers\ResultCrosstabController;
use App\Http\Controllers\StudentGradesController;
use App\Http\Controllers\StudentReportController;
use App\Http\Controllers\StudentResultsController;
use App\Models\attendances\Grouping;
use App\Models\Reg\Batch;
use App\Models\Reg\Student;
use App\Models\Result\Course;
use App\Models\Result\Mark;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/verify/{hash}', [QRCodeController::class, 'verify'])
    ->name('certificate.verify')
    ->middleware('auth');

Route::get('/certificates/{certificate}/affidavit', [CertificateController::class, 'affidavit'])
    ->name('certificates.affidavit')
    ->middleware('auth');
e('certificate.download');


Route::get('/transcript/{universityNo}/{lang}', [CertificateController::class, 'generate_details'])
    ->name('transcript.details')
    ->middleware([Authenticate::class]);


    
Route::get('/student-results/{dataForm}', [StudentResultsController::class, 'showResults'])
    ->name('student.results')
    ->middleware([Authenticate::class]);
Route::get('/results-summary/{dataForm}', [PrintSummaryController::class, 'showSmmarySubjects'])
    ->name('results.summary')
    ->middleware([Authenticate::class]);




//reports/results/35/1003/1/1
Route::get('/reports/results/{course_code}/{level_code}/{semester}/{examType}', [ResultCrosstabController::class, 'show'])
    ->name('results.crosstab.show');

Route::get('/grouping/report/{grouping}', function (Grouping $grouping) {
    return view('reports.grouping', compact('grouping'));
})->middleware([Authenticate::class]);

Route::get('/report/lecture/{lectureID}', [StudentReportController::class, 'show'])
    ->name('report.lecture.attendance')
    ->middleware([Authenticate::class]);


// كشف الامتحان
Route::get(
    '/report/lecture/{lectureID}/exam',
    [StudentReportController::class, 'exam']
)
    ->name('report.lecture.exam')
    ->middleware([Authenticate::class]);
    
Route::get('/print/grades/{dataForm}', [StudentGradesController::class, 'printGrades'])
    ->name('print.details')
    ->middleware([Authenticate::class]);
// Route::get('/grades/print/{course_code}', [StudentGradesController::class, 'printGrades']);

// Route::get('/print-course', [PrintCourseController::class, 'printCourse'])->name('print.course');

Route::get('/student/{student}/card', function (App\Models\Reg\Card $student) {
    return view('reg/card/student-card', ['student' => $student]);
})->name('student.card');


// Route::get('/filament/{courseCode}', function ($courseCode) {
//     // Fetch data based on courseCode
//     $batch = Batch::with(['course.subjects' => function ($query) {
//         $query->orderBy('name_ar');
//     }])->where('course_code', $courseCode)
//         ->firstOrFail();

//     $subjects = $batch->course->subjects;
//     $students = Student::with(['marks' => function ($query) use ($subjects) {
//         $query->whereIn('code', $subjects->pluck('code'));
//     }])->where('batch_code', $batch->code)
//         ->orderBy('name_ar')
//         ->get();

//     return view('filament', compact('batch', 'subjects', 'students'));
// })->name('filament');

Route::get('/certificates/{certificate}/generate', [CertificateController::class, 'generate'])
    ->name('certificates.generate')
    ->middleware('auth');
e('certificate.download');

Route::get('/PrintResult/{data}', [CertificateController::class, 'PrintResult'])
    ->name('PrintResult');


Route::get('/student/{student}/card', function (App\Models\Reg\Card $student) {
    return view('reg/card/student-card', ['student' => $student]);
})->name('student.card');

Route::get('/print/view', function (Request $request) {
    $courseId = $request->input('course_id');
    $batchId = $request->input('batch_id');

    // Fetch data with relationships
    $data = [
        'course' => Course::with(['batches.students.marks'])
            ->findOrFail($courseId),
        'batch' => Batch::with(['students.marks'])
            ->findOrFail($batchId),
        'marks' => Mark::whereIn('student_id', function ($query) use ($batchId) {
            $query->select('id')
                ->from('students')
                ->where('batch_id', $batchId);
        })
            ->get()
            ->groupBy('student_id')
    ];

    return view('print.results', $data);
})->name('print.results');


/* Route::get('/', function () {
    return view('welcome');
}); */