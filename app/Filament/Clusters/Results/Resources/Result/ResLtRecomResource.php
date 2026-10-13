<?php

namespace App\Filament\Clusters\Results\Resources\Result;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use App\Filament\Clusters\Results;
use App\Models\Result\ResLtRecoms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Results\Resources\Result\ResLtRecomResource\Pages;
use App\Filament\Clusters\Results\Resources\Result\ResLtRecomResource\RelationManagers;

class ResLtRecomResource extends Resource
{
    protected static ?string $model = ResLtRecoms::class;
    protected static ?string $cluster = Results::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag'; // Choose a relevant icon

    protected static ?string $title = 'res.ResLtRecomsSettings';
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
        return __(static::$title);
    }

    public static function getPluralLabel(): ?string
    {
        return __(static::$title);
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__(static::$title));
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
                Forms\Components\TextInput::make('code')
                    ->label(__('reg.code'))
                    ->required()
                    ->numeric() // Ensure it's treated as a number
                    ->unique(ignoreRecord: true) // Ensure uniqueness, ignore current record on edit
                    ->placeholder('e.g., 1, 20, 101')
                    ->columnSpan(1), // Take half width

                Forms\Components\TextInput::make('name')
                    ->label(__('reg.name') . ' (Arabic)')
                    ->required()
                    ->maxLength(100)
                    ->unique(ignoreRecord: true, table: ResLtRecoms::class, column: 'name') // Unique Arabic name
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null)
                    ->columnSpan(1),

                Forms\Components\TextInput::make('name_en')
                    ->label(__('reg.name') . ' (English)')
                    ->maxLength(100)
                    ->nullable()
                    ->unique(ignoreRecord: true, table: ResLtRecoms::class, column: 'name_en') // Unique English name
                    ->columnSpan(1),

                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->maxLength(255)
                    ->unique(ResLtRecoms::class, 'slug', ignoreRecord: true),


                Forms\Components\Textarea::make('description')
                    ->label(__('reg.description') . ' (Optional)')
                    ->maxLength(500)
                    ->nullable()
                    , // Make it span full width

                Forms\Components\Toggle::make('is_visible')
                    ->label(__('reg.is_visible'))
                    ->default(true)
                    ,
            ])->columns(3); // Arrange fields in two columns
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name (Arabic)')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name_en')
                    ->label('Name (English)')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visible')
                    ->boolean()
                    ->sortable(),

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
                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->placeholder('All')
                    ->trueLabel('Visible')
                    ->falseLabel('Hidden')
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListResLtRecoms::route('/'),
            'create' => Pages\CreateResLtRecom::route('/create'),
            'edit' => Pages\EditResLtRecom::route('/{record}/edit'),
        ];
    }
}
