<?php

namespace App\Helpers\Services;

use App\Helpers\StudentHelper;
use App\Models\Result\ResMark;
use App\Models\Result\Subject;
use App\Models\Result\ResRecom;
use Illuminate\Support\Facades\Log;

class MarkCalculationService
{
    private string $university_no;
    private string $faculty_code;
    private string $level_code;
    private string $batch_code;
    private string $semester;
    private string $examType;
    private array $studentDetails;

    public function __construct(
        string $university_no,
        string $faculty_code,
        string $level_code,
        string $batch_code,
        string $semester,
        string $examType
    ) {
        $this->university_no = $university_no;
        $this->faculty_code = $faculty_code;
        $this->level_code = $level_code;
        $this->batch_code = $batch_code;
        $this->semester = $semester;
        $this->examType = $examType;
        $this->studentDetails = StudentHelper::getStudentDetails($university_no);
    }

    /**
     * حساب إحصائيات الطالب
     */
    public function calculateStatistics($Fianal = \false): array
    {
        $subjects = $this->getSubjects($Fianal);
        $marks = $this->getStudentMarks($Fianal);


        $stats = [
            'countSubject' => $subjects->count(),
            'sumMarks' => 0,
            's_pass' => 0,
            's_fail' => 0,
            's_abs' => 0,
            'crch' => 0,
            'points' => 0,
            'gpa' => 0,
        ];

        $uniqueSubjects = [];
        $marksFail = $this->getPassingMark();

        foreach ($marks as $markRecord) {
            $stats['sumMarks'] += $markRecord->mark;

            // تحديد حالة المادة (ناجح/راسب/غائب)
            if ($markRecord->reson_code == 4001) {
                $stats['s_abs']++;
            } elseif ($markRecord->mark >= $marksFail) {
                $stats['s_pass']++;
            } else {
                $stats['s_fail']++;
            }

            // حساب CRCH والنقاط للمواد الفريدة
            if (!isset($uniqueSubjects[$markRecord->subject_code])) {
                $subject = $this->getSubjectModel($markRecord->subject_code);
                if ($subject) {
                    $stats['crch'] += $subject->crch ?? 0;
                    $stats['points'] += ($markRecord->point * ($subject->crch ?? 0));
                }
                $uniqueSubjects[$markRecord->subject_code] = true;
            }
        }

        // حساب المعدل التراكمي
        $stats['gpa'] = ($stats['countSubject']) > 0
            ? $stats['sumMarks'] / ($stats['countSubject'])
            : 0;

        return $stats;
    }

    /**
     * تحديد التوصية المناسبة للطالب
     */
    /**
     * تحديد التوصية المناسبة للطالب
     */
    public function determineRecommendation(array $stats): string
    {
        $marks = $this->getStudentMarks(\true);

        //  $failurePercentage = ($stats['countSubject']) > 0
        //     ? floor((($stats['s_fail'] / $stats['countSubject']) * 100) / 10) * 10
        //     : 0;
		//remove floor function nasreldeen
         $failurePercentage = ($stats['countSubject']) > 0
            ? (($stats['s_fail'] / $stats['countSubject']) * 100)
            : 0;

		//if 	$stats['countSubject'] / 2 odd number $stats['countSubject'] + 1
        $adjustedCount = $stats['countSubject'];
		if ($stats['countSubject'] % 2 == 1) {
			$adjustedCount = $stats['countSubject'] + 1;

             $failurePercentage = ($adjustedCount) > 0
            ? (($stats['s_fail'] / $adjustedCount) * 100)
            : 0;
		}
		if($this->university_no == '2021000001'){
            dd($stats, $failurePercentage, $adjustedCount);
		}
		//dd($stats['countSubject'],$marks->count());
        // غير مكتمل
        if ($stats['countSubject'] != $marks->count()) {
            Log::error("Incomplete data for student {$this->university_no}. Total subjects: {$stats['countSubject']}, Inserted subjects: " . $marks->count());
            return "9999";
        }

        // Faculty > 85 PostGraduate
        if ($this->examType == 1 && $this->semester == 2) {
            return $this->handleFinalExamRecommendation($stats, $failurePercentage);
        } elseif ($this->examType == 2) {
            return $this->handleMidtermRecommendation($stats, $failurePercentage);
        }

        return '';
    }

