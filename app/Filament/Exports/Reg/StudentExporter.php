<?php

namespace App\Filament\Exports\Reg;

use App\Models\Reg\Student;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class StudentExporter extends Exporter
{
    protected static ?string $model = Student::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('university_no')->label(__('reg.university_no')),
            ExportColumn::make('name_ar')->label(__('reg.name_ar')),
            ExportColumn::make('name_en')->label(__('reg.name_en')),
            ExportColumn::make('campus.name')->label(__('reg.campus')),
            ExportColumn::make('faculty.name')->label(__('reg.faculty')),
            ExportColumn::make('department.name')->label(__('reg.department')),
            ExportColumn::make('special.name')->label(__('reg.special')),
            ExportColumn::make('level.name')->label(__('reg.level')),
            ExportColumn::make('admission_code')->label(__('reg.admission')),
            ExportColumn::make('stady_code')->label(__('reg.stady')),
            ExportColumn::make('academic_year_code')->label(__('reg.academic_year')),
            ExportColumn::make('status_code')->label(__('reg.status')),
            ExportColumn::make('batch_code')->label(__('reg.batch')),
            ExportColumn::make('nationaltyno')->label(__('reg.nationality')),
            // ExportColumn::make('id_photo'),
            ExportColumn::make('gender_code')->label(__('reg.gender')),
            // ExportColumn::make('birth_place'),
            // ExportColumn::make('birth_date'),
            // ExportColumn::make('photo'),
            ExportColumn::make('nationality_code')->label(__('reg.nationality')),
            // ExportColumn::make('state_code'),
            // ExportColumn::make('city'),
            // ExportColumn::make('localty'),
            // ExportColumn::make('block'),
            // ExportColumn::make('home_no'),
            ExportColumn::make('email')->label(__('reg.email')),
            // ExportColumn::make('email_verified_at'),
            ExportColumn::make('mobile')->label(__('reg.phone')),
            // ExportColumn::make('social_media'),
            // ExportColumn::make('activites'),
            // ExportColumn::make('skill'),
            ExportColumn::make('parent')->label(__('reg.parent')),
            // ExportColumn::make('parent_alive'),
            // ExportColumn::make('parent_job'),
            // ExportColumn::make('parent_address'),
            ExportColumn::make('parent_contacts')->label(__('reg.parent_contacts')),
            // ExportColumn::make('mother'),
            // ExportColumn::make('mother_alive'),
            // ExportColumn::make('mother_job'),
            // ExportColumn::make('mother_address'),
            ExportColumn::make('mother_contacts')->label(__('reg.mother_contacts')),
            // ExportColumn::make('brothers'),
            ExportColumn::make('currency')->label(__('reg.currency')),
            ExportColumn::make('installment_code')->label(__('reg.Installments')),
            ExportColumn::make('fees')->label(__('reg.fees')),
            ExportColumn::make('reg_fees')->label(__('reg.regfees')),
            ExportColumn::make('fees1')->label(__('reg.fees1')),
            ExportColumn::make('fees2')->label(__('reg.fees2')),
            ExportColumn::make('fees3')->label(__('reg.fees3')),
            // ExportColumn::make('fees4'),
            // ExportColumn::make('fees5'),
            ExportColumn::make('fees6')->label(__('reg.fees6')),
            ExportColumn::make('fees7')->label(__('reg.fees7')),
            ExportColumn::make('fees8')->label(__('reg.fees8')),
            // ExportColumn::make('fees9'),
            // ExportColumn::make('fees10'),
            ExportColumn::make('pay1')->label(__('reg.pay1')),
            ExportColumn::make('pay2')->label(__('reg.pay2')),
            ExportColumn::make('pay3')->label(__('reg.pay3')),
            ExportColumn::make('pay4')->label(__('reg.pay4')),
            ExportColumn::make('all_fees')->label(__('reg.all_fees')),
            ExportColumn::make('pay')->label(__('reg.pay')),
            ExportColumn::make('xmod')->label(__('reg.xmod')),
            ExportColumn::make('xstatus')->label(__('reg.xstatus')),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your student export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
