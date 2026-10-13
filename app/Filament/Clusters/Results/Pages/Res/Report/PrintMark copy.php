<?php

namespace App\Filament\Clusters\Results\Pages\Res\Report;

use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Reg\Batch;
use App\Models\Reg\Student;
use App\Models\Result\Mark;
use App\Models\Result\Course;
use App\Models\Result\Printed;
use App\Filament\Clusters\Results;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\CheckboxList;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class PrintMark extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.clusters.results.pages.res.report.print-mark';
    protected static ?string $cluster = Results::class;
    protected static ?string $model = Mark::class;

    public static function getNavigationLabel(): string
    {
        return __('res.print');
    }

    public function getTitle(): string | Htmlable
    {
        return __('');
    }

    // خصائص النموذج الأول (طباعة الدرجات)
    public $course, $faculty, $level;
    public $marksDetails = [];
    public $fields = [];

    // خصائص النموذج الثاني (بحث الدفعة)
    public $batch_code;
    public $search_results = null;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // النموذج الأول - طباعة الدرجات
                Section::make(__('res.grades_print'))
                    ->description(__('res.grades_print_description'))
                    ->columns(3)
                    ->schema([
                        Select::make('course')
                            ->label(__('res.courses'))
                            ->options(Course::all()->pluck('name', 'code'))
                            ->required()
                            ->searchable()
                            ->live(),

                        CheckboxList::make('marksDetails')
                            ->label(__('res.show_entry'))
                            ->columns(2)
                            ->required()
                            ->rules(['required', 'array', 'min:1'])
                            ->validationMessages([
                                'required' => 'يجب اختيار الحقول المطلوبة',
                                'min' => 'يجب اختيار حقل واحد على الأقل',
                            ])
                            ->searchable()
                            ->options(
                                Printed::where('type', '>', 1)->pluck('name_ar', 'fields')
                            ),



                        Actions::make([
                            Action::make('PrintMark')
                                ->label(__('Print'))
                                ->action('printMarks')
                                ->icon('heroicon-o-printer')
                                ->requiresConfirmation()
                                ->color('success')
                                ->outlined()
                                ->extraAttributes([
                                    'x-on:click' => 'window.open($event.target.href, "_blank")',
                                ]),
                        ])
                        ->verticalAlignment(VerticalAlignment::End)
                    ]),

                // النموذج الثاني - بحث الدفعة
                Section::make(__('res.batch_print'))
                    ->description(__('res.batch_print_description'))
                    ->schema([

                                Select::make('batch_code')
                                    ->label(__('res.batch'))
                                    ->options(Batch::all()->pluck('name', 'code'))
                                    ->required()
                                    ->searchable()
                                    ->live(),

                                Actions::make([
                                    Action::make('searchBatch')
                                        ->label(__('reg.batch'))
                                        ->action('PrintBatch')
                                        ->icon('heroicon-o-magnifying-glass')
                                        ->color('primary'),

                                ])->verticalAlignment(VerticalAlignment::End)
                            ,

                        // عرض نتائج البحث
                        Section::make(__('res.search_results'))
                            ->visible(fn() => $this->search_results !== null)
                            ->schema([
                                // يمكنك إضافة مكونات لعرض النتائج هنا



                        ])
                    ])
                    ->columns(2)
                    ,

                // يمكنك إضافة المزيد من النماذج هنا
            ]);
    }

    /**
     * دالة طباعة الدرجات
     */
    public function printMarks()
    {
        $validatedData = $this->validate([
            'fields' => ['required'],
            'course' => ['required'],
            'marksDetails' => ['required', 'min:1'],
        ]);

        return redirect()->route('print.grades', [
            'course_code' => $this->course,
            'fields' => implode(',', $this->fields ?? []),
            'details' => implode(',', $this->marksDetails ?? [])
        ]);
    }

    /**
     * دالة بحث الدفعة
     */
    public function PrintBatch()
    {
        $validatedData = $this->validate([
            'batch_code' => ['required', 'numeric'],
        ]);

        $batchCode = $this->batch_code;

        try {
            $batch = Batch::with(['course.subjects' => function ($query) {
                $query->orderBy('name_ar');
            }])->findOrFail($batchCode);

            $students = Student::with(['marks.subject' => function ($query) use ($batch) {
                $query->whereIn('code', $batch->course->subjects->pluck('code'));
            }])->where('batch_code', $batchCode)
                ->orderBy('name_ar')
                ->get();

            $this->search_results = [
                'batch' => $batch,
                'students' => $students,
                'subjects' => $batch->course->subjects
            ];

            // يمكنك إرسال إشعار بنجاح العملية
            $this->dispatch('notify', type: 'success', message: __('res.batch_found'));

        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'danger', message: __('res.batch_not_found'));
        }
    }
}
