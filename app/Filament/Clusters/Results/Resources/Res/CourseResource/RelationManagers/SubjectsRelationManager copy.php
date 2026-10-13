<?php

namespace App\Filament\Clusters\Results\Resources\Res\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Reg\Batch;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'subjects';

    protected static ?string $recordTitleAttribute = 'name_ar';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('course_code')
                    ->label(__('res.subject'))
                    ->required()
                    ->preloadRecordSelect()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name_ar')
            //  ->allowDuplicates()
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('reg.code')),
                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('reg.name_en')),
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('reg.name_ar')),
                Tables\Columns\TextColumn::make('crch')
                    ->label(__('res.crch')),
                Tables\Columns\TextColumn::make('exam')
                    ->label(__('res.exam')),
                    Tables\Columns\TextColumn::make('id')
                    ->label('Batch Code'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('batch_code')
                            ->label(__('reg.batch'))
                            ->required()
                            ->options(Batch::query()->pluck('name', 'code'))
                            ->searchable()
                            ->live(),
                    ])
                    ->multiple()


            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->name_ar;
    }
}