    private function handleFinalExamRecommendation(array $stats, float $failurePercentage): string
    {
        if ($stats['s_fail'] > 0 || $stats['s_abs'] > 0) {
            if ($failurePercentage > 70) {
                return "2001"; // كل المقررات
            } elseif ($failurePercentage > 50 && $failurePercentage <= 70) {
				if ($this->hasPreviousRepeat()) {
                    return "4004"; //فصل اكاديمي
                }
                return "2002"; //ببعض المقررات
            } elseif ($failurePercentage <= 50 && $stats['s_abs'] == 0) {
                return "4001"; //ملحق
            } elseif ($stats['s_fail'] > 0 && $failurePercentage <= 50 && $stats['s_abs'] > 0) {
                return "3004"; // ملحق + بديل
            } elseif ($stats['s_abs'] > 0 && $stats['s_fail'] == 0) {
                return "3001"; //
            }
        } else {
            return $this->getGpaBasedRecommendation($stats['gpa']);
        }
    }

    private function handleMidtermRecommendation(array $stats, float $failurePercentage): string
    {
        //  dd($stats['s_abs'],$failurePercentage, $this->hasPreviousSupplement());
        if ($stats['s_abs'] > 1) {
            return "3002"; // بديل موجل
        } 
        // elseif ($stats['s_fail'] > 1) {
        //     return "4003"; // ملحق موجل
        // } 
        elseif ($failurePercentage > 70 && !$this->hasPreviousRepeat()) {
            return "2001"; //كل المقررات
        } elseif ($failurePercentage <> 0 && $failurePercentage <= 70 && !$this->hasPreviousRepeat() && !$this->hasPreviousSupplement()) {
            return "2002"; //ببعض المقررات
        } elseif ($stats['s_fail'] == 1 && !$this->hasPreviousSupplement() && !$this->hasPreviousRepeat()) {
            return "4005"; //ازالة رسوب
        } elseif ($stats['s_fail'] > 0 && ($this->hasPreviousRepeat() || $this->hasPreviousSupplement())) {
            return "4004"; //فصل اكاديمي
        }  else {
            return $this->getGpaBasedRecommendation($stats['gpa']);
        }
    }

    private function getGpaBasedRecommendation(float $gpa): string
    {
        if ($gpa >= 80) return "1001";
        if ($gpa >= 70) return "1002";
        if ($gpa >= 60) return "1003";
        return "1004";
    }

    private function getPassingMark(): int
    {
        return $this->studentDetails['stady_code'] == 20060002 ? 40 : 50;
    }

    private function hasPreviousSupplement(): bool
    {
        return ResRecom::where('university_no', $this->university_no)
            ->where('recom_code', 4004)
            ->where('level_code', $this->level_code) // Ensure we are checking the same level
            ->where('batch_code', '<>', $this->batch_code)
            ->exists();
    }

    private function hasPreviousRepeat(): bool
    {
        return ResRecom::where('university_no', $this->university_no)
            ->where('level_code', $this->level_code)
            ->whereIn('recom_code', [2001, 2002])
			->where('batch_code', '<>', $this->batch_code)
            ->exists();
    }

            private function getSubjects(bool $final = false)
                    {
                        $levels = ($this->level_code % 2 === 0)
                            ? [$this->level_code - 1, $this->level_code]
                            : [$this->level_code];

                        return Subject::Exam()
                            ->where('faculty_code', $this->faculty_code)
                           ->where('department_code', $this->studentDetails['department_code'])
                            ->whereIn('level_code', $levels)
                            ->get();
                    }


    private function getStudentMarks($Fianal = \false)
    {
        return ResMark::where('university_no', $this->university_no)
            ->where('faculty_code', $this->faculty_code)
            ->where('level_code', $this->level_code)
            ->where('batch_code', $this->batch_code)
            ->when(!$Fianal, function ($query) {
                $query->where('semester', $this->semester);
            })
            ->get()
            ->sortByDesc('id') // prefer highest examtype by sorting by ID descending
            ->unique('subject_code'); // keep only unique subject_code (keeping the first occurrence which will be the highest ID)
    }


    private function getSubjectModel(string $subject_code): ?Subject
    {
        return Subject::where('code', $subject_code)->first();
    }
}
