<?php

namespace App\Filament\Clusters\Results\Resources\Result;

use App\Enums\Semester;
use App\Enums\AbsStatus;
use App\Models\Result\ResRecom;
use App\Models\Result\ResLtRecoms;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Clusters\Results;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Results\Resources\Result\ResRecomResource\Pages;
use App\Filament\Clusters\Results\Resources\Result\ResRecomResource\RelationManagers;

class ResRecomResource extends Resource
{
    protected static ?string $model = ResRecom::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $cluster = Results::class;
    protected static ?string $title = 'ResLtRecoms';
    protected static ?string $navigationLabel = 'Recommendations';
    protected static ?string $pluralModelLabel = 'Recommendations';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationGroup(): string
    {
        return __('main.exam_entry');
    }

    public static function getLabel(): ?string
    {
        return __('res.' . static::$title);
    }

    public static function getPluralLabel(): ?string
    {
        return __('res.' . static::$title);
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__('res.' . static::$title));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('reg.basic_info'))
                    ->schema([
                        Forms\Components\Grid::make(4)
                            ->schema([
                                // Column 1: Student Information

                                Forms\Components\Select::make('university_no')
                                    ->label(__('reg.university_no'))
                                    ->relationship('student', 'name_ar')
                                    ->required()
                                    ->searchable(),

                                Forms\Components\Select::make('faculty_code')
                                    ->label(__('reg.faculty'))
                                    ->relationship('faculty', 'name')
                                    ->required()
                                    ->preload()
                                    ->searchable(),

                                Forms\Components\Select::make('level_code')
                                    ->label(__('reg.level'))
                                    ->relationship('level', 'name')
                                    ->required()
                                    ->preload()
                                    ->searchable(),

                                Forms\Components\Select::make('batch_code')
                                    ->label(__('reg.batch'))
                                    ->relationship('batch', 'code')
                                    ->required()
                                    ->preload()
                                    ->searchable(),


                            ]),
                    ]),

                Forms\Components\Section::make(__('res.ResLtRecoms'))
                    ->schema([
                        Forms\Components\Grid::make(5)
                            ->schema([
                                // Column 1: Status
                                Forms\Components\Select::make('recom_code')
                                    ->label(__('res.ResLtRecoms'))
                                    ->options(ResLtRecoms::all()->pluck('name', 'code'))
                                    ->preload()
                                    ->required(),

                                Forms\Components\Select::make('semester')
                                    ->label(__('res.semester'))
                                    ->options(Semester::class)
                                    ->preload()
                                    ->required(),

                                Forms\Components\Select::make('examType')
                                    ->label(__('res.absent'))
                                    ->options(AbsStatus::class)
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('gpa')
                                    ->label(__('res.gpa'))
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('points')
                                    ->label(__('res.point'))
                                    ->numeric()
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('university_no')
                    ->label(__('reg.university_no'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.name_ar')
                    ->label(__('reg.name_ar'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),

                Tables\Columns\TextColumn::make('faculty.name')
                    ->label(__('reg.faculty'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),

                Tables\Columns\TextColumn::make('level.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('reg.level')),

                Tables\Columns\TextColumn::make('semester')
                    ->label(__('res.semester'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->badge(),
                Tables\Columns\TextColumn::make('semester')
                    ->label(__('res.semester'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->badge()
                    ->icon(fn(Semester $state): string => $state->getIcon())
                    ->color(fn(Semester $state): string => $state->getColor())
                    ->formatStateUsing(fn(Semester $state): string => $state->getLabel()),

                Tables\Columns\TextColumn::make('batch.name')
                    ->label(__('reg.batch'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('examType')
                    ->label(__('res.absent'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->badge()
                    ->icon(fn(AbsStatus $state): string => $state->getIcon())
                    ->color(fn(AbsStatus $state): string => $state->getColor())
                    ->formatStateUsing(fn(AbsStatus $state): string => $state->getLabel()),


                Tables\Columns\TextColumn::make('gpa')
                    ->label(__('res.gpa'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('recom_code')
                    ->label(__('res.ResLtRecoms'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(fn($state) => ResLtRecoms::where('code', $state)->first()?->name),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\SelectFilter::make('faculty_code')
                    ->relationship('faculty', 'name')
                    ->label(__('reg.faculty')),

                Tables\Filters\SelectFilter::make('level_code')
                    ->relationship('level', 'name')
                    ->label(__('reg.level')),

                Tables\Filters\SelectFilter::make('semester')
                    ->options(Semester::class)
                    ->label(__('res.semester')),

                Tables\Filters\SelectFilter::make('examType')
                    ->options(AbsStatus::class)
                    ->label(__('res.absent')),
                Tables\Filters\SelectFilter::make('recom_code')
                    ->options(ResLtRecoms::all()->pluck('name', 'code'))
                    ->label(__('res.ResLtRecoms')),
                Tables\Filters\Filter::make('gpa')
                    ->form([
                        Forms\Components\TextInput::make('gpa_from')
                            ->label('gpa from')
                            ->numeric(),
                        Forms\Components\TextInput::make('gpa_to')
                            ->label('gpa to')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['gpa_from'] !== null) {
                            $query->where('gpa', '>=', $data['gpa_from']);
                        }
                        if ($data['gpa_to'] !== null) {
                            $query->where('gpa', '<=', $data['gpa_to']);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
              ->bulkActions([
                // إجراءات التحديد المتعدد (حذف، استعادة، حذف قسري)
                    Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\StudentRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResRecoms::route('/'),
            'create' => Pages\CreateResRecom::route('/create'),
            'edit' => Pages\EditResRecom::route('/{record}/edit'),
        ];
    }
}
