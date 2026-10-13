<?php

namespace App\Helpers\Traits;

use Filament\Notifications\Notification;

trait HandlesNotifications
{
    /**
     * إرسال إشعار نجاح حفظ الدرجة
     */
    protected static function sendSuccessNotification(string $university_no, string $subjectName): void
    {
        Notification::make()
            ->title('نجاح!')
            ->body('تم حفظ الدرجة بنجاح للطالب ' . $university_no . ' في مادة ' . $subjectName . '.')
            ->success()
            ->send();
    }

    /**
     * إرسال إشعار نجاح تحديث الإحصائيات
     */
    protected static function sendStatisticsSuccessNotification(string $recommendation): void
    {
        Notification::make()
            ->title('نجاح!')
            ->body('تم تحديث إحصائيات الطالب بنجاح لـ ' . $recommendation . '.')
            ->success()
            ->send();
    }

    /**
     * إرسال إشعار خطأ في حفظ الدرجة
     */
    protected static function sendMarkErrorNotification(string $message): void
    {
        Notification::make()
            ->title('خطأ!')
            ->body($message)
            ->danger()
            ->send();
    }

    /**
     * إرسال إشعار خطأ في حفظ السبب
     */
    protected static function sendReasonErrorNotification(string $message): void
    {
        Notification::make()
            ->title('Error!')
            ->body($message)
            ->danger()
            ->send();
    }

    /**
     * إرسال إشعار خطأ في تحديث الإحصائيات
     */
    protected static function sendStatisticsErrorNotification(string $message): void
    {
        Notification::make()
            ->title('خطأ!')
            ->body($message)
            ->danger()
            ->send();
    }
}
