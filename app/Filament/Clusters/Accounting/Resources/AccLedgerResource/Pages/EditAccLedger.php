<?php

namespace App\Filament\Clusters\Accounting\Resources\AccLedgerResource\Pages;

use App\Filament\Clusters\Accounting\Resources\AccLedgerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccLedger extends EditRecord
{
    protected static string $resource = AccLedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
