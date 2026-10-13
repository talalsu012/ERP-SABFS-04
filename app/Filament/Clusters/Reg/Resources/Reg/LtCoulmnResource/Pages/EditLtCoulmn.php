<?php

namespace App\Filament\Clusters\Reg\Resources\Reg\LtCoulmnResource\Pages;

use App\Filament\Clusters\Reg\Resources\Reg\LtCoulmnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLtCoulmn extends EditRecord
{
    protected static string $resource = LtCoulmnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
