<?php

namespace App\Filament\Clusters\Results\Resources\Result\RecomResource\Pages;

use App\Filament\Clusters\Results\Resources\Result\RecomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecoms extends ListRecords
{
    protected static string $resource = RecomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
