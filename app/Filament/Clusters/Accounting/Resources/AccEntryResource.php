<?php

namespace App\Filament\Clusters\Accounting\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;

use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\Accounting\AccEntry;
use App\Filament\Clusters\Accounting;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Accounting\Resources\AccEntryResource\Pages;
use App\Filament\Clusters\Accounting\Resources\AccEntryResource\RelationManagers;

class AccEntryResource extends Resource
{
    protected static ?string $model = AccEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $cluster = Accounting::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('entry_type')
                    ->options([
                        1 => 'Journal Entry',
                        2 => 'Payment',
                        3 => 'Receipt',
                        4 => 'Contra',
                        5 => 'Opening Balance',
                    ])
                    ->required(),

                Forms\Components\DateTimePicker::make('date')
                    ->required()
                    ->default(now()),

                Forms\Components\Textarea::make('narration')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
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
                    ])
                    ->defaultItems(2)
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Entry #')
                    ->sortable(),

                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('entry_type')
                    ->formatStateUsing(fn ($state) => match($state) {
                        1 => 'Journal',
                        2 => 'Payment',
                        3 => 'Receipt',
                        4 => 'Contra',
                        5 => 'Opening',
                        default => 'Unknown',
                    }),

                Tables\Columns\TextColumn::make('dr_total')
                    ->label('Debit Total')
                    ->numeric(decimalPlaces: 2)
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('cr_total')
                    ->label('Credit Total')
                    ->numeric(decimalPlaces: 2)
                    ->alignEnd(),

                Tables\Columns\IconColumn::make('possessed')
                    ->label('Posted')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('entry_type')
                    ->options([
                        1 => 'Journal Entry',
                        2 => 'Payment',
                        3 => 'Receipt',
                        4 => 'Contra',
                        5 => 'Opening Balance',
                    ]),

                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'],
                                fn($q) => $q->whereDate('date', '>=', $data['from']))
                            ->when($data['until'],
                                fn($q) => $q->whereDate('date', '<=', $data['until']));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('post')
                    ->label('Post Entry')
                    ->icon('heroicon-o-check')
                    ->action(function (AccEntry $record) {
                        $record->update(['possessed' => true]);
                        Notification::make()
                            ->title('Entry posted successfully')
                            ->success()
                            ->send();
                    })
                    ->hidden(fn (AccEntry $record) => $record->possessed),
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
            'index' => Pages\ListAccEntries::route('/'),
            'create' => Pages\CreateAccEntry::route('/create'),
            'edit' => Pages\EditAccEntry::route('/{record}/edit'),
        ];
    }
}
