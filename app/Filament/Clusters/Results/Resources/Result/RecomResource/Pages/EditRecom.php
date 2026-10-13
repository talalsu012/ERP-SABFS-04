<?php

namespace App\Filament\Clusters\Results\Resources\Result\RecomResource\Pages;

use App\Filament\Clusters\Results\Resources\Result\RecomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecom extends EditRecord
{
    protected static string $resource = RecomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
