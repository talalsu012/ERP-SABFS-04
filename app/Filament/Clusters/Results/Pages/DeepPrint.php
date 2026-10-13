<?php

namespace App\Filament\Clusters\Results\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use App\Models\Result\Course;
use App\Models\Result\Printed;
use App\Filament\Clusters\Results;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\CheckboxList;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Notifications\Notification;

class DeepPrint extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.clusters.results.pages.deep-print';
    protected static ?string $cluster = Results::class;

   // أو التحكم الشرطي:
    public static function canAccess(): bool
    {
        return false; // إخفاء تماماً
    }



    public $course;
    public $faculty;
    public $level;
    public $marksDetails = [];
    public $fields = [];
    public $showPrintView = false;

    protected $queryString = [
        'course',
        'marksDetails',
        'fields',
        'showPrintView'
    ];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns([
                        'sm' => 3,
                    ])
                    ->schema([
                        Select::make('course')
                            ->options(Course::all()->pluck('name', 'code'))
                            ->required()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->course = $state;
                                $this->showPrintView = false;
                            }),

                        CheckboxList::make('marksDetails')
                            ->columns(2)
                            ->required()
                            ->rules(['required', 'array', 'min:1'])
                            ->validationMessages([
                                'required' => 'يجب اختيار الحقول المطلوبة',
                                'min' => 'يجب اختيار حقل واحد على الأقل',
                            ])
                            ->searchable()
                            ->live()
                            ->options(
                                Printed::where('type', '>', 1)->pluck('name_ar', 'fields')
                            )
                            ->afterStateUpdated(function ($state) {
                                $this->marksDetails = $state;
                                $this->showPrintView = false;
                            }),

                        CheckboxList::make('fields')
                            ->options(
                                Printed::where('type', 1)->pluck('name_ar', 'fields')
                            )
                            ->rules(['required', 'array', 'min:1'])
                            ->validationMessages([
                                'required' => 'يجب اختيار الحقول المطلوبة',
                                'min' => 'يجب اختيار حقل واحد على الأقل',
                            ])
                            ->searchable()
                            ->live()
                            ->columns(2)
                            ->afterStateUpdated(function ($state) {
                                $this->fields = $state;
                                $this->showPrintView = false;
                            }),

                        Actions::make([
                            Action::make('showPrint')
                                ->label(__('عرض الطباعة'))
                                ->action('showPrint')
                                ->icon('heroicon-o-printer')
                                ->color('success')
                                ->outlined()
                                ->visible(fn() => !$this->showPrintView),

                            Action::make('backToForm')
                                ->label(__('العودة للنموذج'))
                                ->action('backToForm')
                                ->icon('heroicon-o-arrow-uturn-left')
                                ->color('gray')
                                ->outlined()
                                ->visible(fn() => $this->showPrintView),
                        ])
                        ->verticalAlignment(VerticalAlignment::End)
                    ]),
            ]);
    }

    public function showPrint()
    {
        $this->validate([
            'course' => 'required',
            'marksDetails' => 'required|array|min:1',
            'fields' => 'required|array|min:1',
        ]);

        $this->showPrintView = true;

        Notification::make()
            ->title('تم تحضير بيانات الطباعة بنجاح')
            ->success()
            ->send();
    }

    public function backToForm()
    {
        $this->showPrintView = false;
    }

    public function getPrintViewData()
    {
        if (!$this->showPrintView) {
            return [];
        }

        // جلب البيانات المطلوبة للطباعة
        $course = Course::where('code', $this->course)->first();

        if (!$course) {
            Notification::make()
                ->title('خطأ في بيانات المقرر')
                ->danger()
                ->send();
            return [];
        }

        // $students = $course->batch->students->with(['marks', 'level', 'faculty'])->get();
        $students = $course->students->with(['marks', 'level', 'faculty'])->get();
        $subjects = $course->subjects;
        $grades = \App\Models\Result\Grade::all();

        // حساب الإحصائيات
        $subjectStats = [];
        foreach ($subjects as $subject) {
            $marks = $students->flatMap->marks->where('subject_code', $subject->code);
            $passed = $marks->where('mark', '>=', 50)->count();
            $failed = $marks->where('mark', '<', 50)->count();

            $subjectStats[$subject->code] = [
                'total' => $marks->count(),
                'passed' => $passed,
                'failed' => $failed,
                'average' => $marks->avg('mark') ? round($marks->avg('mark'), 2) : 0,
                'max' => $marks->max('mark') ?? 0,
                'min' => $marks->min('mark') ?? 0,
            ];
        }

        return [
            'course' => $course,
            'students' => $students,
            'subjects' => $subjects,
            'grades' => $grades,
            'subjectStats' => $subjectStats,
            'selectedFields' => $this->fields,
            'selectedDetails' => $this->marksDetails,
        ];
    }
}
