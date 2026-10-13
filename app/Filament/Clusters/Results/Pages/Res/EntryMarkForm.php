<?php

namespace App\Filament\Clusters\Results\Pages\Res;

use App\Enums\Semester;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Enums\AbsStatus;
use Filament\Forms\Form;
use App\Models\Reg\Batch;
use App\Models\Reg\Level;
use App\Models\Reg\Faculty;
use App\Models\Reg\Student;
use App\Models\Result\Subject;
use App\Models\Result\CourseSubject;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\Enums\VerticalAlignment;

trait EntryMarkForm
{
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

                            // حقل Semester و AbsStatus
                            Select::make('semester')
                                ->label(__('res.semester'))
                                ->options(function () {
                                    return collect(Semester::cases())
                                        // Filter to include only First and Second semesters
                                        ->filter(fn ($case) => $case->value <= 2)
                                        ->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()]);
                                })
                                ->native()
                                ->required()
                                ->dehydrated(),

                            Select::make('absent')
                                ->label(__('res.absent'))
                                ->options(AbsStatus::class)
                                ->live() // ->live() : يجعل الحقل تفاعلياً
                                ->native()
                                ->required()
                                // ->afterStateUpdated() : يتم تنفيذ هذا الكود بعد تغيير قيمة الحقل
                                ->afterStateUpdated(function (Set $set, ?string $state) {
                                    // إذا اختار المستخدم الخيار الذي قيمته 2
                                    // if ($state == 2) {
                                    //     // قم بتعيين قيمة حقل 'semester' إلى 2 تلقائياً
                                    //     $set('semester', '2');
                                    // }
                                }),

                            Select::make('subject')
                                ->required()
                                ->label(__('res.subject'))
                                ->options(function (Get $get) {
                                    // تأكد من أن جميع الحقول المطلوبة لها قيمة قبل جلب المقررات
                                    if (empty($get('faculty')) || empty($get('level')) || empty($get('semester'))) {
                                        return []; // إرجاع مصفوفة فارغة إذا كانت أي من الحقول فارغة
                                    }

                                    return Subject::query()
										->Exam()
                                        ->where('faculty_code', $get('faculty'))
                                        ->where('level_code', $get('level'))
                                        // أضف شرطاً للفلترة حسب الفصل الدراسي فقط إذا لم تكن قيمة 'absent' تساوي 2
                                        ->when($get('absent') != 2, function (Builder $query) use ($get) {
                                            return $query->where('semester_id', $get('semester'));
                                        })
                                        ->with('staff')
                                        ->get()
                                        ->pluck('name_ar', 'code');
                                })
                                ->visible(function (Get $get) {
                                    // حقل Subject هيكون مرئي فقط لو كل الحقول دي ليها قيمة
                                    return !empty($get('faculty')) && !empty($get('level')) && !empty($get('semester'));
                                })
                                ->dehydrated(false) // تأكد من عدم حفظ القيمة إذا كان الحقل مخفيًا
                                ->live() // مهم لتحديث الخيارات عند تغيير الحقول الأخرى
                                ->native(false)
                                ->columnSpan(2),

                            Actions::make([
                                Action::make('ShowStudents')
                                    ->label(__('reg.students'))
                                    ->icon('heroicon-m-plus')
                                    ->color('primary')
                                    ->action('StudentEntryFn'),
                            ])
                                ->visible()
                                ->verticalAlignment(VerticalAlignment::End)
                        ]),
                    Section::make([
                        Placeholder::make('par')
                            ->label(__('reg.students'))
                            ->content(fn () => $this->StudentCount ?: 'No Students')
                    ])->grow(false),
                ])->from('md')
            ]);
    }

    public function StudentEntryFn()
    {
        $this->courseCode = $this->course;
        $this->subjectCode = $this->subject;
    }

    public function StudentCountFn($course = null)
    {


        if (!$course) {
            return 'No Students';
        }
        $count = 0;
        CourseSubject::where('course_code', $course)
            ->chunk(200, function ($courseSubjects) use (&$count) {
                foreach ($courseSubjects as $courseSubject) {
                    $count += $courseSubject->batch->students->count();
                }
            });

        return $count ?: 'No';
    }
}
