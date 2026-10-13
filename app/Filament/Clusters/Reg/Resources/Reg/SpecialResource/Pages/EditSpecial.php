<?php

namespace App\Filament\Clusters\Reg\Resources\Reg\SpecialResource\Pages;

use App\Filament\Clusters\Reg\Resources\Reg\SpecialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpecial extends EditRecord
{
    protected static string $resource = SpecialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
