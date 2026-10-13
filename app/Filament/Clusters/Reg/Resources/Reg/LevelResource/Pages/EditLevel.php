<?php

namespace App\Filament\Clusters\Reg\Resources\Reg\LevelResource\Pages;

use App\Filament\Clusters\Reg\Resources\Reg\LevelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLevel extends EditRecord
{
    protected static string $resource = LevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
