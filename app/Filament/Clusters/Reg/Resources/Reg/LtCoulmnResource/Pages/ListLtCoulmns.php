<?php

namespace App\Filament\Clusters\Reg\Resources\Reg\LtCoulmnResource\Pages;

use App\Filament\Clusters\Reg\Resources\Reg\LtCoulmnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLtCoulmns extends ListRecords
{
    protected static string $resource = LtCoulmnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('help')
            ->label('مساعدة')
            ->icon('heroicon-o-question-mark-circle')
            ->color('info')
            ->modalContent(view('Help.ltcolumn'))
            ->modalWidth('4xl')
            ->modalSubmitAction(false)
        ];
    }
}
