<?php

namespace App\Filament\Clusters\Results\Resources\Result;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\Result\ResResons;
use Filament\Resources\Resource;
use App\Filament\Clusters\Results;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Results\Resources\Result\ResResonsResource\Pages;
use App\Filament\Clusters\Results\Resources\Result\ResResonsResource\RelationManagers;

class ResResonsResource extends Resource
{
    protected static ?string $model = ResResons::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Results::class;
    protected static ?string $title = 'res.ResResons';
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
                    ->numeric()
                    ->unique(ignoreRecord: true) // Ensure uniqueness, ignore current record on edit
                    ->placeholder('e.g., 1, 10, 100')
                    ->columnSpan(1), // Take half width in a 2-column layout

                Forms\Components\TextInput::make('name')
                    ->label(__('reg.name') . ' (Arabic)')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('e.g., السبب الأول, السبب الثاني') // Placeholder for Arabic name
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null)
                    ->unique(ignoreRecord: true, table: ResResons::class, column: 'name') // Unique Arabic name
                    ->columnSpan(1),

                Forms\Components\TextInput::make('name_en')
                    ->label(__('reg.name') . ' (English)') // Label for English name
                    ->maxLength(100)
                    ->nullable()
                    ->unique(ignoreRecord: true, table: ResResons::class, column: 'name_en') // Unique English name
                    ->columnSpan(1),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->maxLength(50)
                    ->disabled()
                    ->dehydrated()
                    ->helperText('A URL-friendly version of the name, usually lowercase with hyphens.')
                    ->columnSpan(1),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->maxLength(500)
                    ->nullable()
                    ->columnSpanFull(), // Make it span full width

                    Forms\Components\Toggle::make('is_visible')
                    ->label(__('reg.is_visible'))
                    ->default(true)
                    ,
            ])->columns(3);
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
            'index' => Pages\ListResResons::route('/'),
            'create' => Pages\CreateResResons::route('/create'),
            'edit' => Pages\EditResResons::route('/{record}/edit'),
        ];
    }
}
