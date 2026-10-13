<?php

namespace App\Filament\Clusters\Results\Resources\Res;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Reg\Level;
use Filament\Tables\Table;

use App\Models\Reg\Faculty;
use App\Models\Reg\Lt_column;
use App\Models\Reg\Department;
use App\Models\Result\Subject;
use Filament\Resources\Resource;
use App\Filament\Clusters\Results;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;

use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Filament\Imports\Result\SubjectImporter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Results\Resources\Res\SubjectResource\Pages;
use App\Filament\Clusters\Results\Resources\Res\SubjectResource\RelationManagers\CoursesRelationManager;


class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $cluster = Results::class;


    protected static ?string $title = 'subjects';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()->orderBy('created_at', 'DESC');
    }

    public static function getNavigationGroup(): string
    {
        return __('main.settings');
    }

    public static function getLabel(): ?string
    {
        return __('res.'.static::$title);
    }

    public static function getPluralLabel(): ?string
    {
        return __('res.'.static::$title);
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__('res.'.static::$title));
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return true;
    }
    public static function form(Form $form): Form
    {
        //CODE name_ar name_en crch exam plan `status` `type` parent_code is_visible
        return $form
            ->schema([

                Fieldset::make(__('res.subject'))
                    ->schema([

                        // CheckBoxList::make('Courses')
                        // ->searchable()->bulkToggleable()
                        //     ->relationship('Courses','name')
                        // ,

                        Forms\Components\TextInput::make('code')->label(__('reg.code'))
                            ->unique(ignoreRecord: true)->maxLength(10)->required(),

                        Forms\Components\TextInput::make('name_ar')
                            ->required()
                            ->label(__('reg.name_ar'))
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('name_en')
                            ->required()
                            ->label(__('reg.name_en'))
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\Toggle::make('is_visible')
                            ->label(__('reg.is_visible'))
                            ->default(true),

                        Forms\Components\Select::make('parent_code')
                            ->label(__('res.parent_subject'))
                            ->relationship('parent', 'name', fn (Builder $query) => $query->where('parent_code', null))
                            ->searchable()
                            ->placeholder('Select parent category')
                            ->columnSpan(2),

                            Select::make('faculty_code')
                            ->label(__('reg.faculty'))->required()
                            ->options(Faculty::get()->pluck('name', 'code'))
                            // ->options(fn () => static::getUserFacultyOptions())
                            ->live()->native()->searchable()
                            ->columnSpan(2),

                        Select::make('department_code')
                           ->label(__('reg.department'))
                            ->options(fn (Get $get): Collection => Department::query()
                                ->where('faculty_code', $get('faculty_code'))
                                ->pluck('name', 'code'))->live()->searchable()
                                ->columnSpan(2),


                                Select::make('level_code')->required()
                                ->label(__('reg.level'))
                                ->options(Level::all()->pluck('name', 'code'))
                                ->searchable()
                                ->preload(),

                                Select::make('semester_id')->required()
                                ->label(__('res.semester'))
                                ->native()
                                ->options([
                                    '1' => __('res.first_semester'),
                                    '2' => __('res.second_semester'),

                                ])
                                ->selectablePlaceholder(false),

                    ])->columns(5),
                Fieldset::make(__('res.ResultExam'))
                    ->schema([

                        TextInput::make('crch')->label(__('res.crch'))
                            ->required()->numeric(),

                        TextInput::make('exam')->label(__('res.exam'))
                            ->required()->numeric(),

                        Select::make('plan')->label(__('res.plan'))
                            ->options(Lt_column::all()
                                ->where('group_slug', 'plan_code')
                                ->pluck('name', 'code'))
                            ->native(false),


                        Select::make('type')->label(__('res.type'))
                            ->options(Lt_column::all()
                                ->where('group_slug', 'type_code')
                                ->pluck('name', 'code'))
                            ->native(false),

                        TextInput::make('status')->label(__('res.status'))
                            ->default(0)->numeric(),

                    ])->columns(5),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('reg.code'))
                    ->searchable(isIndividual: true)
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('name_ar')
                    ->label(__('reg.name_ar'))
                    ->searchable(isIndividual: true, isGlobal: true)
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('name_en')
                    ->label(__('reg.name_en'))
                    ->searchable(isIndividual: true, isGlobal: true)
                    ->sortable(),
                TextColumn::make('parent.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->label(__('res.parent_subject')),
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
                TextColumn::make('crch')->label(__('res.crch'))
                ->searchable()
                  ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                TextColumn::make('exam')->label(__('res.exam'))
                ->toggleable()
                ->sortable()
                ->searchable(),

                TextColumn::make('plans.name')->label(__('res.plan'))
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                TextColumn::make('status')->label(__('res.status'))
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                TextColumn::make('types.name')->label(__('res.type'))
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                IconColumn::make('is_visible')
                ->label(__('reg.is_visible'))
                    ->action(function ($record, $column) {
                        $name = $column->getName();
                        $record->update([
                            $name => !$record->$name
                        ]);
                    })
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([

				TrashedFilter::make(),

                SelectFilter::make('level_code')
                    ->label(__('reg.level'))
                    ->options(Level::all()->pluck('name', 'code'))
                    ->multiple()
                    ->native(false),

                    SelectFilter::make('faculty_code')
                    ->label(__('reg.faculty'))
                    ->options(Faculty::get()->pluck('name', 'code'))
                    ->multiple()
                    ->native(),

                SelectFilter::make('parent')
                    ->label(__('res.parent_subject'))
                    ->relationship('parent', 'name', fn (Builder $query) => $query->where('parent_code', null))
                    ->searchable()->multiple(),

                SelectFilter::make('type')
                    ->label(__('res.type'))
                    ->options(Lt_column::all()
                    ->where('grouped', '9002')
                    ->pluck('name', 'code'))
                    ->native(false),

                SelectFilter::make('plan')
                    ->label(__('res.plan'))
                    ->options(Lt_column::all()
                                ->where('grouped', '9001')
                                ->pluck('name', 'code'))
                            ->native(false),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
					 Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ])
            ], position: ActionsPosition::BeforeCells)
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                ImportAction::make()
                    ->importer(SubjectImporter::class)
                    ->csvDelimiter(';')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            //  CoursesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
}
