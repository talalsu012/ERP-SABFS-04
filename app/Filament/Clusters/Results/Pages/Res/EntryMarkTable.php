<?php

namespace App\Filament\Clusters\Results\Pages\Res;

use Filament\Tables\Table;
use App\Helpers\MarkHelper;
use App\Models\Reg\Student;
// use App\Models\Result\ResMark;
use Illuminate\Support\Str;
// use App\Services\ResultService;
use App\Enums\ShowAbsentCode;
use App\Models\Result\Subject;
use App\Enums\RecommendationCode;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;

trait EntryMarkTable
{

    public function table(Table $table): Table
    {

        return $table
            ->query(function () {
                $remedialCodes = ShowAbsentCode::getRemedialValues();
                $FailMarkCodes = ShowAbsentCode::getMarkValues();
                $query = Student::query()
                    ->with([
                        'marks' => fn($query) => $query
                            ->when($this->subject, fn($q) => $q->where('subject_code', $this->subject)),
                        'marks.subject',
                        'marks.reason',
                        'faculty',
                        'batch',
                        'grouping' => fn($query) => $query->with('room'),
                    ])
                    ->when($this->faculty, fn($q, $facultyCode) => $q->whereHas('faculty', fn($q) => $q->where('code', $facultyCode)))
                    ->when($this->level, fn($q, $levelCode) => $q->whereHas('level', fn($q) => $q->where('code', $levelCode)))
                    ->when($this->batch, fn($q, $batchCode) => $q->whereHas('batch', fn($q) => $q->where('code', $batchCode)))
                    ->when($this->absent == 2, function ($q) use ($remedialCodes, $FailMarkCodes) {

                        $q->whereHas('recom', function ($recomQuery) use ($remedialCodes) {

                            $recomQuery->whereIn('recom_code', $remedialCodes);

                        })

                            ->whereHas('marks', function ($markQuery) use ($FailMarkCodes) {

                                if ($this->subject) {
                                    $markQuery->where('subject_code', $this->subject);
                                }

                                $markQuery->where('batch_code', $this->batch)
                                     ->where('examType',1)
                                     ->where('level_code',$this->level)
                                     ->whereIn('grade', $FailMarkCodes);
                              
                            });

                    });

                $this->StudentCount = $query->count();

                return $query;
            })
            ->heading(function () {
                $count = 0;
                $heading = __('res.entry_mark');
                try {
                    if ($this->subject) {
                        $subject = Subject::find($this->subject);
                        $heading .= $subject
                            ? ' ' . $subject->name_ar . ' [' . $subject->crch . ']'
                            : ' ' . __('Unknown Subject');
                    }
                } catch (\Exception $e) {
                    $count = 0;
                }
                return $heading;
            })
            ->defaultSort('name_ar', 'asc')
            ->columns([
                SelectColumn::make('reason')
                    ->label(__('res.reason'))
                    ->searchable(isIndividual: true, isGlobal: true)
                    ->options(\App\Models\Result\ResResons::pluck('name', 'code'))
                    ->updateStateUsing(function (Student $record, $state) {
                        MarkHelper::InsertReson(
                            $record->university_no,
                            $this->subject,
                            $state,
                            $this->absent,
                            $this->batch,
                        );
                    })
                     ->disabled(function ($record) {
                        
                        if ($this->absent == 2) {
                            return true; // Always enabled for absent == 2
                        }

                        // Default to enabled (false) in all other cases
                        return false;
                    })
                    ->width('10%')
                    ->getStateUsing(fn(Student $record) => $this->subject
                        ? $record->marks->where('subject_code', $this->subject)->first()?->reason?->code
                        : null),

                TextColumn::make('university_no')
                    ->label(__('reg.university_no'))
                    ->width('12%')
                    ->searchable(isIndividual: true, isGlobal: true)
                    ->sortable(),

                TextColumn::make('name_ar')
                    ->label(__('reg.name_ar'))
                    ->searchable(isIndividual: true, isGlobal: true)
                    ->sortable()
                    ->width('25%'),

                TextInputColumn::make('marks.mark')
                    ->label(__('res.exam'))
                    ->rules(['nullable', 'numeric', 'min:0'])
                    ->searchable(
                        isIndividual: true,
                        isGlobal: true,
                        query: fn(Builder $query, string $search) => $query
                            ->whereHas('marks', fn($q) => $q
                                ->where('mark', 'like', "%{$search}%")
                                ->when($this->subject, fn($q) => $q->where('subject_code', $this->subject)))
                    )

                    ->disabled(function ($record) {
                        // Safely get the first mark's grade
                        $firstMark = $record->marks->first();

                        // Only check if we have a mark and $this->absent == 2
                        if ($this->absent == 2 && $firstMark) {
                            return !in_array(
                                (string) $firstMark->grade,
                                ['راسب', ...ShowAbsentCode::getMarkValues()]
                            );
                        }

                        // Default to enabled (false) in all other cases
                        return false;
                    })
                    ->inputMode('decimal')
                    ->width('10%')
                    // ->getStateUsing($this->absent)
                    ->getStateUsing(fn(Student $record) => $this->subject
                        ? $record->marks
                            ->where('subject_code', $this->subject)
                            ->where('examType', $this->absent)
                            ->first()?->mark
                        : null)
                    ->updateStateUsing(function (Student $record, $state) {
                        MarkHelper::InsertMark(
                            $record->university_no,
                            $this->subject,
                            $state,
                            $this->absent,
                            $this->batch,
                        );
                    }),
                TextColumn::make('marks.grade')
                    ->label(__('res.grade'))
                    ->sortable()
                    ->width('10%')
                    ->searchable(isIndividual: true, isGlobal: true),
                TextColumn::make('grouping.name')
                    ->label('المجموعة')
                    ->badge()
                    ->searchable(isIndividual: true, isGlobal: true)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->listWithLineBreaks()
                    ->getStateUsing(fn(Student $record) => $record->grouping->map(fn($g) => $g->name)->filter()->implode(', ')),
                TextColumn::make('grouping.room')
                    ->label('القاعة')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('success')
                    ->icon('heroicon-m-envelope')
                    ->listWithLineBreaks()
                    ->getStateUsing(fn(Student $record) => $record->grouping->map(fn($g) => $g->pivot->room?->name)->filter()->implode(', ')),
            ])
            // ->actions([
            //     Action::make('undoLastChange')
            //         ->label('Undo')
            //         ->icon('heroicon-o-arrow-uturn-left')
            //         ->color('warning')
            //         ->requiresConfirmation()
            //         ->action(function (Student $record) {
            //             // Get the mark record for this student and subject
            //             $mark = $record->marks
            //                 ->where('subject_code', $this->subject)
            //                 ->first();

            //             if (!$mark) {
            //                 Notification::make()
            //                     ->title('No mark record found')
            //                     ->danger()
            //                     ->send();
            //                 return;
            //             }

            //             $lastAudit = $mark->audits()
            //                 ->where('event', 'updated')
            //                 ->latest()
            //                 ->first();

            //             if ($lastAudit) {
            //                 $oldValues = $lastAudit->old_values;

            //                 if (isset($oldValues['mark'])) {
            //                     $mark->update([
            //                         'mark' => $oldValues['mark'],
            //                         'grade' => $oldValues['grade'],
            //                     ]);

            //                     Notification::make()
            //                         ->title('Mark reverted successfully!')
            //                         ->success()
            //                         ->send();
            //                 } else {
            //                     Notification::make()
            //                         ->title('No previous mark value found to revert.')
            //                         ->warning()
            //                         ->send();
            //                 }
            //             } else {
            //                 Notification::make()
            //                     ->title('No previous changes found for this mark.')
            //                     ->info()
            //                     ->send();
            //             }
            //         })
            //         ->visible(function (Student $record) {
            //             $mark = $record->marks
            //                 ->where('subject_code', $this->subject)
            //                 ->first();
            //             return $mark && $mark->audits()->where('event', 'updated')->exists();
            //         })
            // ])
            ->bulkActions([]);
    }
}
