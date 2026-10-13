<?php

namespace App\Filament\Clusters\Reg\Resources\Reg;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\ltColumnCode;
use App\Models\Reg\Lt_column;
use App\Filament\Clusters\Reg;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Reg\Resources\Reg\LtCoulmnResource\Pages;
use App\Filament\Clusters\Reg\Resources\Reg\LtCoulmnResource\RelationManagers;

class LtCoulmnResource extends Resource
{
    protected static ?string $model = Lt_column::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 8;
    protected static ?string $cluster = Reg::class;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

        public static function getEloquentQuery(): Builder
        {
            return static::getModel()::query()->orderBy('created_at', 'DESC');
        }

        public static function getNavigationGroup(): string
        {
            return __('main.registration');
        }

        public static function getLabel(): ?string
        {
            return __("reg.ltcolumn");
        }

        public static function getPluralLabel(): ?string
        {
            return __("reg.ltcolumn");
        }

        public static function getNavigationLabel(): string
        {
            return ucfirst(__("reg.ltcolumn"));
        }

        public static function shouldRegisterNavigation(): bool
        {
            return true;
            // return auth()->user()->can('xdxdx');
        }

        public static function canCreate(): bool
        {
            return true;
        }

        public static function canEdit(Model $record): bool
        {
            return true;
        }

        public static function canDelete(Model $record): bool
        {
            return true;
        }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Group::make()->schema([
                Section::make([
                TextInput::make('code')->label(__('reg.code'))->numeric()
                ->unique(ignoreRecord: true)->numeric()
                ->maxLength(10)->required(),

                Select::make('grouped')
                ->label(__('reg.group'))
                ->required()
                ->options(ltColumnCode::class),


                TextInput::make('name')
                ->label(__('reg.name'))
                ->live(onBlur: true)
                ->afterStateUpdated(function (Set $set, ?string $state) {
                    // Translate Arabic to English
                    $translated = (new GoogleTranslate())
                            ->setSource('ar')
                            ->setTarget('en')
                            ->translate($state);

                    $set('name_en', $translated);

                    // Generate slug
                    //  $set('slug', Str::slug($translated));
                })

                ->maxLength(100)->required(),

                TextInput::make('name_en')
                 ->label(__('reg.name_en'))
                    ->maxLength(100),



                TextInput::make('description')
                   ->label(__('reg.description'))
                   ->maxLength(100)
                   ->columnSpan('full'),
                ])->columns(2)
            ])->columnSpan(['lg' => 2]),
            Group::make()->schema([
                Section::make([
                    Placeholder::make('created_at')->label(__('reg.created_at'))
                    ->content(fn (Lt_column $record): ?string => $record->created_at?->diffForHumans())
                    ->hidden(fn (?Lt_column $record) => $record === null),
                    Placeholder::make('updated_at')->label(__('reg.updated_at'))
                    ->content(fn (Lt_column $record): ?string => $record->updated_at?->diffForHumans())
                    ->hidden(fn (?Lt_column $record) => $record === null),
                ])
            ]),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('code')->label(__('reg.code'))->searchable()->sortable(),
            TextColumn::make('name')->label(__('reg.name'))->searchable()->sortable(),
            TextColumn::make('name_en')->label(__('reg.name_en'))->searchable()->sortable(),
            TextColumn::make('grouped')->label(__('reg.group'))->searchable()->sortable(),
            TextColumn::make('group_slug')->label(__('reg.type'))->searchable()->sortable(),
            IconColumn::make('is_visible')->label(__('reg.enabled'))->searchable()->sortable(),
            TextColumn::make('created_at')->label(__('reg.created_at')),
        ])
        ->filters([
            Filter::make('created_at')
            ->form([
                DatePicker::make('created_from')->native(false)->label(__('reg.creation_date_from')),
                DatePicker::make('created_until')->native(false)->label(__('reg.creation_date_to'))->default(now()),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when($data['created_from'],
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                    )
                    ->when($data['created_until'],
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                    );
            }),
        ])
        ->actions([
            Tables\Actions\EditAction::make()->iconButton(),
            Tables\Actions\DeleteAction::make()->iconButton(),
        ])
        ->bulkActions([]);
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
            'index' => Pages\ListLtCoulmns::route('/'),
            'create' => Pages\CreateLtCoulmn::route('/create'),
            'edit' => Pages\EditLtCoulmn::route('/{record}/edit'),
        ];
    }
}
