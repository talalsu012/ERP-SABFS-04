<?php

namespace App\Filament\Clusters\attendances\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\attendances\Room;
use Filament\Resources\Resource;
use App\Filament\Clusters\Results;
use Filament\Forms\Components\Select;
use App\Filament\Clusters\attendances;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\attendances\Resources\RoomResource\Pages;
use App\Filament\Clusters\attendances\Resources\RoomResource\RelationManagers;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $cluster = attendances::class;
    protected static ?string $cluster = Results::class;
    protected static ?int $navigationSort = 4;
    public static function getNavigationGroup(): string
    {
        return __('Attendance.Attendance');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()->orderBy('created_at', 'DESC');
    }

    public static function getLabel(): ?string
    {
        return __("Attendance.room");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Attendance.room");
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__("Attendance.room"));
    }

    protected static ?string $table = 'att_room';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Attendance.room_name'))
                    ->required()
                    ->maxLength(255),
                    Select::make('campus_code')
                        ->relationship('campus','name')
                        ->preload()
                    ->searchable()->label(__('reg.campus')),

                Forms\Components\TextInput::make('capacity')
                    ->label(__('Attendance.room_capacity'))
                    ->required()
                    ->numeric()
                    ->minValue(1),

                Forms\Components\Select::make('type')
                    ->label(__('Attendance.room_type'))
                    ->options([
                        'theoretical' => 'Theoretical',
                        'practical' => 'Practical',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Attendance.room_name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('campus.name')
                    ->label(__('reg.campus'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('capacity')
                    ->label(__('Attendance.room_capacity'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('Attendance.room_type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'theoretical' => 'info',
                        'practical' => 'success',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('Attendance.room_type'))
                    ->options([
                        'theoretical' => 'Theoretical',
                        'practical' => 'Practical',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            // Add relations if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
