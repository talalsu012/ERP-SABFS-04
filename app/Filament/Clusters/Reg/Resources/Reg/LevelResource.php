<?php

namespace App\Filament\Clusters\Reg\Resources\Reg;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Reg\Level;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Clusters\Reg;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Reg\Resources\Reg\LevelResource\Pages;
use App\Filament\Clusters\Reg\Resources\Reg\LevelResource\Pages\EditLevel;
use App\Filament\Clusters\Reg\Resources\Reg\LevelResource\Pages\ListLevels;
use App\Filament\Clusters\Reg\Resources\Reg\LevelResource\RelationManagers;
use App\Filament\Clusters\Reg\Resources\Reg\LevelResource\Pages\CreateLevel;

class LevelResource extends Resource
{
    protected static ?string $model = Level::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Reg::class;

    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()
                ->where('is_visible', '1');
    }

    public static function getNavigationGroup(): string
    {
        return __('main.registration');
    }

    public static function getLabel(): ?string
    {
        return __("reg.level");
    }

    public static function getPluralLabel(): ?string
    {
        return __("reg.levels");
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__("reg.levels"));
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
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
                    TextInput::make('code')->label(__('reg.code'))->numeric()->maxLength(10)
                    ->required()->unique(ignoreRecord: true),
                    TextInput::make('name')->label(__('reg.name'))->maxLength(150)->required()
                    ->unique(ignoreRecord: true)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->columnSpan('full'),
                    TextInput::make('name_en')->label(__('reg.name_en'))->maxLength(150)->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpan('full'),
                    TextInput::make('slug'),
                    Forms\Components\Toggle::make('is_visible')
                    ->label(__('reg.is_enabled'))
                        ->required(),
                ])->columns(2)
            ])->columnSpan(['lg' => 2]),
            Group::make()->schema([
                Section::make([
                    Placeholder::make('created_at')->label(__('reg.created_at'))
                    ->content(fn (Level $record): ?string => $record->created_at?->diffForHumans())
                    ->hidden(fn (?Level $record) => $record === null),
                    Placeholder::make('updated_at')->label(__('reg.updated_at'))
                    ->content(fn (Level $record): ?string => $record->updated_at?->diffForHumans())
                    ->hidden(fn (?Level $record) => $record === null),
                ])
            ]),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('code')->label(__('reg.code'))->searchable(),
            TextColumn::make('name')->label(__('reg.name'))->searchable(),
            TextColumn::make('name_en')->label(__('reg.name'))->searchable(),
            IconColumn::make('is_visible')
            ->label(__('reg.is_enabled'))
                ->boolean(),
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
            'index' => Pages\ListLevels::route('/'),
            'create' => Pages\CreateLevel::route('/create'),
            'edit' => Pages\EditLevel::route('/{record}/edit'),
        ];
    }
}
