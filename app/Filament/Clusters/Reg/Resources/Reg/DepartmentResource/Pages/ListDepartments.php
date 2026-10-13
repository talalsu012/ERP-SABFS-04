<?php

namespace App\Filament\Clusters\Reg\Resources\Reg\DepartmentResource\Pages;

use App\Filament\Clusters\Reg\Resources\Reg\DepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
