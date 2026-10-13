<?php

namespace App\Filament\Clusters\Accounting\Resources\AccEntryItemResource\Pages;

use App\Filament\Clusters\Accounting\Resources\AccEntryItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccEntryItem extends CreateRecord
{
    protected static string $resource = AccEntryItemResource::class;
}
