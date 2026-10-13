<?php

namespace App\Filament\Clusters\Reg\Resources\Reg;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Reg\Faculty;
use Illuminate\Support\Str;
use App\Filament\Clusters\Reg;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Filament\Components\HelpModal;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Exports\Reg\FacultyExporter;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Reg\Resources\Reg\FacultyResource\Pages;
use App\Filament\Clusters\Reg\Resources\Reg\FacultyResource\RelationManagers;
use App\Filament\Clusters\Reg\Resources\Reg\FacultyResource\Pages\EditFaculty;
use App\Filament\Clusters\Reg\Resources\Reg\FacultyResource\Pages\CreateFaculty;
use App\Filament\Clusters\Reg\Resources\Reg\FacultyResource\Pages\ListFaculties;

class FacultyResource extends Resource
{
    protected static ?string $model = Faculty::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;
    protected static ?string $cluster = Reg::class;


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
        return __("reg.faculties");
    }

    public static function getPluralLabel(): ?string
    {
        return __("reg.faculties");
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__("reg.faculties"));
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
                Select::make('campus_code')->relationship('campus','name')->preload()
                ->searchable()->label(__('reg.campus')),
                TextInput::make('code')->label(__('reg.code'))->numeric()->maxLength(10)
                ->required()->unique(ignoreRecord: true),
                TextInput::make('name')
                  ->label(__('reg.name'))
                  ->maxLength(150)
                  ->required()
                    ->unique(ignoreRecord: true)
                    // ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        // Translate Arabic to English
                        $translated = (new GoogleTranslate())
                                ->setSource('ar')
                                ->setTarget('en')
                                ->translate($state);

                        $set('name_en', $translated);

                        // Generate slug
                         $set('slug', Str::slug($translated));
                    })

                 ->columnSpan('full'),
                TextInput::make('name_en')
                  ->label(__('reg.name_en'))
                  ->maxLength(150)->required()
                  ->unique(ignoreRecord: true)
                  ->columnSpan('full'),



                DatePicker::make('start_reg')->label(__('reg.start_date'))
                ->native(false)
                ->displayFormat('d/m/Y')
                ->required()
                ->placeholder('MM/DD/YYYY'),
                DatePicker::make('end_reg')->label(__('reg.end_date'))
                ->native(false)
                ->displayFormat('d/m/Y')
                ->required()
                ->placeholder('MM/DD/YYYY'),

                ])
                ->columns(3)
            ])->columnSpan(['lg' => 2]),
            Group::make()->schema([
                Section::make([
                    Placeholder::make('created_at')->label(__('reg.created_at'))
                    ->content(fn (Faculty $record): ?string => $record->created_at?->diffForHumans())
                    ->hidden(fn (?Faculty $record) => $record === null),
                    Placeholder::make('updated_at')->label(__('reg.updated_at'))
                    ->content(fn (Faculty $record): ?string => $record->updated_at?->diffForHumans())
                    ->hidden(fn (?Faculty $record) => $record === null),

                    Toggle::make('is_visible')->label(__('reg.is_enabled')),
                    Toggle::make('results_published')
                    ->inline()
                    ->label(__('reg.results_published')),
                    TextInput::make('slug')
                    ->readOnly()
                    ->columnSpan('full'),
                ])->columns(2)
            ]),
        ])->columns(3);
    }


    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('code')->label(__('reg.code'))->searchable(),
            TextColumn::make('name')->label(__('reg.name'))->searchable(),
            TextColumn::make('campus.name')->label(__('reg.campus')),
            ToggleColumn::make('is_visible')->label(__('reg.is_enabled')),
            ToggleColumn::make('results_published')->label(__('reg.results_published')),
            TextColumn::make('start_reg')->label(__('reg.start_date')),
            TextColumn::make('end_reg')->label(__('reg.end_date')),


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
            Tables\Actions\ViewAction::make()->iconButton(),
            Tables\Actions\EditAction::make()->iconButton(),
            Tables\Actions\DeleteAction::make()->iconButton(),
        ])
        ->headerActions([
            ExportAction::make()
                ->exporter(FacultyExporter::class)
        ])
        ->bulkActions([


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
            'index' => Pages\ListFaculties::route('/'),
            'create' => Pages\CreateFaculty::route('/create'),
            'edit' => Pages\EditFaculty::route('/{record}/edit'),
        ];
    }
}
