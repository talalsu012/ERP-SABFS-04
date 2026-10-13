<?php

namespace App\Filament\Clusters\Reg\Resources\Reg\BatchResource\Pages;

use App\Filament\Clusters\Reg\Resources\Reg\BatchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBatch extends EditRecord
{
    protected static string $resource = BatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
