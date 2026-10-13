<?php

namespace App\Filament\Clusters\Accounting\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;

use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Clusters\Accounting;
use App\Models\Accounting\AccEntryItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Accounting\Resources\AccEntryItemResource\Pages;
use App\Filament\Clusters\Accounting\Resources\AccEntryItemResource\RelationManagers;

class AccEntryItemResource extends Resource
{
    protected static ?string $model = AccEntryItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $cluster = Accounting::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('entry_id')
                    ->relationship('entry', 'number')
                    ->required(),

                Forms\Components\Select::make('ledger_id')
                    ->relationship('ledger', 'name')
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('dc')
                    ->options([
                        'D' => 'Debit',
                        'C' => 'Credit',
                    ])
                    ->required(),

                Forms\Components\DateTimePicker::make('reconciliation_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('entry.number')
                    ->label('Entry #')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ledger.name')
                    ->label('Ledger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->numeric(decimalPlaces: 2)
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('dc')
                    ->formatStateUsing(fn ($state) => $state === 'D' ? 'Debit' : 'Credit'),

                Tables\Columns\TextColumn::make('reconciliation_date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('entry_id')
                    ->relationship('entry', 'number'),

                Tables\Filters\SelectFilter::make('ledger_id')
                    ->relationship('ledger', 'name'),

                Tables\Filters\SelectFilter::make('dc')
                    ->options([
                        'D' => 'Debit',
                        'C' => 'Credit',
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccEntryItems::route('/'),
            'create' => Pages\CreateAccEntryItem::route('/create'),
            'edit' => Pages\EditAccEntryItem::route('/{record}/edit'),
        ];
    }
}
