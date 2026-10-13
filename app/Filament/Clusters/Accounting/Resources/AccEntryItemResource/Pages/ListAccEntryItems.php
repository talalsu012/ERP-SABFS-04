<?php

namespace App\Filament\Clusters\Accounting\Resources\AccEntryItemResource\Pages;

use App\Filament\Clusters\Accounting\Resources\AccEntryItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccEntryItems extends ListRecords
{
    protected static string $resource = AccEntryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
