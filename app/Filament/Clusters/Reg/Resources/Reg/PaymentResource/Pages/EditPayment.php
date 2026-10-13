<?php

namespace App\Filament\Clusters\Reg\Resources\Reg\PaymentResource\Pages;

use Filament\Actions;
use App\Models\Reg\Payment;
use Illuminate\Contracts\View\View;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Clusters\Reg\Resources\Reg\PaymentResource;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
            // Actions\Action::make('print')
            // ->label('Print Receipt')
            // ->url(fn () => PaymentResource::getUrl('print', ['record' => $this->record]))
            // ->icon('heroicon-o-printer'),
            Actions\Action::make('print')
            ->label('Print')
            ->modalContent(fn (Payment $record): View => view(
                'print-payment-test',
                ['record' => $record],
            ))
            // ->slideOver()
            ->modalWidth(MaxWidth::Medium)
            // ->url(fn (Payment $record): string => route('filament.admin.reg.resources.reg.payments.print', $record))
            //E:\pro\erp12\resources\views\print-payment-test.blade.php
            ->color('success')
            ->icon('heroicon-o-chart-bar')
        ];
    }

}
