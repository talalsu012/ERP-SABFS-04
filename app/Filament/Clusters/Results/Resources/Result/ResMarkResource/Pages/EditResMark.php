<?php

namespace App\Filament\Clusters\Results\Resources\Result\ResMarkResource\Pages;

use App\Filament\Clusters\Results\Resources\Result\ResMarkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResMark extends EditRecord
{
    protected static string $resource = ResMarkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
