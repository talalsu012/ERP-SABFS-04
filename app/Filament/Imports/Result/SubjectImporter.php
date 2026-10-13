<?php

namespace App\Filament\Imports\Result;

use App\Models\Result\Subject;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class SubjectImporter extends Importer
{
    protected static ?string $model = Subject::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
            ->label(__('res.code'))
                ->requiredMapping()
                ->numeric(),
            ImportColumn::make('pin')
            ->label('res.code')
                ->requiredMapping()
                ->numeric(),
            ImportColumn::make('name_ar')
                ->label(__('res.name_ar'))
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('name_en')
            ->label(__('res.name_en'))
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('crch')
            ->label(__('res.crch'))
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('exam')
            ->label(__('res.exam'))
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): ?Subject
    {

        return new Subject();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your subject import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }
        return $body;
    }
}
