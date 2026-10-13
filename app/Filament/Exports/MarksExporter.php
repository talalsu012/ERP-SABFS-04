<?php

namespace App\Filament\Exports;

use App\Models\Result\ResMark;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class MarksExporter extends Exporter
{
    protected static ?string $model = ResMark::class;


    public static function getColumns(): array
    {
        //	university_no, subject_code, faculty_code, level_code, batch_code, semester, examType, mark, cwork, grade, reson_code
        return [
            // ExportColumn::make('id')->label(__('ID')),
            ExportColumn::make('university_no')->label(__('reg.university_no')),
            ExportColumn::make('student.name_ar')->label(__('reg.name_ar')),
            ExportColumn::make('mark')->label(__('res.mark')),
            ExportColumn::make('grade')->label(__('res.grade')),
            ExportColumn::make('resons.name_ar')->label(__('res.ResResons')),
            
            ExportColumn::make('semester')
                ->label(__('res.semester'))
                ->formatStateUsing(fn ($state) => match ($state) {
                    '1' => 'الفصل الأول',
                    '2' => 'الفصل الثاني',
                    '3' => 'سنوي / تكميلي',
                    default => $state,
                }),

            // نوع الامتحان
            ExportColumn::make('examType')
                ->label('نوع الامتحان')
                ->formatStateUsing(fn ($state) => $state == 1 ? 'أساسي' : 'ملحق'),


            ExportColumn::make('subject.name_ar')->label(__('res.subject')),
            ExportColumn::make('faculty.name')->label(__('reg.faculty')),
            ExportColumn::make('level.name')->label(__('reg.level')),
            ExportColumn::make('batch.name')->label(__('reg.batch'))
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your marks export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
