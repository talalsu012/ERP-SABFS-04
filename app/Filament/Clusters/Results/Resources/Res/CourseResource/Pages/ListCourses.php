<?php

namespace App\Filament\Clusters\Results\Resources\Res\CourseResource\Pages;

use App\Filament\Clusters\Results\Resources\Res\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
