<?php

namespace App\Filament\Clusters\Results\Resources\Res;

use Filament\Forms;
use Filament\Tables;

use Filament\Forms\Form;
use App\Enums\PlanStatus;
use Filament\Tables\Table;
use App\Models\Result\Grade;
use App\Models\Reg\Lt_column;
use Filament\Resources\Resource;
use App\Filament\Clusters\Results;
use Filament\Forms\Components\Select;

use Illuminate\Database\Eloquent\Builder;
use App\Filament\Clusters\Results\Resources\Res\gradeResource\Pages;


class gradeResource extends Resource
{
    protected static ?string $model = Grade::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Results::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'grades';

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
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                ->label(__('res.title'))
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('titlear')
                ->label(__('res.titlear'))
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('point')
                ->label(__('res.point'))
                    ->required()->default(0)
                    ,
                Forms\Components\TextInput::make('upper')
                    ->label(__('res.upper'))
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('lower')
                    ->label(__('res.lower'))
                    ->required()
                    ->numeric(),
                Select::make('plan')
                ->label(__('res.plan'))
                ->options(Lt_column::all()
                ->where('grouped', '2006')
                ->pluck('name', 'code'))
                    ->required(),
            ])->columns(5);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('res.title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('titlear')
                    ->label(__('res.titlear'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('point')
                    ->label(__('res.point'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('upper')
                    ->label(__('res.upper'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lower')
                    ->label(__('res.lower'))
                    ->numeric()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('plan')
                //     ->label(__('res.plan'))
                //     // ->enum(PlanStatus::class)
                //     ->sortable()
                //     ->searchable(),
                Tables\Columns\TextColumn::make('stady.name')
                ->label(__('reg.stady'))
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('created_at')

                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' =>  Pages\Listgrades::route('/'),
            'create' => Pages\Creategrade::route('/create'),
            'edit' =>   Pages\Editgrade::route('/{record}/edit'),
        ];
    }
}