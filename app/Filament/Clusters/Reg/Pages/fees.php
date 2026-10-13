<?php

namespace App\Filament\Clusters\Reg\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Reg\Student;
use App\Models\Reg\Payment;
use App\Filament\Clusters\Reg;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Split;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\DateRangeFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Enums\FiltersLayout;

    
class Fees extends Page implements HasTable
{
    use InteractsWithTable;
    // use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.clusters.reg.pages.fees';

    protected static ?string $cluster = Reg::class;

    public ?string $startdate = null;
    public ?string $enddate   = null;

    public static function getNavigationGroup(): string
    {
        return __('reg.reports');
    }

    public static function getLabel(): ?string
    {
        return __("reg.repofees");
    }

    public function getTitle(): string | Htmlable
    {
        return __('reg.repofees');
    }

    public static function getPluralLabel(): ?string
    {
        return __("reg.repofees");
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__("reg.repofees"));
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

   
    public function table(Table $table): Table
    {
        return $table
            // نبني الاستعلام: نجمع المدفوعات JOIN الطلاب و JOIN الكليات
            ->query(
                Payment::query()
                ->select([
                    // أزلنا alias باسم 'id' لأنه يجعل الفريمورك يظن أن هناك مفتاح id من payments
                    'students.faculty_code as faculty_code',
                    DB::raw('COALESCE(faculties.name, CONCAT("Faculty #", students.faculty_code)) as faculty_name'),
                    DB::raw('COUNT(DISTINCT payments.university_no) as students_count'),
                    DB::raw('SUM(payments.amount) as total_amount'),
                ])
                ->join('students', 'payments.university_no', '=', 'students.university_no')
                ->leftJoin('faculties', 'students.faculty_code', '=', 'faculties.code')
                // ضمنا اسمي الأعمدة الحقيقية في groupBy (لا تستخدم alias 'faculty_name' في groupBy)
                ->groupBy('students.faculty_code', 'faculties.name')
                // أضفنا ترتيب صريح على حقل مجمع/مجموعي (يتوافق مع ONLY_FULL_GROUP_BY)
                ->orderBy('students.faculty_code', 'asc')
        )
            // أعمدة الجدول المعروضة
            ->columns([
                TextColumn::make('faculty_name')
                    ->label(__('reg.faculty'))
                    ->sortable(),

                TextColumn::make('students_count')
                    ->label(__('reg.counts'))
                    ->sortable()
                    ->summarize(Sum::make())
                    ->formatStateUsing(fn($state) => number_format($state)),

                TextColumn::make('total_amount')
                    ->label(__('reg.amount'))
                    ->sortable()
                    ->summarize(Sum::make())
                    ->formatStateUsing(fn($state) => number_format($state, 2)),
            ])

            // ->pushColumns([
            //     TextColumn::make('last_payment_date')
            //         ->label(__('reg.last_payment'))
            //         ->getStateUsing(function ($record) {
            //             $lastPayment = Payment::where('faculty_code', $record->faculty_code)
            //                 ->orderBy('bankdate', 'desc')
            //                 ->first();
            //             return null;
            //         })
            //         ->toggleable(isToggledHiddenByDefault: true),
            // ])

            // فلاتر: نطاق تاريخ (على created_at) و فلتر مستوى (عبر علاقة level في Payment/Student)
            ->filters([
                Filter::make('reg.bankdate')
                    ->form([
                        DatePicker::make('from')
                            ->label(__('reg.startdate'))
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->placeholder('DD/MM/YYYY')
                            ->columnSpan(1),
                        DatePicker::make('to')
                            ->label(__('reg.enddate'))
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->placeholder('DD/MM/YYYY')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn($q, $date) => $q->whereDate('bankdate', '>=', $date))
                            ->when($data['to'] ?? null, fn($q, $date) => $q->whereDate('bankdate', '<=', $date));
                    }),

                SelectFilter::make('level')
                    ->label(__('reg.level'))
                    ->columnSpan(1)
                    ->relationship('level', 'name'),

                SelectFilter::make('batch')
                    ->label(__('reg.batch'))
                    ->columnSpan(1)
                    ->relationship('batch', 'name'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3); // يجعل الجميع في صف واحد

    }
    
    


/**
 * Filament expects a string record key for each table row.
 * We override this to return the faculty_code (or faculty_name) coming from our aggregated query.
 */
public function getTableRecordKey(Model|array $record): string
{
    // إذا كان الصف مصفوفة (مخرَج من select raw) - استخدم المفتاح الموجود فيها
    if (is_array($record)) {
        // حاول استخدام faculty_code أولاً لأنه أكثر احتمالاً أن يكون فريداً ونظيفاً
        if (!empty($record['faculty_code'])) {
            return (string) $record['faculty_code'];
        }

        // كـ fallback استخدم faculty_name أو concat للقيم
        if (!empty($record['faculty_name'])) {
            return (string) $record['faculty_name'];
        }

        // لا نعيد null أبداً — نعيد سلسلة فارغة كقيمة افتراضية (Filament يريد string)
        return '';
    }

    // إذا كان Model (نادر في استعلامات aggregate لكن نغطّيه)
    if ($record instanceof Model) {
        return (string) ($record->faculty_code ?? $record->getKey() ?? '');
    }

    // عام
    return '';
}

}