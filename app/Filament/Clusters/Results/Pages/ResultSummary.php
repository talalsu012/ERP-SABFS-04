<?php

namespace App\Filament\Clusters\Results\Pages;

use App\Enums\AbsStatus;
use App\Enums\Semester;
use App\Filament\Clusters\Results;
use App\Models\Reg\Batch;
use App\Models\Reg\Faculty;
use App\Models\Reg\Level;
use App\Models\Result\Printed;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Enums\VerticalAlignment;
use Illuminate\Contracts\Support\Htmlable;

class ResultSummary extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.clusters.results.pages.result-summary';

    protected static ?string $cluster = Results::class;


    public static function getNavigationLabel(): string
    {
        return __('res.print_summary');
    }

    public function getTitle(): string|Htmlable
    {
        return __('res.print_summary');
    }


    public $batch, $faculty, $level, $semester, $examType;
    public $marksDetails = [];
    public $fields = [];

    public $search_results = null;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make()
                        ->columns(['sm' => 4])
                        ->schema([
                            Select::make('batch')
                                ->label(__('reg.batch'))
                                ->required()
                                ->options(Batch::all()->pluck('name', 'code'))
                                ->searchable()
                                ->live()
                                ->native(),

                            Select::make('faculty')
                                ->label(__('reg.faculty'))
                                ->required()
                                ->options(
                                    Faculty::query()
                                        ->selectRaw('code, CONCAT(name, " (", code, ")") as name_with_code')
                                        ->pluck('name_with_code', 'code')
                                )
                                ->searchable()
                                ->live()
                                ->native()
                                ->columnSpan(3),

                            Select::make('level')
                                ->label(__('reg.level'))
                                ->options(Level::all()->pluck('name', 'code'))
                                ->required()
                                ->live()
                                ->native(),

                            Select::make('examType')
                                ->label(__('res.absent'))
                                ->options(AbsStatus::class)
                                ->live()
                                ->native()
                                ->required(),



                            Actions::make([
                                Action::make('Print-Details')
                                    ->label(__('res.print'))
                                    ->icon('heroicon-m-plus')
                                    ->color('primary')
                                    ->action('PrintSummary'),
                            ])
                                ->visible()
                                ->verticalAlignment(VerticalAlignment::End)
                        ]),

                ])->from('md')
            ]);
    }

    /**
     * دالة طباعة الدرجات
     */
    public function PrintSummary()
    {
        $validatedData = $this->validate([
            'faculty' => ['required'],
            'level' => ['required'],
            'batch' => ['required'],
             'examType' => ['required'],
        ]);


        $dataForm = [
            'faculty' => $this->faculty,
            'level' => $this->level,
            'batch' => $this->batch,
            'examType' => $this->examType
        ];

        return redirect()->route('results.summary', [
            'dataForm' => base64_encode(json_encode($dataForm))
        ]);
       
    }
}
