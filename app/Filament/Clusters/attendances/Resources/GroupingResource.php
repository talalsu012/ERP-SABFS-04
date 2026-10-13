<?php

namespace App\Filament\Clusters\attendances\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;

use Filament\Tables\Table;
use App\Models\Reg\Student;
use App\Models\attendances\Room;
use Filament\Resources\Resource;
use App\Models\attendances\Grouping;
use App\Filament\Clusters\Results;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\attendances\Resources\GroupingResource\Pages;
use App\Filament\Clusters\attendances\Resources\GroupingResource\RelationManagers;
use App\Filament\Clusters\attendances\Resources\GroupingResource\RelationManagers\StudentRelationManager;

class GroupingResource extends Resource
{
    protected static ?string $model = Grouping::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $table = 'att_grouping';
    protected static ?int $navigationSort = 3;
    // protected static ?string $cluster = attendances::class;
    protected static ?string $cluster = Results::class;
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
        return __("Attendance.grouping");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Attendance.grouping");
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__("Attendance.grouping"));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Attendance.name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('parent_id')
                    ->label(__('Attendance.parent'))
                    ->options(Grouping::whereNull('parent_id')->pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),


                Forms\Components\Textarea::make('note')
                    ->label(__('Attendance.note'))
                    ->columnSpanFull()
                    ->nullable(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->groups([
            'parent.name',
        ])
            ->defaultGroup('parent.name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label(__('Attendance.name'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.name')
                ->label(__('Attendance.parent'))
                    ->searchable()
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
                Tables\Filters\SelectFilter::make('parent_id')
                ->label(__('Attendance.parent'))
                    ->options(Grouping::all()->pluck('name', 'id'))
                    ->searchable(),
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
            StudentRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroupings::route('/'),
            'create' => Pages\CreateGrouping::route('/create'),
            'edit' => Pages\EditGrouping::route('/{record}/edit'),
        ];
    }
}
