<?php

namespace App\Filament\Clusters\Reg\Resources\Reg;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Clusters\Reg;
use App\Models\Reg\Department;

use App\Traits\HasFacultyScope;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Reg\Resources\Reg\DepartmentResource\Pages;
use App\Filament\Clusters\Reg\Resources\Reg\DepartmentResource\RelationManagers;
use App\Filament\Clusters\Reg\Resources\Reg\DepartmentResource\Pages\EditDepartment;
use App\Filament\Clusters\Reg\Resources\Reg\DepartmentResource\Pages\ListDepartments;
use App\Filament\Clusters\Reg\Resources\Reg\DepartmentResource\Pages\CreateDepartment;

class DepartmentResource extends Resource
{
    use HasFacultyScope;

    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Reg::class;
    protected static ?int $navigationSort = 3;


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
        return __("reg.department");
    }

    public static function getPluralLabel(): ?string
    {
        return __("reg.departments");
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__("reg.departments"));
    }


    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Group::make()->schema([
                    Section::make([
                        Select::make('faculty_code')
                        ->options(fn () => static::getUserFacultyOptions())
                            ->preload()->required()
                            ->visible(fn (): bool => static::shouldShowFacultyFields())
                            ->searchable()->label(__('reg.faculty'))->columnSpan(2),
                        TextInput::make('code')->label(__('reg.code'))->numeric()->maxLength(10)
                            ->required()->unique(ignoreRecord: true),
                        TextInput::make('name')->label(__('reg.name'))->maxLength(150)->required()
                            ->unique(ignoreRecord: true)
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
                            ->maxLength(150)
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpan('full'),

                    ])->columns(3)
                ])->columnSpan(['lg' => 2]),
                Group::make()->schema([
                    Section::make([
                        Placeholder::make('created_at')->label(__('reg.created_at'))
                            ->content(fn (Department $record): ?string => $record->created_at?->diffForHumans())
                            ->hidden(fn (?Department $record) => $record === null),
                        Placeholder::make('updated_at')->label(__('reg.updated_at'))
                            ->content(fn (Department $record): ?string => $record->updated_at?->diffForHumans())
                            ->hidden(fn (?Department $record) => $record === null),

                        Toggle::make('is_visible')->label(__('reg.is_enabled')),
                        Toggle::make('results_published')->inline()->label(__('reg.results_published')),
                        TextInput::make('slug')->columnSpan('full'),
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
                TextColumn::make('faculty.name')->label(__('reg.faculty'))
                ->visible(fn (): bool => static::shouldShowFacultyFields())
                ,
                ToggleColumn::make('is_visible')->label(__('reg.is_enabled')),
                ToggleColumn::make('results_published')->label(__('reg.results_published')),
                TextColumn::make('created_at')->label(__('reg.created_at')),
                TextColumn::make('name_en')->label(__('reg.name_en'))->searchable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                    // SelectFilter::make('faculty_code')
                    // ->label('Faculty')
                    // ->options(fn () => static::getUserFacultyOptions())
                    // ->visible(fn (): bool => static::shouldShowFacultyFields()),
                        DatePicker::make('created_from')->native(false)->label(__('reg.creation_date_from')),
                        DatePicker::make('created_until')->native(false)->label(__('reg.creation_date_to'))->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
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
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }

    protected static function isUserAuthorized(): bool
    {
        $user = Auth::user();
        // Option 1: Check if user has any faculties assigned
        return $user->faculties()->exists();
        // Option 2: Or check for specific role/permission
        // return $user->hasRole('admin') || $user->hasRole('faculty_admin');
    }
}
