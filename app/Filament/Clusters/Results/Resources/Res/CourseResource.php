<?php

namespace App\Filament\Clusters\Results\Resources\Res;

use Filament\Forms;
use Filament\Tables;
use App\Enums\AbsStatus;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Reg\Student;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use App\Models\Result\Course;
use Filament\Resources\Resource;
use App\Filament\Clusters\Results;
use Illuminate\Support\Facades\URL;
use App\Models\Result\CourseSubject;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Results\Resources\Res\CourseResource\Pages;
use App\Filament\Clusters\Results\Resources\Res\CourseResource\Pages\EditCourse;
use App\Filament\Clusters\Results\Resources\Res\CourseResource\RelationManagers;
use App\Filament\Clusters\Results\Resources\Res\CourseResource\Pages\ListCourses;
use App\Filament\Clusters\Results\Resources\Res\CourseResource\Pages\CreateCourse;
use App\Filament\Clusters\Results\Resources\Res\CourseResource\RelationManagers\SubjectsRelationManager;


class CourseResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static ?string $title = 'courses';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Results::class;

    protected static ?int $navigationSort = 1;


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
        return __('main.settings');
    }

    public static function getLabel(): ?string
    {
        return __('res.'.static::$title);
    }

    public static function getPluralLabel(): ?string
    {
        return __('res.'.static::$title);
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__('res.'.static::$title));
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
                Section::make()->schema([
                    Forms\Components\TextInput::make('code')->label(__('res.code'))
                    ->unique(ignoreRecord: true)
                    ->label(__('res.code'))
                    ->maxLength(20)->required(),

                    Forms\Components\TextInput::make('name')
                    ->required()
                    ->label(__('reg.name_ar'))
                    ->maxLength(255)
                    ->live(onBlur: true)
                    // ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        ,
                    Forms\Components\Select::make('parent_code')
                        ->label(__('res.Parent'))
                        ->relationship('parent', 'name', fn (Builder $query) => $query->where('parent_code', null))
                        ->searchable()
                        ->placeholder('Select parent category'),

                ])->columns(3),

                    Section::make()
                    ->schema([
                        Forms\Components\DatePicker::make('start')->label(__('reg.start_date'))->required(),
                        Forms\Components\DatePicker::make('end')
                        ->label(__('reg.end_date'))
                        ->required(),


                    Forms\Components\Toggle::make('is_visible')
                        ->label('Visible to Course.')
                        ->default(true),

                        Radio::make('status')
                        ->label(__('res.status'))
                        ->options(AbsStatus::class)
                        ->inline()
                        ->required()
                        ->default('regular')
                        // ->columns(2)
                        ,

                // Forms\Components\TextInput::make('slug')
                // ->disabled()
                // ->dehydrated()
                // ->required()
                // ->maxLength(255)
                // ->unique(Course::class, 'slug', ignoreRecord: true),
                    ])->columns(3)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')->label(__('res.status'))->searchable(),
                // TextColumn::make('code')->label(__('res.code'))->searchable(),
                TextColumn::make('name')->label(__('res.name'))->searchable(),
                TextColumn::make('parent.name')->label(__('res.course')),
                TextColumn::make('start')->label(__('reg.start_date')),
                TextColumn::make('end')->label(__('reg.end_date')),
                IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->sortable(),
                    TextColumn::make('code')
                    ->label('Course ID')
                    ->url(function (Course $record) {

                        return route('print.course', [
                            'course' => $record->code,
                        ]);
                    })
                    ->openUrlInNewTab()
                    // ->copyable() // optional: adds copy to clipboard button
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    ViewAction::make(),
                    Action::make('print')
                        ->label('طباعة الدرجات')
                        ->icon('heroicon-o-printer')
                        ->color('success')
                        ->url(fn (Course $record) => route('print.grades', $record->code))
                        ->openUrlInNewTab(),

                ])
                ->label('الإجراءات')
                ->icon('heroicon-s-cog')
                ->color('gray')
                ->dropdownPlacement('bottom-start'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
            ]);
    }


    public static function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label('طباعة الدرجاتNEW')
                ->url(fn (Course $record) => route('print.grades', $record->code))
                ->openUrlInNewTab(),
        ];
    }

    public static function getRelations(): array
    {
        return [
            SubjectsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
