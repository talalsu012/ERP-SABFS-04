<?php

namespace App\Filament\Resources\Reg;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Reg\Batch;
use App\Models\Reg\Level;
use Filament\Tables\Table;
use App\Models\Reg\Faculty;
use App\Models\Reg\Special;
use App\Models\Reg\Student;
use Illuminate\Support\Arr;
use App\Models\Reg\Lt_column;
use App\Models\Result\Course;
use App\Models\Reg\Department;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\Action as ActionTable;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Reg\StudentResource\Pages;

use App\Filament\Resources\Reg\StudentResource\RelationManagers;
use App\Filament\Resources\Reg\StudentResource\Pages\EditStudent;
use App\Filament\Resources\Reg\StudentResource\Pages\ListStudents;
use App\Filament\Resources\Reg\StudentResource\Pages\CreateStudent;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use App\Filament\Resources\Reg\StudentResource\RelationManagers\MarksRelationManager;
use App\Filament\Resources\Reg\StudentResource\RelationManagers\PaymentRelationManager;


class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()
                ->orderBy('created_at', 'DESC');
    }



    public static function getLabel(): ?string
    {
        return __("reg.student");
    }

    public static function getPluralLabel(): ?string
    {
        return __("reg.students");
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__("reg.students"));
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canEdit(Model $record): bool
    {
        return true;
    }

    public static function canDelete(Model $record): bool
    {
        return true;
    }




    public static function form(Form $form): Form
    {

        return $form
        ->schema([
            Tabs::make(__('reg.basic_info'))
                ->tabs([
                    Tabs\Tab::make(__('reg.basic_info'))
                        ->schema([
                            Split::make([
                                Section::make([
                                    TextInput::make('name_ar')
                                        ->label(__('reg.name_ar'))
                                        ->required()
                                        ->minLength(5),

                                    TextInput::make('name_en')
                                        ->label(__('reg.name_en'))
                                        ->minLength(5),

                                ]),
                                Section::make([
                                    TextInput::make('university_no')
                                        ->label(__('reg.university_no'))
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(20)->required(),

                                    TextInput::make('indexno')
                                        ->label(__('reg.indexno'))
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(20),
                                ])->grow(false),
                            ])->from('md')->columnSpanFull(),


                            Fieldset::make()
                                ->schema([
                                    Select::make('faculty_code')
                                        ->label(__('reg.faculty'))->required()
                                        ->options(Faculty::get()->pluck('name', 'code'))
                                        ->live()->native()->searchable(),

                                    Select::make('department_code')->required()->label(__('reg.department'))
                                        ->options(fn (Get $get): Collection => Department::query()
                                            ->where('faculty_code', $get('faculty_code'))
                                            ->pluck('name', 'code'))->live()->searchable(),

                                    Select::make('special_code')
                                        ->label(__('reg.special'))
                                        ->options(fn (Get $get): Collection => Special::query()
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
                                            ->where('grouped', '2006')
                                            ->pluck('name', 'code'))
                                        ->selectablePlaceholder(false),

                                    Select::make('batch_code')
                                        ->label(__('reg.batch'))->required()
                                        ->relationship('batch', 'name')
                                        ->searchable()
                                        ->createOptionForm([
                                            TextInput::make('code')->label(__('reg.code'))
                                                ->unique(ignoreRecord: true)->maxLength(20)->required(),
                                            TextInput::make('name')->label(__('reg.name'))->maxLength(50)->required(),
                                            // DatePicker::make('batch_start')->label(__('reg.start_date'))->required(),
                                            // DatePicker::make('batch_end')->label(__('reg.end_date'))->required(),
                                            // Select::make('course_code')->options(Course::all()->pluck('name', 'code'))
                                            //     ->label(__('reg.course')),

                                        ])
                                        ->createOptionAction(function (Action $action) {
                                            return $action
                                                ->modalHeading(__('reg.batches'))
                                                ->modalSubmitActionLabel('Create ' . __('reg.batch'))
                                                ->modalWidth('sm');
                                        }),


                                    // Select::make('admission_code')
                                    //     ->label(__('reg.admission'))
                                    //     ->native()
                                    //     ->options(Lt_column::all()
                                    //         ->where('group_slug', 'admission_type')
                                    //         ->pluck('name', 'code'))
                                    //     ->selectablePlaceholder(false),

                                    ToggleButtons::make('admission_code')
                                        ->label(__('reg.admission'))
                                        ->inline()
                                        ->options(Lt_column::all()
                                            ->where('grouped', '2001')
                                            ->pluck('name', 'code'))

                                        ->required(),


                                    Select::make('academic_year_code')
                                        ->label(__('reg.academic_year'))
                                        ->selectablePlaceholder(false)
                                        ->native()
                                        ->options(Lt_column::all()
                                            ->where('grouped', '2003')
                                            ->pluck('name', 'code')),
                                ])
                                ->columns(3),





                        ])->columns(3),
                    Tabs\Tab::make(__('reg.personal_info'))
                        ->schema([
                            Split::make([
                                Section::make([

                                    Select::make('nationality_code')
                                        ->label(__('reg.nationality'))
                                        ->native()
                                        ->options(Lt_column::all()
                                            ->where('grouped', '1002')
                                            ->pluck('name', 'code'))
                                        ->required()
                                        ->selectablePlaceholder(false)
                                        ->searchable(),

                                    Radio::make('gender_code')->inline()
                                        ->options(Lt_column::all()
                                            ->where('grouped', '1001')
                                            ->pluck('name', 'code'))
                                        ->required()
                                        ->label(__('reg.gender')),

                                    TextInput::make('birth_place')->label(__('reg.birth_place'))->required(),

                                    DatePicker::make('birth_date')->label(__('reg.birth_date'))
                                        ->native(false)
                                        ->displayFormat('d/m/Y')
                                        ->required()
                                        ->placeholder('MM/DD/YYYY'),
                                ])->columns(2),
                                FileUpload::make('photo')
                                    ->label(__('reg.image'))
                                    ->image()
                                    ->avatar()
                                    ->imageEditor()
                                    ->circleCropper()
                                    ->disk('public')
                                    ->directory('std'),

                            ])->from('xl'),

                            Fieldset::make()
                                ->schema([
                                    Fieldset::make(__('reg.address'))
                                        ->schema([

                                            TextInput::make('nationaltyno')
                                                ->label(__('reg.nationaltyno'))
                                                ->unique(ignoreRecord: true)
                                                ->maxLength(20)->required(),


                                            TextInput::make('email')->label(__('reg.email'))->email()->required()
                                                ->unique(ignoreRecord: true),
                                            TextInput::make('mobile')->label(__('reg.phone'))->tel()
                                                ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                                            TextInput::make('social_media')->label(__('reg.social_media'))
                                                ->suffixIcon('heroicon-m-ellipsis-horizontal-circle'),
                                            TextInput::make('activites')->label(__('reg.activites')),
                                            TextInput::make('skill')->label(__('reg.skill')),

                                            Select::make('state_code')
                                                ->options(Lt_column::all()
                                                    ->where('grouped', '1003')
                                                    ->pluck('name', 'code'))
                                                ->label(__('reg.state'))->native(),
                                            TextInput::make('city')->label(__('reg.city')),
                                            TextInput::make('localty')->label(__('reg.localty')),
                                            TextInput::make('block')->label(__('reg.block')),
                                            TextInput::make('home_no')->label(__('reg.homeno')),
                                        ])->columns(4),

                                    Fieldset::make('parent')->label(__('reg.guardian'))
                                        ->schema([
                                            TextInput::make('parent')->label(__('reg.guardian_name')),
                                            Toggle::make('parent_alive')->inline(false)->label(__('reg.father_alive')),
                                            TextInput::make('parent_job')->label(__('reg.parent_job')),
                                            TextInput::make('parent_address')->label(__('reg.parent_address')),
                                            TextInput::make('parent_contacts')->label(__('reg.parent_contacts')),

                                            TextInput::make('mother')->label(__('reg.mother_name')),
                                            Toggle::make('mother_alive')->inline(false)->label(__('reg.mother_alive')),
                                            TextInput::make('mother_job')->label(__('reg.mother_job')),
                                            TextInput::make('mother_address')->label(__('reg.mother_address')),
                                            TextInput::make('mother_contacts')->label(__('reg.mother_contacts')),
                                        ])->columns(4),



                                ])->columns(2)
                        ]),
                    Tabs\Tab::make(__('reg.fees'))
                        ->schema([
                            Fieldset::make(__('reg.fees_students'))
                                ->schema([
                                    TextInput::make('reg_fees')
                                        ->label(__('reg.regfees'))
                                        ->numeric()
                                        ->required(),

                                    TextInput::make('fees')
                                        ->label(__('reg.fees'))
                                        ->numeric()->required(),

                                    // Select::make('currency')
                                    //     ->options(['SDG', 'USD'])
                                    //     ->label(__('reg.currency'))->default('SDG'),

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
                                        ->label(__('reg.currency'))->default('SDG')
                                        ->required(),

                                    TextInput::make('fees1')
                                        ->label(__('reg.fees1'))
                                        ->numeric()->default(0)->required(),

                                    TextInput::make('fees2')
                                        ->label(__('reg.fees2'))
                                        ->numeric()->default(0)->required(),

                                    TextInput::make('fees3')
                                        ->label(__('reg.fees3'))
                                        ->numeric()->default(0)->required(),


                                ])->columns(3),

                            Fieldset::make(__('reg.nonfees_students'))
                                ->schema([
                                    TextInput::make('fees6')
                                        ->label(__('reg.fees6'))
                                        ->numeric()->default(0)->required(),

                                    TextInput::make('fees7')
                                        ->label(__('reg.fees7'))
                                        ->numeric()->default(0)->required(),

                                    TextInput::make('fees8')
                                        ->label(__('reg.fees8'))
                                        ->numeric()->default(0)->required(),
                                         //installment_code
                                    Select::make('discount_percent')
                                    ->label(__('reg.discount_percent'))
                                    ->native()
                                    ->options(Lt_column::all()
                                        ->where('grouped', '2008')
                                        ->pluck('name', 'code'))
                                        ->columnSpan(2)
                                ])->columns(6),

                            Fieldset::make(__('reg.Installments'))
                                ->schema([
                                    //installment_code
                                    Select::make('installment_code')
                                        ->label(__('reg.Installments'))
                                        ->native()
                                        ->options(Lt_column::all()
                                            ->where('grouped', '2005')
                                            ->pluck('name', 'code')),
                                    TextInput::make('pay1')->label(__('reg.pay1'))->numeric()->required()->default(0)->disabled(),
                                    TextInput::make('pay2')->label(__('reg.pay2'))->numeric()->required()->default(0)->disabled(),
                                    TextInput::make('pay3')->label(__('reg.pay3'))->numeric()->required()->default(0)->disabled(),
                                    TextInput::make('pay4')->label(__('reg.pay4'))->numeric()->required()->default(0)->disabled(),
                                ])->columns(5),
                        ]),
                    Tabs\Tab::make(__('reg.notes'))
                        ->schema([
                            MarkdownEditor::make('note')
                                ->toolbarButtons([
                                    'attachFiles',
                                    'bold',
                                    'h2',
                                    'h3',
                                    'strike',
                                    'underline',
                                    'undo',
                                ])
                        ]),

                ])->columnSpanFull()
        ]);
    }

    public static function table(Table $table): Table
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
                ->label('')
            ,
            TextColumn::make('faculty.name')
            ->searchable(isIndividual: true)
            ->sortable()->toggleable(isToggledHiddenByDefault: false)
            ->label(__('reg.faculty')),
            TextColumn::make('name_ar')->toggleable(isToggledHiddenByDefault: false)
                // ->extraAttributes(['class' => 'bg-gray-200'])
                ->label(__('reg.name_ar'))
                ->searchable(isIndividual: true, isGlobal: true)
                ->sortable(),
            // ->description(fn (Student $record) => $record->batch->name . '-' . $record->level->name),
            TextColumn::make('university_no')->toggleable(isToggledHiddenByDefault: false)
                ->label(__('reg.university_no'))
                ->searchable(isIndividual: true, isGlobal: true)
                ->sortable(),
            TextColumn::make('x_status.name')->toggleable(isToggledHiddenByDefault: false)
            ->label(__('reg.xstatus')),

            TextColumn::make('all_fees')->toggleable(isToggledHiddenByDefault: false)
                ->getStateUsing(fn ($record) => $record->fees . ' | ' . $record->reg_fees)
                ->label(__('reg.all_fees')),
            // ->description(fn (Student $record) => 'fees=' . $record->fees . ' reg=' . $record->reg_fees),
            TextColumn::make('pay')->label(__('reg.payment'))->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('xmod')->label(__('reg.xmod'))->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('faculty.campus.name')->label(__('reg.campus'))->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('stady.name')->label(__('reg.stady')),
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
            Tables\Columns\TextColumn::make('deleted_at')
            ->label(__('reg.deleted_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true),


            /*
             updated_at
            created_at
*/
        ])
        ->filters([
            TrashedFilter::make(),
            SelectFilter::make(__('reg.faculty'))
                ->relationship('faculty', 'name')
                ->attribute('faculty_code')
                ->searchable()->multiple()
                ->preload(),
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
            // SelectFilter::make(__('reg.xstatus'))
            // ->query(fn (Builder $query): Builder => $query->where('grouped', '1007'))
            //     ->searchable()
            //     ->preload(),

            QueryBuilder::make()
                ->constraintPickerColumns(2)
                ->constraints([
                    TextConstraint::make('name_ar')
                        ->label(__('reg.name_ar'))->nullable(),
                    SelectConstraint::make('batch_code')
                        ->label(__('reg.batch'))
                        ->options(Batch::all()
                            ->pluck('name', 'code')),

                    // TernaryFilter::make('gender_code'),
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

                    // SelectConstraint::make('faculty')
                    // ->relationship('faculty', 'name')
                    // ->attribute('faculty_code')->label(__('reg.faculty'))
                    // ->multiple(),
                    // SelectConstraint::make(__('reg.faculty'))
                    // ->relationship('faculty', 'name')
                    // ->attribute('faculty_code')
                    // ->multiple(),

                    DateConstraint::make('created_at'),
                    // RelationshipConstraint::make('categories')
                    //     ->multiple()
                    //     ->selectable(
                    //         IsRelatedToOperator::make()
                    //             ->titleAttribute('name')
                    //             ->searchable()
                    //             ->multiple(),
                    //     ),
                    // NumberConstraint::make('reviewsRating')
                    //     ->relationship('reviews', 'rating')
                    //     ->integer(),
                ])

        ], layout: FiltersLayout::Modal)->filtersFormColumns(3)
        ->emptyStateActions([
            ActionTable::make('create')
                ->label('Create Student')
                ->url(route('filament.admin.resources.reg.students.create'))
                ->icon('heroicon-m-plus')
                ->button(),
        ])
        ->actions([
            ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                RestoreAction::make(),
                ForceDeleteAction::make(),

                // ActivityLogTimelineTableAction::make('Activities'),
            ])
        ], position: ActionsPosition::BeforeCells)
        ->headerActions([
            // Tables\Actions\CreateAction::make(),
            // ExportAction::make()->exporter(StudentExporter::class)
            //     ->action(function () {
            //         Notification::make()
            //             ->title('Export Students')
            //             ->warning()
            //             ->send();
            //     }),
            // ImportAction::make()
            //     ->importer(StudentImporter::class)
            //     ->csvDelimiter(';')
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
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
                                    ->options(fn (Get $get): Collection => Department::query()
                                        ->where('faculty_code', $get('faculty_code'))
                                        ->pluck('name', 'code'))->live()->searchable(),

                                Select::make('special_code')
                                    ->label(__('reg.special'))
                                    ->options(fn (Get $get): Collection => Special::query()
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
                    // ->requiresConfirmation() // This ensures the action will show a confirmation dialog
            // ->confirmationTitle('Are you sure you want to delete these users?') // Confirmation dialog title
            // ->confirmationDescription('This action cannot be undone.') // Confirmation dialog description
            // ->color('danger'), // Button color (optional)
,
                // ExportBulkAction::make()
                //     ->exporter(StudentExporter::class)
                //     ->formats([
                //         ExportFormat::Xlsx,
                //         ExportFormat::Csv,
                //     ])

            ]),

        ]);
    }

    public static function getRelations(): array
    {
        return [
            PaymentRelationManager::class,
            MarksRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'view' => Pages\ViewStudent::route('/{record}/view'),
        ];
    }
}
