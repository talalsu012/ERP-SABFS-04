<?php

namespace App\Filament\Clusters\Results\Resources\Res\SubjectResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Reg\Batch;
use Filament\Tables\Table;
use App\Models\Reg\Student;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'Courses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('subject_code')
                //     ->required()
                //     ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label(__('reg.name')),
                Tables\Columns\TextColumn::make('code')->label(__('reg.code')),
                Tables\Columns\TextColumn::make('start')->label(__('reg.start_date')),
                Tables\Columns\TextColumn::make('end')->label(__('reg.end_date')),
                Tables\Columns\ToggleColumn::make('is_visible')->label(__('reg.is_visible')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->modalWidth('6xl')
                    ->form([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Select::make('batch_code')
                                        ->label(__('reg.batch'))
                                            ->required()
                                            ->options(function (callable $get) {
                                                return Batch::query()->pluck('name', 'code');
                                            })
                                            ->live()
                                            // ->afterStateUpdated(fn (callable $set) => $set('level_code', null)),
                                    ])
                                    ->columnSpan(1),

                                Forms\Components\Group::make()
                                    ->schema([
                                        CheckboxList::make('university_no')
                                            ->label(__('Students'))
                                            ->required()
                                            ->options(function (callable $get) {
                                                $query = Student::query();
                                                // Get existing students with explicit table reference
                                                $existingStudents = $this->getOwnerRecord()
                                                    ->students()
                                                    ->pluck('students.university_no')  // Explicitly specify table
                                                    ->toArray();

                                                $query->whereNotIn('students.university_no', $existingStudents);  // Explicit table reference

                                                $query->whereNotIn('university_no', $existingStudents);

                                                if ($batchCode = $get('batch_code')) {
                                                    $query->where('batch_code', $batchCode);
                                                }

                                                return $query
                                                    ->orderBy('name_ar', 'asc')
                                                    ->limit($get('limited'))
                                                    ->get()
                                                    ->mapWithKeys(fn ($student) => [
                                                        $student->university_no => "{$student->name_ar}"
                                                    ]);
                                            })
                                            ->live() // Add this to make it reactive

                                            ->columns(3)->gridDirection('row')
                                            ->searchable()->bulkToggleable(),
                                    ])
                                    ->columnSpan(2),
                            ])
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        return [
                            'recordId' => $data['university_no'], // For multiple selection
                            'university_no' => $data['university_no'],
                        ];
                    })
                    ->preloadRecordSelect(),
                // Add Reset Button in Form Footer
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
