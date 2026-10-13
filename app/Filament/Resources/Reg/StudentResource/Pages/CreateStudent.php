<?php

namespace App\Filament\Resources\Reg\StudentResource\Pages;

use App\Filament\Resources\Reg\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;
}
