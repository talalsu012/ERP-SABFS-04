<?php

namespace App\Filament\Clusters\Results\Resources\Result\ResMarkResource\Pages;

use App\Filament\Clusters\Results\Resources\Result\ResMarkResource;
use App\Filament\Exports\MarksExporter;
use App\Filament\Imports\Result\ResMarkImporter;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Actions\Imports\Jobs\ImportCsv;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\ExportAction;

class ListResMarks extends ListRecords
{
    protected static string $resource = ResMarkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportAction::make()
                ->importer(ResMarkImporter::class)
                ->job(ImportCsv::class)
                ->label(__('res.ImportStudentsMarks'))
                ->csvDelimiter(';')
        ];
    }
}