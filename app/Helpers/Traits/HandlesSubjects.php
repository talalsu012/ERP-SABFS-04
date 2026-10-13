<?php

namespace App\Helpers\Traits;

use App\Models\Result\Subject;
use App\Models\Result\ResResons;
use Illuminate\Support\Facades\Log;

trait HandlesSubjects
{
    /**
     * التحقق من صحة بيانات الدرجة المدخلة
     */
    protected static function validateMarkInput(
        string $university_no,
        string $subject_code,
        float $mark
    ): bool {
        if (!is_numeric($mark)) {
            self::sendMarkErrorNotification('الدرجة المدخلة غير صحيحة. يجب أن تكون الدرجة قيمة رقمية.');
            Log::error("MarkHelper: Invalid mark provided for university_no: {$university_no}, subject_code: {$subject_code}. Mark: {$mark}");
            return false;
        }

        return true;
    }

    /**
     * التحقق من صحة بيانات السبب المدخل
     */
    protected static function validateReasonInput(
        string $subject_code,
        string $reson
    ): bool {
        if (empty($subject_code) || empty($reson) || !is_numeric($reson)) {
            self::sendReasonErrorNotification('Invalid subject or reason');
            return false;
        }

        return true;
    }

    /**
     * جلب تفاصيل المادة
     */
    protected static function getSubjectDetails(string $subject_code): array
    {
        $subject = Subject::where('code', $subject_code)->firstOrFail();

        return [
            'code' => $subject->code,
            'name_ar' => $subject->name_ar,
            'faculty_code' => $subject->faculty_code,
            'level_code' => $subject->level_code,
            'semester_id' => $subject->semester_id,
            // يمكن إضافة المزيد من الحقول حسب الحاجة
        ];
    }

    /**
     * إعداد معايير البحث عن الدرجة
     */
    protected static function prepareMarkCriteria(
        string $university_no,
        array $subjectDetails,
        string $batch_code,
        string $examType
    ): array {
        return [
            'university_no' => $university_no,
            'subject_code' => $subjectDetails['code'],
            'faculty_code' => $subjectDetails['faculty_code'],
            'level_code' => $subjectDetails['level_code'],
            'semester' => $subjectDetails['semester_id'],
            'batch_code' => $batch_code,
            'examType' => $examType,
        ];
    }

    /**
     * إعداد بيانات الدرجة للحفظ
     */
    protected static function prepareMarkData(float $mark, string $grade): array
    {
        return [
            'mark' => (float) $mark,
            'grade' => $grade,
            'reson_code' => null
        ];
    }

    /**
     * إعداد بيانات السبب للحفظ
     */
    protected static function prepareReasonData(string $reson): array
    {
        $data = ['reson_code' => $reson];

        if ($reson > 2000) {
            $resonDesc = ResResons::where('code', $reson)->first();
            if ($resonDesc) {
                $data = [
                    'mark' => '0',
                    'grade' => $resonDesc->description,
                    'reson_code' => $reson
                ];
            }
        }

        return $data;
    }
}
