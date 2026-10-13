<?php

namespace App\Filament\Clusters\Results\Resources\Res\gradeResource\Pages;
use App\Filament\Clusters\Results\Resources\Res\gradeResource;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Editgrade extends EditRecord
{
    protected static string $resource = gradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
