<?php

namespace App\Filament\Imports\Result;

use App\Models\Result\ResMark;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use App\Models\Result\ResResons;

class ResMarkImporter extends Importer
{
    protected static ?string $model = ResMark::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('university_no')
                ->label(__('reg.university_no'))
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('subject_code')
            ->label(__('res.subject'))
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('faculty_code')
            ->label(__('reg.faculty'))
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('level_code')
            ->label(__('reg.level'))
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('batch_code')
            ->label(__('reg.batch'))
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('semester')
            ->label(__('res.semester')),
            ImportColumn::make('examType')
            ->label(__('res.type')),
            ImportColumn::make('mark')
            ->label(__('res.mark'))
                ->numeric()
                ->rules(['integer']),
            // ImportColumn::make('cwork')
            //     ->numeric()
            //     ->rules(['integer']),
            ImportColumn::make('grade')
                ->rules(['max:20'])
                ->label(__('res.grade')),
            // ImportColumn::make('point')
            //     ->numeric()
            //     ->rules(['integer']),
            // ImportColumn::make('note')
            //     ->rules(['max:250']),
            // ImportColumn::make('is_visible')
            //     ->requiredMapping()
            //     ->boolean()
            //     ->rules(['required', 'boolean']),
            // ImportColumn::make('is_locked')
            //     ->requiredMapping()
            //     ->boolean()
            //     ->rules(['required', 'boolean']),
            // ImportColumn::make('is_released')
            //     ->requiredMapping()
            //     ->boolean()
            //     ->rules(['required', 'boolean']),
            // ImportColumn::make('is_recheck')
            //     ->requiredMapping()
            //     ->boolean()
            //     ->rules(['required', 'boolean']),
            // ImportColumn::make('is_recheck_passed')
            //     ->requiredMapping()
            //     ->boolean()
            //     ->rules(['required', 'boolean']),
            ImportColumn::make('reson_code')
                ->numeric()
                ->label(__('res.ResResons'))
                ->rules(['integer']),
        ];
    }

    public function resolveRecord(): ?ResMark
    {
        // Try to find an existing record by the unique keys used elsewhere in the app.
        $keys = [
            'university_no' => $this->data['university_no'] ?? null,
            'subject_code' => $this->data['subject_code'] ?? null,
            'faculty_code' => $this->data['faculty_code'] ?? null,
            'level_code' => $this->data['level_code'] ?? null,
            'semester' => $this->data['semester'] ?? null,
            'batch_code' => $this->data['batch_code'] ?? null,
            'examType' => $this->data['examType'] ?? null,
        ];

        // If we don't have the minimal identifying keys, return a new model so import will create it.
        if (empty($keys['university_no']) || empty($keys['subject_code'])) {
            return new ResMark();
        }

        // Filter out null keys to avoid matching on empty values
        $search = array_filter($keys, function ($v) {
            return $v !== null && $v !== '';
        });

        return ResMark::firstOrNew($search);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your res mark import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}