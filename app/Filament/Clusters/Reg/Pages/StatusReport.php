<?php

namespace App\Filament\Clusters\Reg\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Reg\Level;
use Filament\Tables\Table;
use App\Models\Reg\Faculty;
use App\Models\Reg\Student;
use App\Filament\Clusters\Reg;
use Illuminate\Support\Number;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Split;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;

use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
// use Filament\Tables\Columns\Layout\Split;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Widgets\Reg\StatusFaculty;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\Enums\VerticalAlignment;
use App\Filament\Widgets\Reg\StatusRegistration;
use Filament\Tables\Concerns\InteractsWithTable;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

// use BezhanSalleh\FilamentShield\Traits\HasPageShield;

use Illuminate\Database\Eloquent\Model;

use App\Models\Reg\Lt_column;
use App\Models\Reg\Batch;
use App\Models\Reg\Department;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Enums\FiltersLayout;

use Filament\Tables\Filters\QueryBuilder;


class StatusReport extends Page implements HasTable
{
    use InteractsWithTable;
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.clusters.reg.pages.status-report';

    protected static ?string $cluster = Reg::class;


    public static function getNavigationGroup(): string
    {
        return __('reg.reports');
    }

    public static function getLabel(): ?string
    {
        return __("reg.statusReport");
    }

    public function getTitle(): string | Htmlable
    {
        return __('reg.statusReport');
    }

    public static function getPluralLabel(): ?string
    {
        return __("reg.statusReport");
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__("reg.statusReport"));
    }


    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
   
   
    protected function getHeaderWidgets(): array
    {
        return [
            StatusRegistration::class,
            // StatusFaculty::class,
        ];
    }


 public function getTableRecordKey(Model $record): string
    {
        return (string) $record->id;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Student::query()

                    ->selectRaw('faculty_code as id')
                    ->selectRaw("SUM(CASE WHEN xstatus = '20070001' THEN 1 ELSE 0 END) AS complete")
                    ->selectRaw("SUM(CASE WHEN xstatus = '20070002' THEN 1 ELSE 0 END) AS noreg")
                    ->selectRaw("SUM(CASE WHEN xstatus = '20070003' THEN 1 ELSE 0 END) AS unreg")
                    ->selectRaw('COUNT(*) AS total')
                    ->groupBy('faculty_code')
                    ->orderBy('faculty_code')
            )
            // ... rest of the table definition
            ->recordAction(null)
            ->defaultSort(null)
            ->columns([
                TextColumn::make('id')
                    ->label(__('reg.faculty'))
                    ->formatStateUsing(fn($state) => optional(Faculty::where('code', $state)->first())->name)
                    ,
                TextColumn::make('total')
                     ->label(' ')
                    ->color('primary')
                    ->summarize(Sum::make()->label(' ')),
                TextColumn::make('complete')
                    ->label(__('reg.Complete'))
                    ->description(fn(Model $record) => '(' . number_format(($record->complete / $record->total) * 100, 2) . '%)')
                    ->color('success')
                    ->summarize(
                        Sum::make()
                             ->label(' ')
                            ->formatStateUsing(function ($state, $livewire) {
                                // Total complete from all rows
                                $totalComplete = $state;
                                $totalStudents = $livewire->getFilteredTableQuery()
                                    ->get() 
                                    ->sum('total');

                                $percentage = $totalStudents > 0
                                    ? number_format(($totalComplete / $totalStudents) * 100, 2)
                                    : 0;

                                return "{$totalComplete} ({$percentage}%)";
                            })
                    ),

                TextColumn::make('noreg')
                    ->label(__('reg.noreg'))
                    ->description(fn(Model $record) => '(' . number_format(($record->noreg / $record->total) * 100, 2) . '%)')
                    ->color('warning')
                     ->summarize(
                        Sum::make()
                            ->label(' ')
                            ->formatStateUsing(function ($state, $livewire) {
                                // Total noreg from all rows
                                $totalNoreg = $state;
                                $totalStudents = $livewire->getFilteredTableQuery()
                                    ->get() 
                                    ->sum('total');

                                $percentage = $totalStudents > 0
                                    ? number_format(($totalNoreg / $totalStudents) * 100, 2)
                                    : 0;

                                return "{$totalNoreg} ({$percentage}%)";
                            })
                    ),

                TextColumn::make('unreg')
                    ->label(__('reg.unRegister'))
                    ->description(fn(Model $record) => '(' . number_format(($record->unreg / $record->total) * 100, 2) . '%)')
                    ->color('danger')
                    ->summarize(
                        Sum::make()
                            ->label(' ')
                            ->formatStateUsing(function ($state, $livewire) {
                                // Total unreg from all rows
                                $totalUnreg = $state;
                                $totalStudents = $livewire->getFilteredTableQuery()
                                    ->get() 
                                    ->sum('total');

                                $percentage = $totalStudents > 0
                                    ? number_format(($totalUnreg / $totalStudents) * 100, 2)
                                    : 0;

                                return "{$totalUnreg} ({$percentage}%)";
                            })
                    ),

            ])
            ->filters([
                SelectFilter::make('faculty')
                    ->label(__('reg.faculty'))
                    ->options(
                        Faculty::all()
                            ->mapWithKeys(fn($faculty) => [
                                $faculty->code => "{$faculty->name} ({$faculty->code})"
                            ])
                    )
                    ->attribute('faculty_code')
                    ->searchable()
                    ->multiple()
                    ->preload(),
                SelectFilter::make(__('reg.level'))
                    ->relationship('level', 'name')
                    ->attribute('level_code')
                    ->searchable()->multiple()
                    ->preload(),
                SelectFilter::make('admission_code')
                    ->label(__('reg.admission'))
                    ->options(Lt_column::all()
                        ->where('grouped', '2001')
                        ->pluck('name', 'code')),

                SelectFilter::make('gender_code')
                    ->label(__('reg.gender'))
                    ->options(Lt_column::all()
                        ->where('grouped', '1001')
                        ->pluck('name', 'code')),
                QueryBuilder::make()
                    ->constraintPickerColumns(4)
                    ->constraints([

                        SelectConstraint::make('batch_code')
                            ->label(__('reg.batch'))
                            ->options(Batch::all()
                                ->pluck('name', 'code')),

                        SelectConstraint::make('department_code')
                            ->label(__('reg.department'))
                            ->options(Department::all()
                                ->pluck('name', 'code')),

                        SelectConstraint::make('academic_year_code')
                            ->label(__('reg.academic_year'))
                            ->options(Lt_column::all()
                                ->where('grouped', '2003')
                                ->pluck('name', 'code')),

                        BooleanConstraint::make('gender_code')->label(__('reg.gender')),
                        NumberConstraint::make('fees')->label(__('reg.fees')),
                        NumberConstraint::make('reg_fees')->label(__('reg.regfees')),

                        SelectConstraint::make('installment_code')
                            ->label(__('reg.Installments'))
                            ->options(Lt_column::all()
                                ->where('grouped', '2005')
                                ->pluck('name', 'code')),
                        NumberConstraint::make('all_fees')->label(__('reg.all_fees')),
                        NumberConstraint::make('xmod')->label(__('reg.xmod')),

                    ])
            ], layout: FiltersLayout::Modal)
            ->filtersFormColumns(3)
            ->deferFilters()
            ->deferLoading()
            ->filtersFormWidth(MaxWidth::SixExtraLarge)
        ;
    }
}