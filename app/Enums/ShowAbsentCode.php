<?php

namespace App\Enums;

/**
 * Enum لتحديد أكواد التوصيات النهائية للطلاب
 */
enum ShowAbsentCode: string
{
    // إعادة بكل المقررات
    case ReAllCourses = '2001';

    // إعادة ببعض المقررات
    case ReSomeCourses = '2002';

    // بديل
    case Substitute = '3001';

    // بديل + ملحق
    case SubstituteAndSupplementary = '3004';

    // ملحق
    case Supplementary = '4001';
 //    // غائب
    case SupplementaryMark = 'رسوب';
    case FailMark = 'راسب';
    case SubstituteMark = 'بديل';
    /**
     * دالة مساعدة لجلب قيم الطلاب الملحقين أو البدلاء فقط
     *
     * @return array<int, string>
     */
    public static function getRemedialValues(): array
    {
        return [
            self::Substitute->value,
          //  self::ReSomeCourses->value,
           // self::ReAllCourses->value,
            self::SubstituteAndSupplementary->value,
            self::Supplementary->value,
        ];
    }

    public static function getMarkValues(): array
    {
        return [
            self::SupplementaryMark->value,
            self::SubstituteMark->value,
            self::FailMark->value,
        ];
    }
}
