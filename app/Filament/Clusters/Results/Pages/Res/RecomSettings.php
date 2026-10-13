<?php

namespace App\Filament\Clusters\Results\Pages\Res;

use Filament\Pages\Page;
use App\Models\Result\Grade;
use App\Models\Result\ResMark;
use App\Models\Result\ResRecoms;
use App\Filament\Clusters\Results;
use Filament\Notifications\Notification;

abstract class RecomSettings extends Page
{

    public function updateRecommendationStats(
        $university_no,
        $faculty_code,
        $level_code,
        $batch_code,
        $semester,
        $examType,
        $mark
    ) {
        // الحصول على السجل الحالي من جدول التوصيات
        $recommendation = ResRecoms::firstOrNew([
            'university_no' => $university_no,
            'faculty_code' => $faculty_code,
            'level_code' => $level_code,
            'batch_code' => $batch_code,
            'semester' => $semester,
            'examType' => $examType,
        ]);

        // حساب القيم الجديدة
        $currentCount = $recommendation->countSubject ?? 0;
        $currentSum = $recommendation->sumMarks ?? 0;

        // تحديث القيم
        $recommendation->countSubject = $currentCount + 1;
        $recommendation->sumMarks = $currentSum + $mark;


        // حفظ التغييرات
        $recommendation->save();

        return $recommendation;
    }
}
