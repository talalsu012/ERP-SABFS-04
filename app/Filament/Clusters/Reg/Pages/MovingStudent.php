<?php

namespace App\Filament\Clusters\Reg\Pages;

use App\Filament\Clusters\Reg;
use Filament\Pages\Page;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Reg\Student;
use App\Models\Reg\Batch;
use App\Models\Reg\Level;
use App\Models\Reg\Faculty;
use App\Models\Reg\Lt_column as LtColumn;
use App\Models\Result\ResLtRecoms as LtRecom;


use Filament\Support\Enums\MaxWidth;

use Illuminate\Contracts\Support\Htmlable;

//use Filament\Forms\Components\Actions\Action;
use Filament\Support\Enums\VerticalAlignment;


use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;


use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;


use Illuminate\Database\Eloquent\Model;


use Filament\Tables\Enums\FiltersLayout;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;

//Form
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;


class MovingStudent extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use HasPageShield;


    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.clusters.reg.pages.moving-student';

    protected static ?string $cluster = Reg::class;

    protected static ?int $navigationSort = 1;
    public function getTitle(): string|Htmlable
    {
        return __('نقل الطلاب');
    }
    public static function getLabel(): ?string
    {
        return __('نقل الطلاب');
    }
    public static function getPluralLabel(): ?string
    {
        return '';
    }
    public function getMaxContentWidth(): MaxWidth|string|null
    {
        return MaxWidth::ScreenTwoExtraLarge;
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__('نقل الطلاب وفتح التسجيل'));
    }



    public static function getNavigationGroup(): string
    {
        return __('نقل الطلاب');
    }


    public ?array $promotionData = [];
    public array $studentsToMove = [];

    public function mount(): void
    {
        $this->promotionData = [
            'recom_code' => [],
            'faculty_code' => [],
        ];
    }


    public function form(Form $form): Form
    {
        return $form
            ->statePath('promotionData')
            ->schema([

                Section::make([])
                    ->columns([
                        'sm' => 5,
                    ])
                    ->schema([

                        Select::make('faculty_code')
                            ->label('الكلية')
                            ->default(null)
                            ->searchable()
                            ->options(fn() => Faculty::pluck('name', 'code'))
                            ->required()
                            ->columnSpan(2),

                        Select::make('recom_code')
                            ->label(__('res.recom'))
                            ->multiple()
                            ->default([])
                            ->options(fn() => LtRecom::pluck('name', 'code'))
                            ->searchable()
                            ->required()
                            ->columnSpan(3),



                        Select::make('current_level')
                            ->label('المستوى الحالي')
                            ->options(fn() => Level::pluck('name', 'code'))
                            ->required(),

                        Select::make('current_batch_code')
                            ->label('العام الأكاديمي الحالي')
                            ->options(fn() => LtColumn::where('grouped', '2003')->pluck('name', 'code'))
                            ->required(),


                        Select::make('new_level')
                            ->label('المستوى الجديد')
                            ->options(fn() => Level::pluck('name', 'code'))
                            ->required()
                            ->rule('different:current_level'),

                        Select::make('new_batch_code')
                            ->label('العام الأكاديمي الجديد')
                            ->options(fn() => LtColumn::where('grouped', '2003')->pluck('name', 'code'))
                            ->required()
                            ->rule('different:current_batch_code'),

                        Actions::make([
                            Action::make('preview')
                                ->label('عرض الطلاب')
                                ->icon('heroicon-o-eye')
                                ->color('info')

                                ->action(function () {

                                    $this->form->validate();
                                    $this->loadStudentsForPromotion();
                                }),


                            Action::make('execute')
                                ->label('تنفيذ الترحيل')
                                ->icon('heroicon-o-arrow-path')
                                ->color('success')
                                ->visible(fn($livewire) => count($livewire->studentsToMove) > 0)
                                ->requiresConfirmation()
                                ->modalHeading('تأكيد الترحيل')
                                ->modalDescription('سيتم تحديث جميع الطلاب المطابقين للمعايير المحددة.')
                             ->action(fn($livewire) => $livewire->executePromotion()),
                        ])->columnSpanFull(),


                    ]),


            ]);

    }


    public function loadStudentsForPromotion()
    {
        $formData = $this->promotionData;

        $this->studentsToMove = Student::query()

            ->where('faculty_code', $formData['faculty_code'] ?? null)
            ->where('level_code', $formData['current_level'] ?? null)
            ->where('batch_code', $formData['current_batch_code'] ?? null)

            // ->with([
            //     'recom' => function ($query) use ($formData) {
            //         $query->whereIn('recom_code', $formData['recom_code'] ?? []);
            //     }
            // ])

            // 3. التصفية بناءً على وجود سجل في علاقة recom (جدول resrecom)
            ->whereHas('recom', function ($query) use ($formData) {

                // فلترة حسب recom المختارة
                $query->when(
                    !empty($formData['recom_code']),
                    fn($q) => $q->whereIn('recom_code', $formData['recom_code'])
                );

                // التأكد أن مستوى النتيجة يساوي مستوى الطالب
                $query->whereColumn('res_recoms.level_code', 'students.level_code');

                // التأكد أن العام الأكاديمي يساوي عام الطالب
                $query->whereColumn('res_recoms.batch_code', 'students.batch_code');
                
                
                $query->where('res_recoms.semester', 2);
                
                //اذا كانت هنالك قيمتان في الحقل examType اختار الاكبر منهام 
            })
            ->get()
            ->toArray();

        $this->resetTable();

        Notification::make()
            ->title('تم تحميل ' . count($this->studentsToMove) . ' طالب')
            ->success()
            ->send();
    }


 public function executePromotion()
    {
         $formData = $this->promotionData;

        if (empty($this->studentsToMove)) {
            Notification::make()
                ->title('لا يوجد طلاب للترحيل')
                ->danger()
                ->send();
            return;
        }

          Student::query()
            ->where('faculty_code', $formData['faculty_code'] ?? null)
            ->where('level_code', $formData['current_level'] ?? null)
            ->where('batch_code', $formData['current_batch_code'] ?? null)

            ->whereHas('recom', function ($query) use ($formData) {

                // فلترة حسب recom المختارة
                $query->when(
                    !empty($formData['recom_code']),
                    fn($q) => $q->whereIn('recom_code', $formData['recom_code'])
                );
                $query->whereColumn('res_recoms.level_code', 'students.level_code');
                $query->whereColumn('res_recoms.batch_code', 'students.batch_code');                    
                $query->where('res_recoms.semester', 2);          
            })
            ->update([
                'level_code' => $this->promotionData['new_level'],
                'batch_code' => $this->promotionData['new_batch_code'], // لو العام الجديد نفسه
                'fees8' => DB::raw('fees8 + xmod'),
            ]);



        Notification::make()
            ->title('تم ترحيل الطلاب بنجاح')
            ->success()
            ->send();

        $this->studentsToMove = [];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->baseQuery())
            ->columns($this->getColumns())
            ->filtersLayout(FiltersLayout::AboveContentCollapsible)
            ->paginationPageOptions([10, 25, 50, 'all'])
            ->filtersFormWidth(MaxWidth::FourExtraLarge)
            ->actions([])
            ->bulkActions([]);
    }

    protected function baseQuery(): Builder
    {
        $formData = $this->promotionData;

        return Student::query()
            ->with('faculty', 'level', 'batch')
            ->with(['recom.recomName'])
            ->where('faculty_code', $this->promotionData['faculty_code'] ?? null)
            ->where('level_code', $this->promotionData['current_level'] ?? null)
            ->where('batch_code', $this->promotionData['current_batch_code'] ?? null)
            ->whereHas('recom', function ($query) use ($formData) {

                // فلترة حسب recom المختارة
                $query->when(
                    !empty($formData['recom_code']),
                    fn($q) => $q->whereIn('recom_code', $formData['recom_code'])
                );

                // التأكد أن مستوى النتيجة يساوي مستوى الطالب
                $query->whereColumn('res_recoms.level_code', 'students.level_code');

                // التأكد أن العام الأكاديمي يساوي عام الطالب
                $query->whereColumn('res_recoms.batch_code', 'students.batch_code');
                
                
                $query->where('res_recoms.semester', 2);
                
                //اذا كانت هنالك قيمتان في الحقل examType اختار الاكبر منهام 
                
                // اعرض قيمة واحة فقط 
            })
     
            ->orderBy('name_ar');
    }

    protected function getColumns(): array
    {
        return [
            TextColumn::make('id')
                ->label('#')
                ->rowIndex()
                ->listWithLineBreaks(),
            TextColumn::make('university_no')
                ->label(__('reg.university_no')),
            TextColumn::make('name_ar')
                ->label(__('reg.name_ar')),

        TextColumn::make('recom')
    ->label(__('res.recom'))
    ->formatStateUsing(function ($record) {

        $selectedRecoms = $this->promotionData['recom_code'] ?? [];

        return $record->recom
            ->whereIn('recom_code', $selectedRecoms) // فلترة المتشابه فقط
            ->pluck('recomName.name')               // جلب الاسم
            ->filter()
            ->implode(', ');
    }),

            TextColumn::make('faculty.name')
                ->label(__('reg.faculty')),
            TextColumn::make('level.name')
                ->label(__('reg.level')),
            TextColumn::make('batch.name')
                ->label(__('reg.batch')),
            TextColumn::make('xmod')
                ->label(__('reg.xmod'))
                ->badge()
                ->color(fn($record) => $record->xmod > 0 ? 'danger' : 'success')
            ,

        ];
    }

}