<?php

namespace App\Filament\Clusters\Reg\Resources\Reg\PaymentResource\Pages;

use App\Filament\Clusters\Reg\Resources\Reg\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('help')
            ->label('مساعدة')
            ->icon('heroicon-o-question-mark-circle')
            ->color('info')
            ->modalContent(view('Help.payment'))
            ->modalWidth('6xl')
            ->modalSubmitAction(false)  // This hides the submit button
        ];
    }
}
