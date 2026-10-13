<?php

namespace App\Filament\Clusters\Results\Pages\Res\Report;

use App\Enums\AbsStatus;
use App\Enums\Semester;
use App\Filament\Clusters\Results;
use App\Models\Reg\Batch;
use App\Models\Reg\Faculty;
use App\Models\Reg\Level;
use App\Models\Reg\Student;
use App\Models\Result\Course;
use App\Models\Result\Grade;
use App\Models\Result\Mark;
use App\Models\Result\Printed;
use App\Models\Result\ResMark;
use App\Models\Result\Subject;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class PrintMark extends Page implements HasForms
{
    use InteractsWithForms;
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.clusters.results.pages.res.report.print-mark';
    protected static ?string $cluster = Results::class;
    protected static ?string $model = Mark::class;

    public static function getNavigationLabel(): string
    {
        return __('res.print');
    }

    public function getTitle(): string|Htmlable
    {
        return __('res.print');
    }


    public $batch, $faculty, $level, $semester, $absent;
    public $marksDetails = [];
    public $fields = [];


    public $subjects = [];
    public $grades = [];

    public $search_results = null;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make()
                        ->columns(['sm' => 5])
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

                            Select::make('semester')
                                ->label(__('res.semester'))
                                ->options(Semester::class)
                                ->live()
                                ->native()
                                ->required(),

                            Select::make('absent')
                                ->label(__('res.absent'))
                                ->options(AbsStatus::class)
                                ->live()
                                ->native()
                                ->required(),


                            Select::make('subjects')
                                // ->required()
                                ->label(__('res.subjects'))
                                ->options(function (Get $get) {
                                    // تأكد من أن جميع الحقول المطلوبة لها قيمة قبل جلب المقررات
                                    if (empty($get('faculty')) || empty($get('level')) || empty($get('semester'))) {
                                        return []; // إرجاع مصفوفة فارغة إذا كانت أي من الحقول فارغة
                                    }

                                    return Subject::query()
                                        //  ->Exam()
                                        ->where('faculty_code', $get('faculty'))
                                        ->where('level_code', $get('level'))
                                        // أضف شرطاً للفلترة حسب الفصل الدراسي فقط إذا لم تكن قيمة 'absent' تساوي 2
                                        ->when($get('semester') < 3, function (Builder $query) use ($get) {
                                            return $query->where('semester_id', $get('semester'));
                                        })
                                        ->get()
                                        ->pluck('name_ar', 'code');
                                })
                                // ->visible(function (Get $get) {
                                //     // حقل Subject هيكون مرئي فقط لو كل الحقول دي ليها قيمة
                                //     return !empty($get('faculty')) && !empty($get('level')) && !empty($get('semester'));
                                // })
                                ->multiple()
                                ->dehydrated(true) // تأكد من عدم حفظ القيمة إذا كان الحقل مخفيًا
                                ->live() // مهم لتحديث الخيارات عند تغيير الحقول الأخرى
                                ->native(false)
                                ->columnSpan(2),

                            Select::make('grades')
                                ->label(__('res.grade'))
                                ->options(
                                        ResMark::groupBy('grade')
                                            ->pluck('grade', 'grade')
                                        )
                                ->live()
                                ->native()
                                ->multiple()
                                ->required(),

                            CheckboxList::make('fields')
                                ->label(__('res.fields'))
                                ->options(
                                    Printed::where('type', '>=', 1)
                                        ->pluck('name_ar', 'fields')
                                )
                                ->default(['1', '2', '7'])
                                ->rules(['required', 'array', 'min:1'])
                                ->validationMessages([
                                    'required' => 'يجب اختيار الحقول المطلوبة',
                                    'min' => 'يجب اختيار حقل واحد على الأقل',
                                ])
                                ->searchable()
                                ->columns(4)
                                ->columnSpan(5),




                            Actions::make([
                                Action::make('Print-Details')
                                    ->label(__('res.print'))
                                    ->icon('heroicon-m-plus')
                                    ->color('primary')
                                    ->action('PrintDetails'),
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
    public function PrintDetails()
    {
        $validatedData = $this->validate([
            'faculty' => ['required'],
            'level' => ['required'],
            'batch' => ['required'],
            'semester' => ['required'],
            'absent' => ['required'],
            // 'subjects' => ['required', 'array', 'min:1'],
            'fields' => ['required', 'min:1'],
        ]);

        $dataForm = [
            'faculty' => $this->faculty,
            'level' => $this->level,
            'batch' => $this->batch,
            'semester' => $this->semester,
            'absent' => $this->absent,
            'subjects' => $this->subjects,
            'fields' => implode(',', $this->fields ?? []),
            'grades' => $this->grades
        ];

        return redirect()->route('student.results', [
            'dataForm' => base64_encode(json_encode($dataForm))
        ]);
        //return redirect()->route('print.details', $dataForm);
    }

}
