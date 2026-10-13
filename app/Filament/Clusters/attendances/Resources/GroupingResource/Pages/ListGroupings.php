<?php

namespace App\Filament\Clusters\attendances\Resources\GroupingResource\Pages;

use App\Filament\Clusters\attendances\Resources\GroupingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGroupings extends ListRecords
{
    protected static string $resource = GroupingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
