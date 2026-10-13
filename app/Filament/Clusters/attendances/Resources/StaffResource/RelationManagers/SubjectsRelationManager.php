<?php

namespace App\Filament\Clusters\attendances\Resources\StaffResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Reg\Batch;
use App\Models\Reg\Level;
use Filament\Tables\Table;
use App\Models\Reg\Faculty;
use App\Models\Result\Subject;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SubjectsRelationManager extends RelationManager
{


    protected static string $relationship = 'subjects';


    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name_ar')
            ->columns([
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('res.subject'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('batch_code')
                    ->label(__('reg.batch'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                    TextColumn::make('faculty.name')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label(__('reg.faculty')),
                    TextColumn::make('department.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->label(__('reg.department')),
                TextColumn::make('level.name')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label(__('reg.level')),
                TextColumn::make('semester_id')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label(__('res.semester')),
                TextColumn::make('name_en')
                    ->label(__('reg.name_en'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                AttachAction::make()
                    ->form(fn (AttachAction $action): array => [
                        Select::make('batch_code')
                            ->label(__('reg.batch'))
                            ->required()
                            ->options(Batch::query()->pluck('name', 'code')),
                        Select::make('faculty_code')
                            ->label(__('reg.faculty'))
                            ->options(Faculty::get()->pluck('name', 'code'))
                            ->live(),
                        Select::make('level_code')
                            ->label(__('reg.level'))
                            ->options(Level::all()->pluck('name', 'code'))
                            ->live(),
                            Select::make('semester_id')->required()
                            ->label(__('res.semester'))
                            ->native()
                            ->options([
                                '1' => __('res.first_semester'),
                                '2' => __('res.second_semester'),

                            ])
                            ->selectablePlaceholder(false),
                        //$action->getRecordSelect(),
                        $action->getRecordSelect()
                            ->label(__('res.subject'))
                            ->multiple()
                            ->options(function (Forms\Get $get) {
                                // Initialize the query for subjects
                                $query = Subject::query();

                                // Filter by faculty if selected
                                if ($facultyCode = $get('faculty_code')) {
                                    $query->where('faculty_code', $facultyCode);
                                }

                                // Filter by level if selected - fixed table reference
                                if ($levelCode = $get('level_code')) {
                                    $query->where('level_code', $levelCode); // Changed from students.level_code to just level_code
                                }

                                if ($semester = $get('semester_id')) {
                                    $query->where('semester_id', $semester); // Changed from students.level_code to just level_code
                                }

                                return $query->pluck('name_ar', 'code');
                            })
                            //   ->searchable(['name_ar', 'code']) // Specify searchable columns
                            ->required()
                            ->preload() // Preload options for better performance
                            ->native(false), // Added native false for better UI
                    ])
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
                // Tables\Actions\EditAction::make(),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
