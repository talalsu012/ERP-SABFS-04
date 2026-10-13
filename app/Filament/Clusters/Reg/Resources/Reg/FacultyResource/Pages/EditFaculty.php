<?php

namespace App\Filament\Clusters\Reg\Resources\Reg\FacultyResource\Pages;

use App\Filament\Clusters\Reg\Resources\Reg\FacultyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaculty extends EditRecord
{
    protected static string $resource = FacultyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
