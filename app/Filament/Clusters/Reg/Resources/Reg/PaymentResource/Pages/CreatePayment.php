<?php

namespace App\Filament\Clusters\Reg\Resources\Reg\PaymentResource\Pages;

use App\Filament\Clusters\Reg\Resources\Reg\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('print', ['record' => $this->record]);
    // }
}
