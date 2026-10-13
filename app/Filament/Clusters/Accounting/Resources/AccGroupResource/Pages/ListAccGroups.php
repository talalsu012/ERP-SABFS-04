<?php

namespace App\Filament\Clusters\Accounting\Resources\AccGroupResource\Pages;

use App\Filament\Clusters\Accounting\Resources\AccGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccGroups extends ListRecords
{
    protected static string $resource = AccGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
