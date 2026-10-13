<?php

namespace App\Filament\Clusters\Reg\Resources\Reg;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use App\Models\Reg\Card;
use Filament\Forms\Form;
use App\Enums\CardStatus;
use Filament\Tables\Table;
use App\Models\Reg\Student;
use Illuminate\Support\Str;
use App\Filament\Clusters\Reg;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Reg\Resources\Reg\CardResource\Pages;
use App\Filament\Clusters\Reg\Resources\Reg\CardResource\RelationManagers;

class CardResource extends Resource
{
    protected static ?string $model = Card::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Reg::class;
    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'reg.card';
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
    //     return __('main.settings');
    // }

    public static function getLabel(): ?string
    {
        return __(static::$title);
    }

    public static function getPluralLabel(): ?string
    {
        return __(static::$title);
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__(static::$title));
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return true;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Fieldset::make('Label')
                    ->schema([
                        Forms\Components\Select::make('university_no')
                            ->label(__('reg.name_ar'))
                            ->required()
                            ->searchable()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (!empty($state)) {

                                    $prefix = 'UNI'; // University prefix
                                    $universityPart = strtoupper(substr($state, 0, 5)); // First 5 chars of university no
                                    $randomPart = Str::upper(Str::random(4)); // 4 random letters
                                    $numericPart = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT); // 4 random digits

                                    $cardNumber = "{$prefix}-{$universityPart}-{$randomPart}-{$numericPart}";

                                    $set('card_number', $cardNumber);
                                }
                            })
                            ->getSearchResultsUsing(fn (string $search): array => Student::where('name_ar', 'like', "%{$search}%")->limit(50)->pluck('name_ar', 'university_no')->toArray())
                            ->getOptionLabelUsing(fn ($value): ?string => Student::find($value)?->name_ar)
                            ->columnSpan(2),

                            TextInput::make('card_number')
                            ->label('Card Number')
                            ->disabled()
                            ->dehydrated()
                            ->live(onBlur: true)
                            ->required(),

                        Radio::make('status')
                            ->label(__('reg.status_code'))
                            ->options(CardStatus::class)
                            ->inline()
                            ->required()
                            ->default('active')
                            ->columnSpan(2),

                    ])
                    ->columns(5),
                Fieldset::make('Label')
                    ->schema([


                        Forms\Components\DatePicker::make('issue_date')
                            ->label(__('reg.issue_date'))
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->locale('ar')
                            ->required(),

                        Forms\Components\DatePicker::make('expiry_date')
                            ->after('issue_date')
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->locale('ar')
                            ->label(__('reg.expiry_date')),
                        Forms\Components\Toggle::make('is_visible')
                            ->label(__('reg.is_visible'))
                            ->inline()
                            ->required(),
                    ])->columns(3),





            ]);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('student.name_ar'),
                ImageEntry::make('student.phpot')
                    ->height(40)
                    ->square()
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->label(__('reg.status_code'))
                    ->badge(),

                Tables\Columns\TextColumn::make('student.name_ar')
                    ->label(__('reg.name_ar'))
                    // ->description(fn (Student $record): string => $record->university_no)
                    ->sortable(),
                Tables\Columns\TextColumn::make('card_number')->label(__('reg.card_number'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('issue_date')->label(__('reg.issue_date'))
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiry_date')->label(__('reg.expiry_date'))
                    ->date()
                    // ->color(fn (Card $record) => $record->expiry_date->isPast() ? 'danger' : 'success')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_visible')->label(__('reg.is_visible'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->label(__('reg.created_at'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->label(__('reg.updated_at'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->successRedirectUrl(route('filament.admin.reg.resources.reg.cards.index')),
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('generate_card')
                    ->icon('heroicon-o-credit-card')
                    ->label(__('reg.card'))
                    ->url(fn (Card $record) => route('student.card', $record))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListCards::route('/'),
            'create' => Pages\CreateCard::route('/create'),
            'edit' => Pages\EditCard::route('/{record}/edit'),
            // 'view' => Pages\ViewCard::route('/{record}/view'),
        ];
    }
}
