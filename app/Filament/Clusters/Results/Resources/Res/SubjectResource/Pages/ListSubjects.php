<?php

namespace App\Filament\Clusters\Results\Resources\Res\SubjectResource\Pages;

use App\Filament\Clusters\Results\Resources\Res\SubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubjects extends ListRecords
{
    protected static string $resource = SubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
