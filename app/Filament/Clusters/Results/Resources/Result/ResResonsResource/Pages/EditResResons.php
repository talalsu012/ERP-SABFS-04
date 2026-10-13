<?php

namespace App\Filament\Clusters\Results\Resources\Result\ResResonsResource\Pages;

use App\Filament\Clusters\Results\Resources\Result\ResResonsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResResons extends EditRecord
{
    protected static string $resource = ResResonsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
