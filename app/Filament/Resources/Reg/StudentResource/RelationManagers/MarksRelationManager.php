<?php

namespace App\Filament\Resources\Reg\StudentResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class MarksRelationManager extends RelationManager
{
    protected static string $relationship = 'marks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('university_no')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('university_no')
            ->columns([
                TextColumn::make('course.name')
                    ->label(__('res.course'))
                    ->width('12%')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('level.name')
                    ->label(__('reg.level'))
                    ->width('12%')
                    ->searchable()
                    ->sortable(),
                // TextColumn::make('name_ar')->label(__('reg.name_ar'))
                //     // ->searchable()
                //     ->sortable()
                //     ->width('25%'),
                TextColumn::make('Subject.name_ar')->label(__('res.subject'))
                    ->searchable()
                    ->sortable()
                    ->width('12%'),
                TextColumn::make('Subject.crch')->label(__('res.crch'))
                    ->searchable()
                    ->sortable()
                    ->width('12%'),
                    TextColumn::make('mark')->label(__('res.exam'))
                    ->width('5%'),
                TextColumn::make('point')->width('5%')->label(__('res.point')),
                TextColumn::make('grade')->label(__('res.grade'))
                    ->searchable()
                    ->sortable()
                    ->width('10%'),
                TextColumn::make('status')
                ->width('5%')->label(__('res.status')),
                TextColumn::make('reason')
                    ->label(__('res.reason'))->width('20%'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
