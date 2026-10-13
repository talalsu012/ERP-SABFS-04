<?php

namespace App\Helpers;

use App\Helpers\Traits\HandlesNotifications;
use App\Helpers\Traits\HandlesSubjects;
use App\Helpers\Traits\HandlesRecommendations;
use App\Helpers\Services\MarkCalculationService;
use App\Models\Result\ResMark;
use App\Models\Result\ResRecom;
use Illuminate\Support\Facades\Log;

class MarkHelper
{
    use HandlesNotifications, HandlesSubjects, HandlesRecommendations;

    /**
     * إدخال أو تحديث درجة الطالب
     */
    public static function InsertMark(
        string $university_no,
        string $subject_code,
        float $mark,
        string $examType,
        string $batch_code
    ): void {
        try {
            // التحقق من صحة البيانات المدخلة
            if (!self::validateMarkInput($university_no, $subject_code, $mark)) {
                return;
            }

            // جلب تفاصيل المادة والطالب
            $subjectDetails = self::getSubjectDetails($subject_code);
            $studentDetails = StudentHelper::getStudentDetails($university_no);
            $grade = GradeHelper::getGradeTitle($mark, $studentDetails['stady_code'] ?? '20060001');

            // حفظ أو تحديث الدرجة
            $resMarkSuccess = ResMark::updateOrCreate(
                self::prepareMarkCriteria($university_no, $subjectDetails, $batch_code, $examType),
                self::prepareMarkData($mark, $grade->titlear)
            );

            if ($resMarkSuccess) {
                self::updateStudentStatistics(
                    $university_no,
                    $subjectDetails['faculty_code'],
                    $subjectDetails['level_code'],
                    $batch_code,
                    $subjectDetails['semester_id'],
                    $examType
                );

                self::sendSuccessNotification($university_no, $subjectDetails['name_ar']);
            }
        } catch (\Exception $e) {
            self::handleMarkError($e, $university_no, $subject_code, $mark, $examType, $batch_code);
        }
    }

    /**
     * إدخال سبب لعدم وجود درجة
     */
    public static function InsertReson(
        string $university_no,
        string $subject_code,
        string $reson,
        string $examType,
        string $batch_code
    ): void {
        try {
            // التحقق من صحة البيانات المدخلة
            if (!self::validateReasonInput($subject_code, $reson)) {
                return;
            }

            // جلب تفاصيل المادة
            $subjectDetails = self::getSubjectDetails($subject_code);

            // حفظ أو تحديث السبب
            $resResonSuccess = ResMark::updateOrCreate(
                self::prepareMarkCriteria($university_no, $subjectDetails, $batch_code, $examType),
                self::prepareReasonData($reson)
            );

            if ($resResonSuccess) {
                self::updateStudentStatistics(
                    $university_no,
                    $subjectDetails['faculty_code'],
                    $subjectDetails['level_code'],
                    $batch_code,
                    $subjectDetails['semester_id'],
                    $examType
                );

                self::sendSuccessNotification($university_no, $subjectDetails['name_ar']);
            }
        } catch (\Exception $e) {
            self::handleReasonError($e);
        }
    }

    /**
     * تحديث إحصائيات الطالب
     */
    public static function updateStudentStatistics(
        string $university_no,
        string $faculty_code,
        string $level_code,
        string $batch_code,
        string $semester,
        string $examType
    ): void {
        try {
            $calculationService = new MarkCalculationService(
                $university_no,
                $faculty_code,
                $level_code,
                $batch_code,
                $semester,
                $examType
            );

            // حساب الإحصائيات
            $Final = ($semester == 2 || $examType == 2 || $faculty_code > 85) ? true : false;
            $stats = $calculationService->calculateStatistics($Final);
            $recommendation = $calculationService->determineRecommendation($stats) ?
                $calculationService->determineRecommendation($stats)
                : '9999';



            $targetSemester = ($semester == 1 && $examType == 2)
                ? 2
                : $semester;

            ResRecom::updateOrCreate(
                [
                    'university_no' => $university_no,
                    'faculty_code' => $faculty_code,
                    'level_code' => $level_code,
                    'batch_code' => $batch_code,
                    'semester' => $targetSemester,
                    'examType' => $examType,
                ],
                array_merge($stats, [
                    'recom_code' => $recommendation,
                ])
            );

            
            self::sendStatisticsSuccessNotification($recommendation);
        } catch (\Exception $e) {
            self::handleStatisticsError($e, $university_no, $faculty_code, $level_code, $batch_code, $semester, $examType);
        }
    }
}
