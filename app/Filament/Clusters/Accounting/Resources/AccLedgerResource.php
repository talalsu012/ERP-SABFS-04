<?php

namespace App\Filament\Clusters\Accounting\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;

use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\Accounting\AccGroup;
use App\Models\Accounting\AccLedger;
use App\Filament\Clusters\Accounting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Accounting\Resources\AccLedgerResource\Pages;
use App\Filament\Clusters\Accounting\Resources\AccLedgerResource\RelationManagers;

class AccLedgerResource extends Resource
{
    protected static ?string $model = AccLedger::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Accounting::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100),

                Forms\Components\Select::make('group_id')
                    ->label('Group')
                    ->options(AccGroup::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('op_balance')
                    ->label('Opening Balance')
                    ->numeric()
                    ->default(0),

                Forms\Components\Select::make('op_balance_dc')
                    ->label('Balance Type')
                    ->options([
                        'D' => 'Debit',
                        'C' => 'Credit',
                    ])
                    ->default('D'),

                Forms\Components\Toggle::make('reconciliation')
                    ->label('Requires Reconciliation')
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

                Tables\Columns\TextColumn::make('group.name')
                    ->label('Group')
                    ->sortable(),

                Tables\Columns\TextColumn::make('op_balance')
                    ->label('Opening Balance')
                    ->formatStateUsing(fn ($state, AccLedger $record) =>
                        ($record->op_balance_dc === 'D' ? '' : '-') . number_format($state, 2)
                    )
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('op_balance_dc')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => $state === 'D' ? 'Debit' : 'Credit'),

                Tables\Columns\IconColumn::make('reconciliation')
                    ->label('Reconciliation')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group_id')
                    ->label('Group')
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
            'index' => Pages\ListAccLedgers::route('/'),
            'create' => Pages\CreateAccLedger::route('/create'),
            'edit' => Pages\EditAccLedger::route('/{record}/edit'),
        ];
    }
}
