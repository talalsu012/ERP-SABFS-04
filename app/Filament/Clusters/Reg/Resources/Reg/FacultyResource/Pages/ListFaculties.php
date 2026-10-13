<?php

namespace App\Filament\Clusters\Reg\Resources\Reg\FacultyResource\Pages;

use Filament\Actions;
use App\Filament\Components\HelpModal;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Clusters\Reg\Resources\Reg\FacultyResource;

class ListFaculties extends ListRecords
{
    protected static string $resource = FacultyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('help')
                ->label('مساعدة')
                ->icon('heroicon-o-question-mark-circle')
                ->modalContent(view('Help.faculty'))
                ->modalWidth('4xl')
                ->modalSubmitAction(false)  // This hides the submit button
                // ->modalCancelAction() // This hides the cancel button (optional)

        ];
    }
}
