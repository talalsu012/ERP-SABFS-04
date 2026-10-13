<?php

namespace App\Filament\Resources\Reg\StudentResource;

use App\Models\Reg\Batch;
use App\Models\Reg\Department;
use App\Models\Reg\Faculty;
use App\Models\Reg\Level;
use App\Models\Reg\Lt_column;
use App\Models\Reg\Special;
use App\Models\Reg\StudentAccount;
use App\Traits\HasFacultyScope;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action as ActionTable;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;

use Filament\Tables\Filters\QueryBuilder;

use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class TableSchema
{
    use HasFacultyScope;
    public static function getTableSchema(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('faculty.name')
                    ->label(__('reg.faculty'))
                    ->collapsible(),
                Group::make('level.name')
                    ->label(__('reg.level'))
                    ->collapsible(),
                Group::make('x_status.name')
                    ->label(__('reg.xstatus'))
                    ->collapsible(),
                Group::make('admission.name')
                    ->label(__('reg.admission'))
                    ->collapsible(),
            ])
            ->columns([
                ImageColumn::make('photo')
                    ->defaultImageUrl(url('/images/face.png'))
                    ->width(50)
                    ->circular()
                    ->disk('public') // Specify the storage disk
                    ->getStateUsing(function ($record) {
                        return $record->photo
                            ? asset('storage/' . $record->photo)
                            : null;
                    })
                    ->visibility('public') // Make files publicly accessible
                    ->label(''),
                TextColumn::make('faculty.name')
                    ->visible(fn(): bool => static::shouldShowFacultyFields())
                    ->searchable(isIndividual: true)
                    ->sortable()->toggleable(isToggledHiddenByDefault: false)
                    ->label(__('reg.faculty')),
                TextColumn::make('name_ar')->toggleable(isToggledHiddenByDefault: false)
                    ->label(__('reg.name_ar'))
                    ->searchable(isIndividual: true, isGlobal: true)
                    ->sortable(),
                TextColumn::make('university_no')->toggleable(isToggledHiddenByDefault: false)
                    ->label(__('reg.university_no'))
                    ->searchable(isIndividual: true, isGlobal: true)
                    ->sortable(),
                TextColumn::make('x_status.name')->toggleable(isToggledHiddenByDefault: false)
                    ->label(__('reg.xstatus')),

                TextColumn::make('all_fees')->toggleable(isToggledHiddenByDefault: false)
                    ->getStateUsing(fn($record) => $record->fees . ' | ' . $record->reg_fees)
                    ->label(__('reg.all_fees')),
                TextColumn::make('pay')->label(__('reg.payment'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('xmod')->label(__('reg.xmod'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('faculty.campus.name')->label(__('reg.campus'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('stady.name')->label(__('reg.stady')),
                TextColumn::make('AccountStatus.name')->label(__('reg.student_account'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('level.name')->label(__('reg.level'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('department.name')->label(__('reg.department'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nationalty.name')->label(__('reg.nationality'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name_en')->label(__('reg.name_en'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('special.name')->label(__('reg.special'))->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('academicyear.name')->label(__('reg.academic_year'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('batch.name')
                    ->label(__('reg.batch'))->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nationaltyno')->label(__('reg.nationaltyno'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nationalty.name')->label(__('reg.nationality'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')->label(__('reg.email'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mobile')->label(__('reg.phone'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fees')->label(__('reg.fees'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('reg_fees')->label(__('reg.regfees'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fees1')->label(__('reg.fees1'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fees2')->label(__('reg.fees2'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fees3')->label(__('reg.fees3'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fees6')->label(__('reg.fees6'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fees7')->label(__('reg.fees7'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fees8')->label(__('reg.fees8'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pay1')->label(__('reg.pay1'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pay2')->label(__('reg.pay2'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pay3')->label(__('reg.pay3'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pay4')->label(__('reg.pay4'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label(__('reg.updated_at'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->label(__('reg.created_at'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('reg.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->searchDebounce('900ms')
            //  ->modifyQueryUsing(fn (Builder $query) => static::scopeFacultyQuery($query))
            ->filters([
                TrashedFilter::make(),

                // SelectFilter::make('faculty')
                //     ->label(__('reg.faculty'))
                //     ->options(
                //         Faculty::all()
                //             ->mapWithKeys(fn ($faculty) => [
                //                 $faculty->code => "{$faculty->name} ({$faculty->code})"
                //           ])
                //     )
                //     ->attribute('faculty_code')
                //     ->searchable()
                //     ->searchable()
                //     ->multiple()
                //     ->preload(),



                Filter::make('faculty_department')
                    ->form([
                        Select::make('faculty_code')
                            ->label(__('reg.faculty'))
                            ->options(Faculty::pluck('name', 'code'))
                            ->multiple()
                            ->live()
                            ->afterStateUpdated(fn(callable $set) => $set('department_code', null)),

                        Select::make('department_code')
                            ->label(__('reg.department'))
                            ->multiple()
                            ->options(function (Get $get): array {
                                $faculties = $get('faculty_code');
                                return Department::query()
                                    ->when(!empty($faculties), fn($q) => $q->whereIn('faculty_code', (array) $faculties))
                                    ->pluck('name', 'code')
                                    ->toArray();
                            }),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(!empty($data['faculty_code']), fn($q) => $q->whereIn('faculty_code', (array) $data['faculty_code']))
                            ->when(!empty($data['department_code']), fn($q) => $q->whereIn('department_code', (array) $data['department_code']));
                    }),

                SelectFilter::make(__('reg.level'))
                    ->relationship('level', 'name')
                    ->attribute('level_code')
                    ->searchable()->multiple()
                    ->preload(),
                SelectFilter::make('admission_code')
                    ->label(__('reg.admission'))
                    ->options(Lt_column::all()
                        ->where('grouped', '2001')
                        ->pluck('name', 'code')),

                SelectFilter::make('xstatus')
                    ->label(__('reg.xstatus'))->multiple()
                    ->options(Lt_column::all()
                        ->where('grouped', '2007')
                        ->pluck('name', 'code')),

                SelectFilter::make('gender_code')
                    ->label(__('reg.gender'))
                    ->options(Lt_column::all()
                        ->where('grouped', '1001')
                        ->pluck('name', 'code')),

                QueryBuilder::make()
                    ->constraintPickerColumns(4)
                    ->constraints([
                        TextConstraint::make('name_ar')
                            ->label(__('reg.name_ar'))->nullable(),
                        SelectConstraint::make('batch_code')
                            ->label(__('reg.batch'))
                            ->options(Batch::all()
                                ->pluck('name', 'code')),

                        SelectConstraint::make('academic_year_code')
                            ->label(__('reg.academic_year'))
                            ->options(Lt_column::all()
                                ->where('grouped', '2003')
                                ->pluck('name', 'code')),

                        BooleanConstraint::make('gender_code')->label(__('reg.gender')),
                        NumberConstraint::make('fees')->label(__('reg.fees')),
                        NumberConstraint::make('reg_fees')->label(__('reg.regfees')),
                        NumberConstraint::make('fees1')->label(__('reg.fees1')),
                        NumberConstraint::make('fees2')->label(__('reg.fees2')),
                        NumberConstraint::make('fees3')->label(__('reg.fees3')),
                        NumberConstraint::make('fees6')->label(__('reg.fees6')),
                        NumberConstraint::make('fees7')->label(__('reg.fees7')),
                        NumberConstraint::make('fees8')->label(__('reg.fees8')),
                        SelectConstraint::make('installment_code')
                            ->label(__('reg.Installments'))
                            ->options(Lt_column::all()
                                ->where('grouped', '2005')
                                ->pluck('name', 'code')),

                        NumberConstraint::make('all_fees')->label(__('reg.all_fees')),
                        NumberConstraint::make('xmod')->label(__('reg.xmod')),
                        DateConstraint::make('created_at'),
                    ])
            ], layout: FiltersLayout::Modal)
            ->filtersFormColumns(3)
            ->deferFilters()
            ->deferLoading()
            ->filtersFormWidth(MaxWidth::SixExtraLarge)
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->slideOver()
                    ->modalFooterActionsAlignment(Alignment::End)
                    ->modalCancelAction(false)
                    ->extraModalFooterActions(function (Table $table) use ($action) {
                        return [
                            $table->getFiltersApplyAction()
                                ->close(),
                            StaticAction::make('cancel')
                                ->label($action->getModalCancelActionLabel())
                                ->button()
                                ->close()
                                ->color('gray'),
                            Action::make('resetFilters')
                                ->label(__('Clear All'))
                                ->color('primary')
                                ->link()
                                ->extraAttributes([
                                    'class' => 'me-auto',
                                ])
                                ->action('resetTableFiltersForm'),
                        ];
                    })
            )
            ->emptyStateActions([
                ActionTable::make('create')
                    ->label('Create Student')
                    ->url(route('filament.admin.resources.reg.students.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                    Action::make('activities')->url(fn($record) =>  \App\Filament\Resources\Reg\StudentResource::getUrl('activities', ['record' => $record])),

                    Action::make('resetAccount')
                        ->label('Reset Account Student')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn($record) => ! empty($record->university_no)) // Only show if university_no is present
                        ->action(function ($record) {
                            // Find the student account by university_no
                            $account = StudentAccount::where('university_no', $record->university_no)->first();

                            // If no account found, notify and exit
                            if (! $account) {
                                Notification::make()
                                    ->warning()
                                    ->title('لم يتم العثور على حساب لهذا الطالب')
                                    ->send();
                                return;
                            }
                            try {
                                DB::transaction(function () use ($account) {
                                    // Delete the account
                                    $account->delete();

                                    // Reset other fields as needed
                                    // $account->update(['balance' => 0, 'some_field' => null]);
                                });
                                Notification::make()
                                    ->success()
                                    ->title('تمت معالجة حساب الطالب بنجاح')
                                    ->send();
                            } catch (\Throwable $e) {
                                Notification::make()
                                    ->danger()
                                    ->title('حدث خطأ أثناء معالجة الحساب')
                                    ->body($e->getMessage())
                                    ->send();
                            }
                        })
                ])
            ], position: ActionsPosition::BeforeCells);
    }

    public static function getBulkActions(): array
    {
        return [
            DeleteBulkAction::make(),
            \Filament\Tables\Actions\ForceDeleteBulkAction::make(),
            \Filament\Tables\Actions\RestoreBulkAction::make(),
            BulkAction::make('updateStudents')
                ->label(__('reg.updateStudent'))
                ->icon('heroicon-o-pencil')
                ->action(function (Collection $records, array $data) {
                    $filtered = Arr::whereNotNull($data);
                    foreach ($records as $record) {
                        $record->update($filtered);
                    }
                    \Filament\Notifications\Notification::make()
                        ->title('Updated successfully')
                        ->success()
                        ->body('Changes to the Students have been updated.')
                        ->send();
                })
                ->form([
                    Section::make()
                        ->description(__('reg.updateStudents'))
                        ->columns([
                            'sm' => 3,
                        ])
                        ->schema([
                            Select::make('faculty_code')
                                ->label(__('reg.faculty'))
                                ->options(Faculty::get()->pluck('name', 'code'))
                                ->live()->native()->searchable(),

                            Select::make('department_code')->label(__('reg.department'))
                                ->options(fn(Get $get): Collection => Department::query()
                                    ->where('faculty_code', $get('faculty_code'))
                                    ->pluck('name', 'code'))->live()->searchable(),

                            Select::make('special_code')
                                ->label(__('reg.special'))
                                ->options(fn(Get $get): Collection => Special::query()
                                    ->where('department_code', $get('department_code'))
                                    ->pluck('name', 'code'))->searchable(),

                            Select::make('level_code')
                                ->label(__('reg.level'))
                                ->options(Level::all()->pluck('name', 'code'))
                                ->searchable()
                                ->preload(),

                            Select::make('stady_code')
                                ->label(__('reg.stady'))
                                ->native()
                                ->options(Lt_column::all()
                                    ->where('group_slug', 'stady_code')
                                    ->pluck('name', 'code')),

                            Select::make('student_account')
                                ->label(__('reg.student_account'))
                                ->native()
                                ->options(Lt_column::all()
                                    ->where('grouped', '4002')
                                    ->pluck('name', 'code'))
                                ->selectablePlaceholder(true),

                            Select::make('batch_code')
                                ->label(__('reg.batch'))
                                ->relationship('batch', 'name')
                                ->searchable(),

                            ToggleButtons::make('admission_code')
                                ->label(__('reg.admission'))
                                ->inline()
                                ->options(Lt_column::all()
                                    ->where('grouped', '2001')
                                    ->pluck('name', 'code')),

                            Select::make('nationality_code')
                                ->label(__('reg.nationality'))
                                ->native()
                                ->options(Lt_column::all()
                                    ->where('grouped', '1002')
                                    ->pluck('name', 'code'))
                                ->searchable(),

                            Radio::make('gender_code')->inline()
                                ->options(Lt_column::all()
                                    ->where('grouped', '1001')
                                    ->pluck('name', 'code'))
                                ->label(__('reg.gender')),

                            Select::make('academic_year_code')
                                ->label(__('reg.academic_year'))
                                ->native()
                                ->options(Lt_column::all()
                                    ->where('grouped', '2003')
                                    ->pluck('name', 'code')),

                            Fieldset::make(__('reg.fees_students'))
                                ->schema([
                                    TextInput::make('reg_fees')
                                        ->label(__('reg.regfees'))
                                        ->numeric(),

                                    TextInput::make('fees')
                                        ->label(__('reg.fees'))
                                        ->numeric(),

                                    TextInput::make('fees1')
                                        ->label(__('reg.fees1'))
                                        ->numeric()->default(0),

                                    TextInput::make('fees2')
                                        ->label(__('reg.fees2'))
                                        ->numeric()->default(0),

                                    TextInput::make('fees3')
                                        ->label(__('reg.fees3'))
                                        ->numeric()->default(0),

                                    TextInput::make('fees6')
                                        ->label(__('reg.fees6'))
                                        ->numeric()->default(0),

                                    TextInput::make('fees7')
                                        ->label(__('reg.fees7'))
                                        ->numeric()->default(0),

                                    TextInput::make('fees8')
                                        ->label(__('reg.fees8'))
                                        ->numeric()->default(0),

                                    ToggleButtons::make('currency')
                                        ->inline()
                                        ->options([
                                            'SDG' => 'SDG',
                                            'USD' => 'USD',
                                            'SAR' => 'SAR'
                                        ])
                                        ->colors([
                                            'SDG' => 'info',
                                            'USD' => 'warning',
                                            'SAR' => 'success',
                                        ])
                                        ->icons([
                                            'SDG' => 'heroicon-o-pencil',
                                            'USD' => 'heroicon-o-clock',
                                            'SAR' => 'heroicon-o-check-circle',
                                        ])
                                        ->label(__('reg.currency'))
                                        ->default('SDG')
                                        ->columnSpan([
                                            'xl' => 3
                                        ]),

                                    Select::make('installment_code')
                                        ->label(__('reg.Installments'))
                                        ->native()
                                        ->options(Lt_column::all()
                                            ->where('group_slug', 'installment_code')
                                            ->pluck('name', 'code'))
                                        ->columnSpan([
                                            'sm' => 2
                                        ]),
                                ])->columns(6),
                        ])
                ])
        ];
    }
}
