<?php

namespace App\Filament\Resources\Reg\StudentResource\Pages;

use App\Filament\Exports\Reg\StudentExporter;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Imports\Reg\StudentImporter;
use App\Filament\Resources\Reg\StudentResource;
use Filament\Actions\ActionGroup;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentExport;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
            
            
             Action::make('xls')
            ->label('Export XLS')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('info')
            ->action(function () {
                $filteredQuery = $this->getFilteredTableQuery();

                return Excel::download(
                    new StudentExport($filteredQuery), 
                    'Students_' . now()->format('Y-m-d') . '.xlsx'
                );
            }),
            
                Actions\CreateAction::make()
                    ->color('gray'),
                Actions\Action::make('refresh')
                ->label(__('reg.refresh'))
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ,

                ImportAction::make()
                    ->label(__('reg.import_students'))
                    ->color('success')
                    ->importer(StudentImporter::class),
                    // ->options([
                    //     'updateExisting' => true,
                    // ]),
                ExportAction::make()
                    ->exporter(StudentExporter::class)
                    ->label(__('reg.export_students'))
                    ->color('danger')
                    ->icon('heroicon-o-arrow-down-tray'),    
               
            ])
                ->label(__('reg.more_actions'))
                ->button() // Renders the group as a button
                ->outlined()
                ->icon('heroicon-o-cog'),

            Actions\Action::make('help')
                ->label('مساعدة')
                ->icon('heroicon-o-question-mark-circle')
                ->color('info')
                ->modalContent(view('Help.students'))
                ->modalWidth('6xl')
                ->modalSubmitAction(false)
        ];
    }
}