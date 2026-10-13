<?php

namespace App\Filament\Resources\Reg\StudentResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Reg\Student;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PaymentRelationManager extends RelationManager
{
    protected static string $relationship = 'Payment';


    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Select::make('university_no')
                ->label(__('reg.name'))->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, Set $set) {
                    if (!empty($state)) {

                        $prefix = 'SABFS'; // University prefix

                        $studentId = $state; // assuming student_id is a field in your form
                        $date = now()->format('Ymd');
                        $prefix = 'VOUCH'; // or make this configurable
                        $serial = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

                        $voucher_no = "{$prefix}-{$date}-{$studentId}-{$serial}";
                        $set('voucher_no', $voucher_no);
                    }
                })
                ->getSearchResultsUsing(fn (string $search): array => Student::where('name_ar', 'like', "%{$search}%")->limit(50)->pluck('name_ar', 'university_no')->toArray())
                ->getOptionLabelUsing(fn ($value): ?string => Student::find($value)?->name_ar)
                ->live()->native()->searchable(),

            Forms\Components\TextInput::make('amount')
                ->label(__('reg.amount'))
                ->required()
                ->numeric(),
            ToggleButtons::make('currency')
                ->label(__('reg.currency'))
                ->inline()
                ->options([
                    'SDG' => 'SDG',
                    'USD' => 'USD',
                    'TRN' => 'TRN',
                ])->default('SDG')
                ->required(),


            Forms\Components\TextInput::make('voucher_no')
                ->unique(ignoreRecord: true)
                ->required()->label(__('reg.voucher_no'))
                ->disabled()
                ->dehydrated(),

            ToggleButtons::make('sys_type')
                ->inline()->label(__('reg.deposit'))
                ->grouped()
                ->options([
                    'REG' => 'REG',
                    'CRT' => 'CRT',
                    'OTH' => 'OTH',
                ])->default('REG')
                ->required(),

            Forms\Components\DatePicker::make('bankdate')
                ->label(__('reg.bankdate'))
                ->default(now())
                ->required(),

            ToggleButtons::make('bank')
                ->inline()->label(__('reg.payment_method'))
                ->grouped()
                ->options([
                    'CASH' => 'CASH',
                    'CHECK' => 'CHECK',
                ])->default('CASH')
                ->required(),

            Forms\Components\TextInput::make('branch')
                ->maxLength(255)->label(__('reg.branch'))
                ->default(null),



        ])->columns(4);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('university_no')
            ->heading(__('reg.payments'))
            // ->description(__('reg.paymentDescription'))
            ->columns([
                // Tables\Columns\TextColumn::make('student.name_ar')
                //     ->label(__('reg.name'))
                //     ->numeric()
                //     ->sortable(),

                    Tables\Columns\TextColumn::make('batch.name')
                    ->label(__('reg.batch'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('level.name')
                    ->label(__('reg.level'))
                    ->sortable(),

                    Tables\Columns\TextColumn::make('amount')
                    ->numeric()->label(__('reg.amount'))
                    ->summarize(Sum::make())
                    ->sortable(),

                Tables\Columns\TextColumn::make('voucher_no')
                    ->label(__('reg.voucher_no'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('bankdate')
                    ->date()->label(__('reg.bankdate'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('bank')
                    ->label(__('reg.bank'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('branch')
                    ->label(__('reg.branch'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency')
                    ->label(__('reg.currency'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('sys_type')
                    ->label(__('reg.deposit'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('reg.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->deferLoading()
            ->striped()
            ->groups([
                Group::make('level_code')
                    ->label(__('reg.level')),
                Group::make('batch')
                    ->label(__('reg.batch')),
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
