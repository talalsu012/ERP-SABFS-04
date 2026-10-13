<?php

namespace App\Filament\Clusters\Reg\Resources\Reg\CardResource\Pages;

use App\Filament\Clusters\Reg\Resources\Reg\CardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCard extends EditRecord
{
    protected static string $resource = CardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirect to list page
    }
}
