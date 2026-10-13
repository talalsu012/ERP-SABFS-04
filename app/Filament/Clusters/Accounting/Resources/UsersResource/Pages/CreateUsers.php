<?php

namespace App\Filament\Clusters\Accounting\Resources\UsersResource\Pages;

use App\Filament\Clusters\Accounting\Resources\UsersResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUsers extends CreateRecord
{
    protected static string $resource = UsersResource::class;
}
