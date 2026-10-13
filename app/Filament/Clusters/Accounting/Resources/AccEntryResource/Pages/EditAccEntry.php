<?php

namespace App\Filament\Clusters\Accounting\Resources\AccEntryResource\Pages;

use App\Filament\Clusters\Accounting\Resources\AccEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccEntry extends EditRecord
{
    protected static string $resource = AccEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
