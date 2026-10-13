<?php

namespace App\Filament\Clusters\Results\Resources\Academic;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\attendances\Room;
use Filament\Resources\Resource;
use App\Models\attendances\Staff;
use App\Filament\Clusters\Results;
use Filament\Support\Colors\Color;
use App\Models\attendances\Lecture;
use Filament\Tables\Filters\Filter;
use App\Models\attendances\Grouping;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\attendances\GroupingStudent;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\Modal\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Results\Resources\Academic\LectureResource\Pages;
use App\Filament\Clusters\Results\Resources\Academic\LectureResource\RelationManagers;
use App\Filament\Clusters\Results\Resources\Academic\LectureResource\Pages\EditLecture;
use App\Filament\Clusters\Results\Resources\Academic\LectureResource\Pages\ListLectures;
use App\Filament\Clusters\Results\Resources\Academic\LectureResource\Pages\CreateLecture;

class LectureResource extends Resource
{
    protected static ?string $model = Lecture::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Results::class;
    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Attendance.Lecture';

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
        return __('Attendance.Attendance');
    }

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


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Group 1: Basic Information
                Section::make(__(''))
                    // ->description(__('Enter the core attendance details'))
                    // ->icon('heroicon-o-information-circle')
                    ->schema([
                        Group::make()
                            ->schema([


                                Select::make('grouping_id')
                                    ->label(__('Attendance.grouping'))
                                    ->options(
                                        Grouping::all()
                                            ->pluck('name', 'id')
                                    )
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(['md' => 1]),
                                Select::make('room_id')
                                    ->label(__('Attendance.room'))
                                    ->options(function (Get $get) {
                                        if (empty($get('grouping_id'))) {
                                            return [];
                                        }
                                        return GroupingStudent::query()
                                            ->where('grouping_id', $get('grouping_id'))
                                            ->get()
                                            ->pluck('room.name', 'room_id');
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(['md' => 1]),

                                Select::make('staff_id')
                                    ->label(__('Attendance.staff'))
                                    ->relationship('staff', 'name')
                                    ->required()
                                    ->preload()
                                    ->searchable()
                                    ->live() // Add live() to update the subject options when staff changes
                                    ->columnSpan(['md' => 1]),

                                Select::make('subject_code')
                                    ->label(__('res.subject'))
                                    ->options(function (Get $get) {
                                        $staffId = $get('staff_id');
                                        if (!$staffId) {
                                            return [];
                                        }

                                        $staff = Staff::with('subjects')->find($staffId);
                                        return $staff->subjects->pluck('name_ar', 'code');
                                    })
                                    ->required()
                                    ->searchable()
                                    ->columnSpan(['md' => 1]),

                            ])
                            ->columns(4),
                    ]),

                // Group 2: Location and Timing
                Section::make(__(''))
                    // ->description(__('Specify where and when the attendance occurs'))
                    // ->icon('heroicon-o-clock')
                    ->schema([
                        Group::make()
                            ->schema([])
                            ->columns(2),

                        Group::make()
                            ->schema([
                                Select::make('day_of_week')
                                    ->label(__('Attendance.day_of_week'))
                                    ->options([
                                        'Monday' => __('Attendance.days.Monday'),
                                        'Tuesday' => __('Attendance.days.Tuesday'),
                                        'Wednesday' => __('Attendance.days.Wednesday'),
                                        'Thursday' => __('Attendance.days.Thursday'),
                                        'Friday' => __('Attendance.days.Friday'),
                                        'Saturday' => __('Attendance.days.Saturday'),
                                        'Sunday' => __('Attendance.days.Sunday'),
                                    ])
                                    ->required()
                                    ->columnSpan(['md' => 2]),

                                TimePicker::make('start_time')
                                    ->label(__('Attendance.start_time'))
                                    ->required()
                                    ->seconds(false)
                                    ->minutesStep(15)
                                    ->columnSpan(['md' => 1]),

                                TimePicker::make('end_time')
                                    ->label(__('Attendance.end_time'))
                                    ->required()
                                    ->seconds(false)
                                    ->minutesStep(15)
                                    ->columnSpan(['md' => 1]),


                                DatePicker::make('start_date')
                                    ->label(__('Attendance.start_date'))
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->closeOnDateSelection()
                                    ->columnSpan(['md' => 1]),

                                DatePicker::make('end_date')
                                    ->label(__('Attendance.end_date'))
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->closeOnDateSelection()
                                    ->columnSpan(['md' => 1]),
                            ])
                            ->columns(6),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('تقرير الحضور')
                    ->formatStateUsing(fn() => 'عرض التقرير') // Custom text
                    ->url(fn(Lecture $record) => route('report.lecture.attendance', $record))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-document-text') // Document icon
                    ->iconPosition('after') // Icon after text for RTL
                    ->color(Color::Indigo) // Color scheme
                    ->badge() // Display as badge/tag
                    ->extraAttributes([
                        'class' => 'hover:shadow-md transition-all' // Hover effects
                    ]),

                TextColumn::make('exam_report')
                    ->label('كشف الامتحان')
                    ->state(fn() => 'كشف الامتحان')
                    ->url(
                        fn(Lecture $record) =>
                        route(
                            'report.lecture.exam',
                            ['lectureID' => $record->id]
                        )
                    )
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-document-text')
                    ->iconPosition('after')
                    ->color(Color::Emerald)
                    ->badge(),
                // Grouping Information
                TextColumn::make('grouping.name')
                    ->label(__('Attendance.grouping'))
                    ->sortable()
                    ->searchable()
                    ->description(fn($record) => $record->room?->name ?? ''),

                // Subject Information
                TextColumn::make('subject.name_ar')
                    ->label(__('res.subject'))
                    ->sortable()
                    ->searchable(),

                // Staff Information
                TextColumn::make('staff.name')
                    ->label(__('Attendance.staff'))
                    ->sortable()
                    ->searchable(),

                // Day and Time
                TextColumn::make('day_of_week')
                    ->label(__('Attendance.day_of_week'))
                    ->formatStateUsing(fn($state) => __("Attendance.days.$state"))
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('start_time')
                    ->label(__('Attendance.Time'))
                    ->formatStateUsing(
                        fn($record) =>
                        $record->start_time->format('h:i A') . ' - ' .
                        $record->end_time->format('h:i A')
                    )
                    ->sortable(),

                // Date Range
                TextColumn::make('start_date')
                    ->label(__('Attendance.Date_Range'))
                    ->formatStateUsing(
                        fn($record) =>
                        $record->start_date->format('d/m/Y') . ' - ' .
                        $record->end_date->format('d/m/Y')
                    )
                    ->sortable(),

                // Status Indicator
                IconColumn::make('status')
                    ->label(__('Status'))
                    ->icon(fn(string $state): string => match ($state) {
                        'completed' => 'heroicon-o-check-circle',
                        'ongoing' => 'heroicon-o-clock',
                        'upcoming' => 'heroicon-o-calendar',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'completed' => 'success',
                        'ongoing' => 'warning',
                        'upcoming' => 'info',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('day_of_week')
                    ->label(__('Attendance.day_of_week'))
                    ->options([
                        'Monday' => __('Attendance.days.Monday'),
                        'Tuesday' => __('Attendance.days.Tuesday'),
                        // ... other days
                    ]),

                Filter::make('current')
                    ->label(__('Current Records'))
                    ->query(fn(Builder $query): Builder => $query->where('end_date', '>=', now())),
            ])
            ->actions([
                EditAction::make(),
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
            'index' => Pages\ListLectures::route('/'),
            'create' => Pages\CreateLecture::route('/create'),
            'edit' => Pages\EditLecture::route('/{record}/edit'),
        ];
    }
}
