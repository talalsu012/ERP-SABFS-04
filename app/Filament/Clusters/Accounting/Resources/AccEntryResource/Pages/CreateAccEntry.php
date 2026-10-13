<?php

namespace App\Filament\Clusters\Accounting\Resources\AccEntryResource\Pages;

use App\Filament\Clusters\Accounting\Resources\AccEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccEntry extends CreateRecord
{
    protected static string $resource = AccEntryResource::class;
}
