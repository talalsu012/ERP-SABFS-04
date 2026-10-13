<?php

namespace App\Filament\Clusters\attendances\Resources\GroupingResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;

use App\Models\Reg\Batch;
use App\Models\Reg\Level;
use Filament\Tables\Table;
use App\Models\Reg\Faculty;
use App\Models\Reg\Student;
use App\Models\Reg\Lt_column;
use App\Models\attendances\Room;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Models\attendances\GroupingStudent;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Relations\Relation;

class StudentRelationManager extends RelationManager
{
    protected static string $relationship = 'students';
    protected static ?string $recordTitleAttribute = 'name_ar';

    public int $countStd = 0;
    public int $limited;

    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name_ar')
            ->query(function (Builder $query) {
                $query->with(['groupings' => function (Relation $relation) {
                    $relation->withPivot(['batch_code', 'room_id']);
                    $relation->with('pivot.room');
                }]);
            })
            ->columns([
                Tables\Columns\TextColumn::make('university_no')
                    ->label(__('reg.university_no'))
                    ,
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('reg.name_ar'))
                    ,
                Tables\Columns\TextColumn::make('faculty.name')
                    ->label(__('reg.faculty')),
                Tables\Columns\TextColumn::make('batch.name')
                    ->label(__('reg.batch')),
                Tables\Columns\TextColumn::make('level.name')
                    ->label(__('reg.level')),

                Tables\Columns\TextColumn::make('room_name')
                    ->label(__('Attendance.room'))
                    ->getStateUsing(function ($record) {
                        $roomNames = $record->grouping
                            ->map(function ($grouping) {
                                if ($grouping->pivot && $grouping->pivot->room) {
                                    return $grouping->pivot->room->name;
                                }
                                return null;
                            })
                            ->filter()
                            ->unique()
                            ->implode(', ');

                        return $roomNames ?: 'N/A';
                    })
                    ->listWithLineBreaks(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('faculty')
                    ->relationship('faculty', 'name')->label(__('reg.faculty'))
                    ->searchable(),
                Tables\Filters\SelectFilter::make('batch')->label(__('reg.batch'))
                    ->relationship('batch', 'name')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('level')->label(__('reg.level'))
                    ->relationship('level', 'name')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('room_id')
                    ->label(__('Attendance.room'))
                    ->options(Room::query()
                        ->pluck('name', 'id'))
                    ->searchable(),

            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->modalWidth('6xl')
                    ->form([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Radio::make('gender_code')->inline()
                                            ->options(Lt_column::all()
                                                ->where('grouped', '1001')
                                                ->pluck('name', 'code'))
                                            ->live()
                                            ->label(__('reg.gender')),


                                        Select::make('faculty_code')
                                            ->label(__('reg.faculty'))
                                            // ->required()
                                            ->options(Faculty::query()->pluck('name', 'code'))
                                            ->live()
                                            ->afterStateUpdated(fn (callable $set) => $set('batch_code', null)),

                                        Select::make('room_id')
                                            ->label(__('Attendance.room'))
                                            // ->required()
                                            ->options(function (callable $get) {
                                                return Room::query()->pluck('name', 'id');
                                            })
                                            ->live()
                                            ->required(),

                                        Select::make('batch_code')
                                            ->label(__('reg.batch'))
                                            // ->required()
                                            ->options(function (callable $get) {
                                                $facultyCode = $get('faculty_code');

                                                return Batch::query()->pluck('name', 'code');
                                            })
                                            ->live()
                                            ->required(),

                                        Select::make('level_code')
                                            ->label(__('reg.level'))
                                            // ->required()
                                            ->options(function (callable $get) {

                                                return Level::query()->pluck('name', 'code');
                                            })
                                            ->live(),

                                        CheckboxList::make('students_by_letter')
                                            ->label(__('Filter by Alphabetical Group'))
                                            // ->placeholder(__('Select a letter group'))
                                            ->options(function (callable $get) {
                                                $query = Student::query();

                                                // Apply filters from other fields to count students for groups
                                                if ($facultyCode = $get('faculty_code')) {
                                                    $query->where('students.faculty_code', $facultyCode);
                                                }
                                                if ($genderCode = $get('gender_code')) {
                                                    $query->where('students.gender_code', $genderCode);
                                                }
                                                if ($batchCode = $get('batch_code')) {
                                                    $query->where('students.batch_code', $batchCode);
                                                }
                                                if ($levelCode = $get('level_code')) {
                                                    $query->where('students.level_code', $levelCode);
                                                }

                                                // Get all students matching the current filters
                                                $students = $query->get();

                                                // Group students by the first letter of their Arabic name and count them
                                                $groupedStudents = $students->groupBy(function ($student) {
                                                    // Safely get the first character, ensuring the name is not empty
                                                    if (empty($student->name_ar)) {
                                                        return null; // Group empty names separately or ignore
                                                    }
                                                    return mb_substr($student->name_ar, 0, 1, 'UTF-8');
                                                })
                                                    ->filter(function ($group, $letter) {
                                                        // Remove groups with null letters (from empty name_ar) if desired
                                                        return $letter !== null;
                                                    })
                                                    ->mapWithKeys(function ($group, $letter) {
                                                        // Format for Select options: ['letter_key' => 'Letter (Count)']
                                                        return [$letter => "{$letter} ({$group->count()})"];
                                                    })
                                                    ->sortKeys(); // Sort the letter groups alphabetically

                                                return $groupedStudents->toArray();
                                            })
                                            ->live()
                                            ->bulkToggleable()
                                            ->columns(4)
                                            ->columnSpanFull()
                                            ->afterStateUpdated(function (callable $set, $state) {
                                                // When a letter group is selected, update the 'students_by_letter' CheckboxList
                                                // if ($state) {
                                                //     $set('students_by_letter', [$state]); // Set the CheckboxList to only the selected letter
                                                // } else {
                                                //     $set('students_by_letter', []); // Clear the CheckboxList if nothing is selected
                                                // }
                                            }),

                                    ])
                                    ->columnSpan(1),

                                Forms\Components\Group::make()
                                    ->schema([
                                        Placeholder::make('student_count')
                                            ->label(__('reg.students'))
                                            ->content(function (callable $get) {
                                                // Re-run the options logic to get the count
                                                $query = Student::query();

                                                // $existingStudents = $this->getOwnerRecord()
                                                //     ->students()
                                                //     ->pluck('students.university_no')
                                                //     ->toArray();
                                                // $query->whereNotIn('students.university_no', $existingStudents);

                                                $hasActiveFilters = false;
                                                if ($facultyCode = $get('faculty_code')) {
                                                    $query->where('students.faculty_code', $facultyCode);
                                                    $hasActiveFilters = true;
                                                }
                                                if ($genderCode = $get('gender_code')) {
                                                    $query->where('students.gender_code', $genderCode);
                                                    $hasActiveFilters = true;
                                                }
                                                if ($batchCode = $get('batch_code')) {
                                                    $query->where('students.batch_code', $batchCode);
                                                    $hasActiveFilters = true;
                                                }
                                                if ($levelCode = $get('level_code')) {
                                                    $query->where('students.level_code', $levelCode);
                                                    $hasActiveFilters = true;
                                                }
                                                if ($studentsByLetter = $get('students_by_letter')) {
                                                    $query->where(function ($q) use ($studentsByLetter) {
                                                        foreach ($studentsByLetter as $letter) {
                                                            $q->orWhere('students.name_ar', 'like', "$letter%");
                                                        }
                                                    });
                                                    $hasActiveFilters = true;
                                                }

                                                if (!$hasActiveFilters) {
                                                    $query->limit(30);
                                                }

                                                $count = $query->count(); // Get the count directly
                                                return $count . "  " . __('reg.student');
                                            })
                                            ->live(),

                                        CheckboxList::make('university_no')
                                            ->label(__('reg.students')) // Keep this label simple
                                            ->required()
                                            ->options(function (callable $get) {
                                                $query = Student::query();

                                                // $existingStudents = $this->getOwnerRecord()
                                                //     ->students()
                                                //     ->pluck('students.university_no')
                                                //     ->toArray();
                                                // $query->whereNotIn('students.university_no', $existingStudents);

                                                $hasActiveFilters = false;
                                                if ($facultyCode = $get('faculty_code')) {
                                                    $query->where('students.faculty_code', $facultyCode);
                                                    $hasActiveFilters = true;
                                                }
                                                if ($genderCode = $get('gender_code')) {
                                                    $query->where('students.gender_code', $genderCode);
                                                    $hasActiveFilters = true;
                                                }
                                                if ($batchCode = $get('batch_code')) {
                                                    $query->where('students.batch_code', $batchCode);
                                                    $hasActiveFilters = true;
                                                }
                                                if ($levelCode = $get('level_code')) {
                                                    $query->where('students.level_code', $levelCode);
                                                    $hasActiveFilters = true;
                                                }
                                                if ($studentsByLetter = $get('students_by_letter')) {
                                                    $query->where(function ($q) use ($studentsByLetter) {
                                                        foreach ($studentsByLetter as $letter) {
                                                            $q->orWhere('students.name_ar', 'like', "$letter%");
                                                        }
                                                    });
                                                    $hasActiveFilters = true;
                                                }

                                                if (!$hasActiveFilters) {
                                                    $query->limit(30);
                                                }

                                                return $query->orderBy('students.name_ar', 'asc')
                                                    ->pluck('name_ar', 'university_no');
                                            })
                                            ->live() // Keep this live so it reacts to other filter changes
                                            ->columns(3)
                                            ->gridDirection('row')
                                            ->searchable() // Client-side search for CheckboxList
                                            ->bulkToggleable(),
                                    ])
                                    ->columnSpan(2),
                            ])
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        return [
                            'university_no' => $data['university_no'],
                            'batch_code' => $data['batch_code'], // Add this line
                            'room_id' => $data['room_id'] // Add this line
                        ];
                    })
                    ->action(function (array $data, AttachAction $action) {
                        $grouping = $this->getOwnerRecord();
                        $batchCode = $data['batch_code'];
                        $roomId = $data['room_id'];

                        foreach ((array)$data['university_no'] as $universityNo) {
                            $grouping->students()->attach($universityNo, [
                                'batch_code' => $batchCode,
                                'room_id' => $roomId, // Attach the room_id
                            ]);
                        }

                        $action->success();
                    })
                    ->preloadRecordSelect(),
            ])
            // ->headerActions([
            //     Tables\Actions\AttachAction::make()
            //         ->modalWidth('6xl')
            //         ->form([
            //             Forms\Components\Grid::make(3)
            //                 ->schema([
            //                     // Your existing form fields...
            //                 ])
            //         ])
            //         ->mutateFormDataUsing(function (array $data): array {
            //             return [
            //                 'university_no' => $data['university_no'],
            //                 'batch_code' => $data['batch_code'] // Add this line
            //             ];
            //         })
            //         ->action(function (array $data, AttachAction $action) {
            //             $grouping = $this->getOwnerRecord();
            //             $batchCode = $data['batch_code'];

            //             foreach ((array)$data['university_no'] as $universityNo) {
            //                 $grouping->students()->attach($universityNo, [
            //                     'batch_code' => $batchCode
            //                 ]);
            //             }

            //             $action->success();
            //         })
            //         ->preloadRecordSelect(),
            // ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
