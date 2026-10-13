<?php

namespace App\Filament\Clusters\Accounting\Resources\AccLedgerResource\Pages;

use App\Filament\Clusters\Accounting\Resources\AccLedgerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccLedger extends CreateRecord
{
    protected static string $resource = AccLedgerResource::class;
}
