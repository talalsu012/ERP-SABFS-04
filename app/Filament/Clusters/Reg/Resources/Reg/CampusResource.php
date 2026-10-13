<?php

namespace App\Filament\Clusters\Reg\Resources\Reg;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Reg\Campus;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Clusters\Reg;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Reg\Resources\Reg\CampusResource\Pages;
use App\Filament\Clusters\Reg\Resources\Reg\CampusResource\RelationManagers;

class CampusResource extends Resource
{
    protected static ?string $model = Campus::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;
    protected static ?string $cluster = Reg::class;


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()
                ->where('is_visible', '1');
    }

    public static function getNavigationGroup(): string
    {
        return __('main.registration');
    }

    public static function getLabel(): ?string
    {
        return __("reg.campus");
    }

    public static function getPluralLabel(): ?string
    {
        return __("reg.campuses");
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__("reg.campuses"));
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
            TextInput::make('code')->label(__('reg.code'))->numeric()->maxLength(10)
            ->required()->unique(ignoreRecord: true),
            TextInput::make('name')->label(__('reg.name'))->maxLength(150)->required()
            ->unique(ignoreRecord: true)
            ->live(onBlur: true)
            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
            ->columnSpan('full'),
            TextInput::make('slug'),

            Forms\Components\Toggle::make('is_visible')
            ->label(__('reg.is_enabled'))
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                ->numeric()->label(__('reg.code'))
                ->sortable(),
            Tables\Columns\TextColumn::make('name')
            ->label(__('reg.name'))
                ->searchable(),
            Tables\Columns\TextColumn::make('slug')
                ->searchable(),
            Tables\Columns\IconColumn::make('is_visible')
            ->label(__('reg.is_enabled'))
                ->boolean(),
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
            'index' => Pages\ListCampuses::route('/'),
            'create' => Pages\CreateCampus::route('/create'),
            'edit' => Pages\EditCampus::route('/{record}/edit'),
        ];
    }
}
