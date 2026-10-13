<?php

namespace App\Filament\Clusters\Results\Resources\Result\ResLtRecomResource\Pages;

use App\Filament\Clusters\Results\Resources\Result\ResLtRecomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResLtRecoms extends ListRecords
{
    protected static string $resource = ResLtRecomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
