<?php

namespace App\Http\Controllers;

use App\Models\Reg\Batch;
use App\Models\Reg\Level;
use App\Models\Reg\Student;
use Illuminate\Http\Request;
use App\Models\attendances\Lecture;
use App\Models\attendances\Grouping;

class StudentReportController extends Controller
{
   public function show($lectureID)
{

    // Check if the user has permission to view the lecture
    // if (!auth()->user()->can('view', $lecture)) {
    //     abort(403, 'Unauthorized action.');
    // }
    $lecture = Lecture::with(['grouping.students.batch', 'grouping.students.level'])
        ->where('id', $lectureID)
        ->first();
    // Check if lecture exists
    if (!$lecture) {
        abort(404, 'المحاضرة غير موجودة');
    }


    $students = $lecture->grouping->students()
                    ->with(['batch', 'level'])
                    ->where('room_id', $lecture->room_id)
                    ->orderBy('name_ar')
                    ->get();

    $batchName = optional($students->first())->batch->name ?? 'غير محدد';
    $levelName = optional($students->first())->level->name ?? 'غير محدد';


    $group = $lecture->grouping;

    return view('reports.student-list', [
        'group' => $group,
        'lecture' => $lecture,
        'batchName' => $batchName,
        'levelName' => $levelName,
        'students' => $students
    ]);
}

public function exam($lectureID)
{
    $lecture = Lecture::with([
        'grouping.students.batch',
        'grouping.students.level',
        'subject.faculty',
        'staff',
        'room',
    ])
        ->find($lectureID);

    if (! $lecture) {
        abort(404, 'المحاضرة غير موجودة');
    }

    if (! $lecture->grouping) {
        abort(404, 'لا توجد مجموعة مرتبطة بالمحاضرة');
    }

    $students = $lecture->grouping
        ->students()
        ->with([
            'batch',
            'level',
        ])
        ->where('room_id', $lecture->room_id)
        ->orderBy('name_ar')
        ->get();

    $firstStudent = $students->first();

    $batchName = $firstStudent?->batch?->name
        ?? $firstStudent?->batch?->name_ar
        ?? 'غير محدد';

    $levelName = $firstStudent?->level?->name
        ?? $firstStudent?->level?->name_ar
        ?? 'غير محدد';

    return view('reports.exam-list', [
        'lecture'   => $lecture,
        'group'     => $lecture->grouping,
        'students'  => $students,
        'batchName' => $batchName,
        'levelName' => $levelName,
    ]);
}

}
