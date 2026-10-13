<?php

namespace App\Filament\Clusters\Results\Resources\Res\CourseResource\Pages;

use App\Filament\Clusters\Results\Resources\Res\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
