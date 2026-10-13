<?php

namespace App\Filament\Clusters\Results\Resources\Result;

use App\Filament\Clusters\Results;
use App\Filament\Clusters\Results\Resources\Result\RecomResource\Pages;
use App\Filament\Clusters\Results\Resources\Result\RecomResource\RelationManagers;
use App\Models\Result\Recom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecomResource extends Resource
{
    protected static ?string $model = Recom::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Results::class;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('university_no')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('faculty_code')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('level_code')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('batch_code')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('semester')
                    ->required(),
                Forms\Components\TextInput::make('examType')
                    ->required(),
                Forms\Components\TextInput::make('countSubject')
                    ->numeric(),
                Forms\Components\TextInput::make('sumMarks')
                    ->numeric(),
                Forms\Components\TextInput::make('s_pass')
                    ->numeric(),
                Forms\Components\TextInput::make('s_fail')
                    ->numeric(),
                Forms\Components\TextInput::make('s_abs')
                    ->numeric(),
                Forms\Components\TextInput::make('crch')
                    ->numeric(),
                Forms\Components\TextInput::make('points')
                    ->numeric(),
                Forms\Components\TextInput::make('gpa')
                    ->numeric(),
                Forms\Components\TextInput::make('cgpa')
                    ->numeric(),
                Forms\Components\TextInput::make('recom_code')
                    ->numeric(),
                Forms\Components\TextInput::make('note')
                    ->maxLength(250),
                Forms\Components\Toggle::make('is_visible')
                    ->required(),
                Forms\Components\Toggle::make('is_locked')
                    ->required(),
                Forms\Components\Toggle::make('is_released')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('university_no')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('faculty_code')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level_code')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch_code')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester'),
                Tables\Columns\TextColumn::make('examType'),
                Tables\Columns\TextColumn::make('countSubject')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sumMarks')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('s_pass')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('s_fail')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('s_abs')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('crch')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('points')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gpa')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cgpa')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('recom_code')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_locked')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_released')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            'index' => Pages\ListRecoms::route('/'),
            'create' => Pages\CreateRecom::route('/create'),
            'edit' => Pages\EditRecom::route('/{record}/edit'),
        ];
    }
}
