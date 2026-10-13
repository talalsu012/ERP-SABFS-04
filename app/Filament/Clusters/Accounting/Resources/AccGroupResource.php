<?php

namespace App\Filament\Clusters\Accounting\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;

use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\Accounting\AccGroup;
use App\Filament\Clusters\Accounting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Accounting\Resources\AccGroupResource\Pages;
use App\Filament\Clusters\Accounting\Resources\AccGroupResource\RelationManagers;

class AccGroupResource extends Resource
{
    protected static ?string $model = AccGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Accounting::class;

    public static function form(Form $form): Form
        {
            return $form
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(100),

                    Forms\Components\Select::make('parent_id')
                        ->label('Parent Group')
                        ->options(AccGroup::all()->pluck('name', 'id'))
                        ->searchable()
                        ->nullable(),

                    Forms\Components\Toggle::make('affects_gross')
                        ->label('Affects Gross Profit')
                        ->default(false),
                ]);
        }


        public static function table(Table $table): Table
        {
            return $table
                ->columns([
                    Tables\Columns\TextColumn::make('name')
                        ->searchable()
                        ->sortable(),

                    Tables\Columns\TextColumn::make('parent.name')
                        ->label('Parent Group')
                        ->sortable(),

                    Tables\Columns\IconColumn::make('affects_gross')
                        ->label('Affects Gross')
                        ->boolean(),

                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ])
                ->filters([
                    Tables\Filters\SelectFilter::make('parent_id')
                        ->label('Parent Group')
                        ->options(AccGroup::all()->pluck('name', 'id')),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccGroups::route('/'),
            'create' => Pages\CreateAccGroup::route('/create'),
            'edit' => Pages\EditAccGroup::route('/{record}/edit'),
        ];
    }
}
