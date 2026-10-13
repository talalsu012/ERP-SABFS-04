<?php

namespace App\Filament\Clusters\attendances\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Reg\Lt_column;
use Filament\Resources\Resource;
use App\Models\attendances\Staff;
use App\Filament\Clusters\Results;
use Filament\Forms\Components\Radio;
use App\Filament\Clusters\attendances;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\attendances\Resources\StaffResource\Pages;
use App\Filament\Clusters\attendances\Resources\StaffResource\RelationManagers;
use App\Filament\Clusters\attendances\Resources\StaffResource\RelationManagers\SubjectsRelationManager;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $cluster = attendances::class;
    protected static ?string $cluster = Results::class;

    protected static ?int $navigationSort = 5;
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

    // public static function getNavigationGroup(): string
    // {
    //     return __('Attendance.staff');
    // }

    public static function getLabel(): ?string
    {
        return __("Attendance.staff");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Attendance.staff");
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__("Attendance.staff"));
    }

    protected static ?string $table = 'att_staff';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('reg.name_ar'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('degree')
                    ->label(__('Attendance.degree'))
                    ->datalist([
                        'استاذ',
                        'استاذ مساعد',
                        'استاذ مشارك',
                        'محاضر',
                        'مساعد تدريس',
                        'تقني',
                    ])
                    ->required()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('date_degree')
                    ->label(__('Attendance.date_degree'))
                    ->required(),

                Forms\Components\TextInput::make('specialization')
                    ->label(__('Attendance.specialization'))
                    ->required()
                    ->maxLength(255),

                Radio::make('appointment')
                    ->label(__('Attendance.appointment'))
                    ->required()
                    ->inline()
                    ->options([
                        'basic' => 'Basic',
                        'temp' => 'Temporary'
                    ]),

                    Radio::make('gender_code')->inline()
                    ->options(Lt_column::all()
                        ->where('grouped', '1001')
                        ->pluck('name', 'code'))
                    ->required()
                    ->label(__('reg.gender')),

                    Forms\Components\TextInput::make('crch_load')
                    ->label(__('Crch_load'))
                    ->required()
                    ->maxLength(2),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('reg.name_ar'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('degree')
                    ->label(__('Attendance.degree'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('specialization')
                    ->label(__('Attendance.specialization'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('appointment')
                    ->label(__('Attendance.appointment'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'basic' => 'success',
                        'temp' => 'warning',
                    }),

                Tables\Columns\TextColumn::make('gender_code')
                    ->label(__('reg.gender'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '10010001' => 'primary',
                        '10010002' => 'pink',
                    })
                    ,
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('appointment')
                    ->label(__('Attendance.appointment'))
                    ->options([
                        'basic' => 'Basic',
                        'temp' => 'Temporary',
                    ]),

                Tables\Filters\SelectFilter::make('gender')
                    ->label(__('reg.gender'))
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
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
             SubjectsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
        ];
    }
}
