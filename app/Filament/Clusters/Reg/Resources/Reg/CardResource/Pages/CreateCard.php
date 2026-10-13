<?php

namespace App\Filament\Clusters\Reg\Resources\Reg\CardResource\Pages;

use App\Filament\Clusters\Reg\Resources\Reg\CardResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCard extends CreateRecord
{
    protected static string $resource = CardResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirect to list page
    }
}
