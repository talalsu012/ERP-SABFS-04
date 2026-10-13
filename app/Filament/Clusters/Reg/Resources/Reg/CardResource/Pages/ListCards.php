<?php

namespace App\Filament\Clusters\Reg\Resources\Reg\CardResource\Pages;

use App\Filament\Clusters\Reg\Resources\Reg\CardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCards extends ListRecords
{
    protected static string $resource = CardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('help')
            ->label('مساعدة')
            ->icon('heroicon-o-question-mark-circle')
            ->color('info')
            ->modalContent(view('Help.card'))
            ->modalWidth('6xl')
            ->modalSubmitAction(false)
        ];
    }
}
