<?php

namespace App\Filament\Exports\Reg;

use App\Models\Reg\Faculty;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class FacultyExporter extends Exporter
{
    protected static ?string $model = Faculty::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('code'),
            ExportColumn::make('campus_code'),
            ExportColumn::make('name'),
            ExportColumn::make('name_en'),
            ExportColumn::make('slug'),
            ExportColumn::make('is_visible'),
            ExportColumn::make('start_reg'),
            ExportColumn::make('end_reg'),
            ExportColumn::make('primary_hex'),
            ExportColumn::make('results_published'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your faculty export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
