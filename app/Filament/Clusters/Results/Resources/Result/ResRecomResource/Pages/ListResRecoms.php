<?php

namespace App\Filament\Clusters\Results\Resources\Result\ResRecomResource\Pages;

use App\Filament\Clusters\Results\Resources\Result\ResRecomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResRecoms extends ListRecords
{
    protected static string $resource = ResRecomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
