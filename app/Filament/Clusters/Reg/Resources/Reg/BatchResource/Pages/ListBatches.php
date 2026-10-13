<?php

namespace App\Filament\Clusters\Reg\Resources\Reg\BatchResource\Pages;

use App\Filament\Clusters\Reg\Resources\Reg\BatchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBatches extends ListRecords
{
    protected static string $resource = BatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
