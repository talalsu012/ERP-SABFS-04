<?php

namespace App\Filament\Clusters\Accounting\Resources\AccEntryResource\Pages;

use App\Filament\Clusters\Accounting\Resources\AccEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccEntries extends ListRecords
{
    protected static string $resource = AccEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
