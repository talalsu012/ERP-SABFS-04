<?php

namespace App\Filament\Clusters\Accounting\Resources\UsersResource\Pages;

use App\Filament\Clusters\Accounting\Resources\UsersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsers extends EditRecord
{
    protected static string $resource = UsersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
