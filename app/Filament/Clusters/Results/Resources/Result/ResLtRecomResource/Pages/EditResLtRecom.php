<?php

namespace App\Filament\Clusters\Results\Resources\Result\ResLtRecomResource\Pages;

use App\Filament\Clusters\Results\Resources\Result\ResLtRecomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResLtRecom extends EditRecord
{
    protected static string $resource = ResLtRecomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
