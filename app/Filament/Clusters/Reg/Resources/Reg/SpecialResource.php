<?php

namespace App\Filament\Clusters\Reg\Resources\Reg;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Reg\Special;
use Illuminate\Support\Str;
use App\Filament\Clusters\Reg;
use Illuminate\Validation\Rule;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Reg\Resources\Reg\SpecialResource\Pages;
use App\Filament\Clusters\Reg\Resources\Reg\SpecialResource\RelationManagers;
use App\Filament\Clusters\Reg\Resources\Reg\SpecialResource\Pages\EditSpecial;
use App\Filament\Clusters\Reg\Resources\Reg\SpecialResource\Pages\ListSpecials;
use App\Filament\Clusters\Reg\Resources\Reg\SpecialResource\Pages\CreateSpecial;

class SpecialResource extends Resource
{
    protected static ?string $model = Special::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Reg::class;

    protected static ?int $navigationSort = 4;


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()
                ->where('is_visible', '>=' , '0');
    }

    public static function getNavigationGroup(): string
    {
        return __('main.registration');
    }

    public static function getLabel(): ?string
    {
        return __("reg.special");
    }

    public static function getPluralLabel(): ?string
    {
        return __("reg.specials");
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__("reg.specials"));
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
                Select::make('department_code')->relationship('department','name')->preload()
                ->searchable()->label(__('reg.department')),
                TextInput::make('code')->label(__('reg.code'))->numeric()->maxLength(10)
                ->required()->unique(ignoreRecord: true),
                TextInput::make('name')->label(__('reg.name'))->maxLength(150)->required()
                ->unique(ignoreRecord: true) // Filament built-in method
                // ->rules([
                //     Rule::unique('special', 'name'),
                // ])
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                ->columnSpan('full'),

                TextInput::make('name_en')->label(__('reg.name_en'))->maxLength(150)->required()
                ->unique(ignoreRecord: true)
                ->columnSpan('full'),
                TextInput::make('slug'),
                ])->columns(2)
            ])->columnSpan(['lg' => 2]),
            Group::make()->schema([
                Section::make([
                    Placeholder::make('created_at')->label(__('reg.created_at'))
                    ->content(fn (Special $record): ?string => $record->created_at?->diffForHumans())
                    ->hidden(fn (?Special $record) => $record === null),
                    Placeholder::make('updated_at')->label(__('reg.updated_at'))
                    ->content(fn (Special $record): ?string => $record->updated_at?->diffForHumans())
                    ->hidden(fn (?Special $record) => $record === null),
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
            TextColumn::make('department.name')->label(__('reg.department')),
            ToggleColumn::make('is_visible')->label(__('reg.is_enabled')),
            ToggleColumn::make('results_published')->label(__('reg.published')),
            TextColumn::make('created_at')->label(__('reg.created_at')),
            TextColumn::make('name_en')->label(__('reg.name_en'))->searchable(),
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
            // ImportAction::make()
            //     ->importer(DepartmentImporter::class),

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
            'index' => Pages\ListSpecials::route('/'),
            'create' => Pages\CreateSpecial::route('/create'),
            'edit' => Pages\EditSpecial::route('/{record}/edit'),
        ];
    }
}
