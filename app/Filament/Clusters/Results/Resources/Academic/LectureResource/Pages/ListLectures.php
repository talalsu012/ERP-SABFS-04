<?php

namespace App\Filament\Clusters\Results\Resources\Academic\LectureResource\Pages;

use App\Filament\Clusters\Results\Resources\Academic\LectureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLectures extends ListRecords
{
    protected static string $resource = LectureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
