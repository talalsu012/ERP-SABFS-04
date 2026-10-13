<?php

namespace App\Filament\Clusters\Results\Resources\Result;

use App\Filament\Clusters\Results;
use App\Filament\Clusters\Results\Resources\Result\ResMarkResource\Pages;
use App\Filament\Clusters\Results\Resources\Result\ResMarkResource\RelationManagers;
use App\Filament\Exports\MarksExporter;
use App\Filament\Imports\Result\ResMarkImporter;
use App\Models\Result\ResMark;
use App\Models\Result\ResResons;
use App\Models\Result\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Pages\ListResMarkActivities;



class ResMarkResource extends Resource
{

    protected static ?string $cluster = Results::class;

    protected static ?string $title = 'StudentsMarks';


    // تحديد النموذج الذي سيعمل عليه هذا المورد
    protected static ?string $model = ResMark::class;

    // أيقونة التنقل التي ستظهر في الشريط الجانبي
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    // تسمية عنصر التنقل في الشريط الجانبي
    protected static ?string $navigationLabel = 'Student Marks';

    // تسمية النموذج في صيغة الجمع (تستخدم في العناوين)
    protected static ?string $pluralModelLabel = 'Student Marks';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     return static::getModel()::query()->orderBy('created_at', 'DESC');
    // }

    public static function getNavigationGroup(): string
    {
        return __('main.exam_entry');
    }

    public static function getLabel(): ?string
    {
        return __('res.' . static::$title);
    }

    public static function getPluralLabel(): ?string
    {
        return __('res.' . static::$title);
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__('res.' . static::$title));
    }
    /**
     * تعريف حقول النموذج لعرضها في صفحات الإنشاء والتعديل (Form)
     *
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // حقل اختيار رقم الطالب الجامعي مع علاقة البحث باسم الطالب العربي
                Forms\Components\Select::make('university_no')
                    ->label(__('reg.university_no')) // تسمية الحقل
                    ->relationship('student', 'name_ar') // تحديد العلاقة والعمود الذي سيتم عرضه
                    ->required() // جعل الحقل مطلوبًا
                    ->searchable(), // تمكين البحث في هذا الحقل

                // حقل اختيار رمز المادة مع علاقة البحث باسم المادة العربي
                Forms\Components\Select::make('subject_code')
                    ->label(__('res.subject'))
                    ->relationship('subject', 'name_ar')
                    ->required()
                    ->searchable(),

                // حقل اختيار رمز الكلية مع علاقة البحث باسم الكلية العربي
                Forms\Components\Select::make('faculty_code')
                    ->label(__('reg.faculty'))
                    ->relationship('faculty', 'name')
                    ->required()
                    ->searchable(),

                // حقل اختيار رمز المستوى مع علاقة البحث باسم المستوى العربي
                Forms\Components\Select::make('level_code')
                    ->label(__('reg.level'))
                    ->relationship('level', 'name')
                    ->required()
                    ->searchable(),

                // حقل اختيار رمز الدفعة مع علاقة البحث برمز الدفعة
                Forms\Components\Select::make('batch_code')
                    ->label(__('reg.batch'))
                    ->relationship('batch', 'code') // إذا كان لديك 'name_ar' في نموذج Batch، يمكنك استخدامه هنا
                    ->required()
                    ->searchable(),

                // حقل إدخال للدرجة
                Forms\Components\TextInput::make('mark')
                    ->label(__('res.mark'))
                    ->numeric() // التأكد من أن القيمة رقمية
                    ->required(),

                // حقل إدخال للتقدير
                Forms\Components\TextInput::make('grade')
                    ->label(__('res.grade'))
                    ->required()
                    ->maxLength(255), // تحديد أقصى طول للنص

                // حقل اختيار اسم المادة العربي مع علاقة البحث باسم المادة العربي
                // حقول تبديل للخصائص المنطقية
                Forms\Components\Toggle::make('is_visible')
                    ->label('Is Visible'),
                Forms\Components\Toggle::make('is_locked')
                    ->label('Is Locked'),
                Forms\Components\Toggle::make('is_released')
                    ->label('Is Released'),
                Forms\Components\Toggle::make('is_recheck')
                    ->label('Is Recheck'),
                Forms\Components\Toggle::make('is_recheck_passed')
                    ->label('Is Recheck Passed'),
            ]);
    }

    /**
     * تعريف أعمدة الجدول لعرض البيانات (Table)
     *
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // عمود لرقم الطالب الجامعي من خلال العلاقة 'student'
                Tables\Columns\TextColumn::make('student.university_no')
                    ->label(__('reg.university_no')) // تسمية العمود
                    ->searchable()->toggleable(isToggledHiddenByDefault: false) // تمكين البحث في هذا العمود
                    ->sortable(), // تمكين الفرز

                // عمود لاسم الطالب العربي من خلال العلاقة 'student'
                Tables\Columns\TextColumn::make('student.name_ar')
                    ->label(__('reg.name_ar'))
                    ->searchable()->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),

                // عمود للدرجة
                Tables\Columns\TextColumn::make('mark')
                    ->label(__('res.mark'))->toggleable(isToggledHiddenByDefault: false)
                    ->numeric() // عرض كرقم
                    ->sortable(),

                // عمود للتقدير
                Tables\Columns\TextColumn::make('grade')
                    ->label(__('res.grade'))
                    ->searchable()->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),

                Tables\Columns\TextColumn::make('reason.name')
                    ->label(__('res.reason'))
                    ->searchable()->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),


                // عمود لاسم الكلية العربي من خلال العلاقة المباشرة 'faculty' في ResMark
                Tables\Columns\TextColumn::make('faculty.name')
                    ->label(__('reg.faculty'))
                    ->searchable()->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                // عمود لاسم المستوى العربي من خلال العلاقة المباشرة 'level' في ResMark
                Tables\Columns\TextColumn::make('level.name')
                    ->label(__('reg.level'))
                    ->searchable()->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                // عمود لرمز الدفعة من خلال العلاقة المباشرة 'batch' في ResMark
                Tables\Columns\TextColumn::make('batch.code')
                    ->label(__('reg.batch'))
                    ->searchable()->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                // عمود لاسم المادة العربي من خلال العلاقة 'subject'
                Tables\Columns\TextColumn::make('subject.name_ar')
                    ->label(__('res.subject'))
                    ->searchable()->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                // أعمدة الطوابع الزمنية (مخفية افتراضياً)
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // فلتر لعرض السجلات المحذوفة (Soft Deletes)
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('faculty_code')
                    ->relationship('faculty', 'name')
                    ->label(__('reg.faculty')),
                Tables\Filters\SelectFilter::make('level_code')
                    ->relationship('level', 'name')
                    ->label(__('reg.level')),
                Tables\Filters\SelectFilter::make('batch_code')
                    ->relationship('batch', 'code')
                    ->label(__('reg.batch')),

                Tables\Filters\SelectFilter::make('grade')
                    ->options(function () {
                        return ResMark::query()
                            ->select('grade')
                            ->distinct()
                            ->pluck('grade', 'grade')
                            ->toArray();
                    })
                    ->multiple()
                    ->searchable()
                    ->label(__('res.grade')),

                Tables\Filters\SelectFilter::make('subject')
                    ->attribute('subject_code')
                    ->label(__('res.subject'))
                    ->options(function () {
                        return Subject::with(['faculty', 'level'])
                            ->get()
                            ->mapWithKeys(function ($subject) {
                                $facultyName = $subject->faculty?->name ?? 'Unknown Staff';
                                $levelName = $subject->level?->name ?? 'Unknown Group';


                                return [
                                    $subject->code => sprintf(
                                        '%s | %s | %s | %s',
                                        $subject->name_ar,
                                        $subject->code,
                                        $facultyName,
                                        $levelName
                                    )
                                ];
                            });
                    })

                    ->searchable()
                    // ->searchDebounce(500) // Add debounce for better performance
                    ->getSearchResultsUsing(function (string $search) {
                        return Subject::with(['faculty', 'level'])
                            ->where('name_ar', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%")
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($subject) {
                                $facultyName = $subject->faculty?->name ?? 'Unknown Faculty';
                                $levelName = $subject->level?->name ?? 'Unknown Level';

                                return [
                                    $subject->code => sprintf(
                                        '%s | %s | %s | %s',
                                        $subject->name_ar,
                                        $subject->code,
                                        $facultyName,
                                        $levelName
                                    )
                                ];
                            });
                    })
                    ->columnSpan(3),

                QueryBuilder::make()
                    ->constraints([
                        NumberConstraint::make('mark')
                            ->label(__('res.mark'))
                            ->integer()
                            ->nullable(),
                        SelectConstraint::make('grade')
                            ->options(
                                ResMark::query()
                                    ->select('grade')
                                    ->distinct()
                                    ->pluck('grade', 'grade')
                                    ->toArray()

                            )
                            ->multiple(),

                        SelectConstraint::make('reson_code')
                            ->options(
                                ResResons::pluck('name', 'code')

                            )
                            ->multiple(),

                    ]),


            ])
            ->filtersFormColumns(3)
            ->filtersFormWidth('4xl')
            ->actions([
                // إجراءات الصف (تعديل، حذف، استعادة، حذف قسري)
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(MarksExporter::class)
                    ->label('تصدير الدرجات')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('danger'),
            ])
            ->extremePaginationLinks()
            ->bulkActions([
                // إجراءات التحديد المتعدد (حذف، استعادة، حذف قسري)
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // يمكنك تعريف مدراء العلاقات هنا إذا كان لديك علاقات تريد عرضها
        ];
    }

    /**
     * تعريف مسارات الصفحات للمورد
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResMarks::route('/'),
            'create' => Pages\CreateResMark::route('/create'),
            'edit' => Pages\EditResMark::route('/{record}/edit'),
            'activities' => Pages\ListResMarksActivites::route('/{record}/activities'),

        ];
    }

    /**
     * تعديل الاستعلام الأساسي للمورد
     * (تستخدم لجلب البيانات بكفاءة عن طريق Eager Loading)
     *
     * @return Builder
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class, // تعطيل نطاق الحذف الناعم إذا كان موجودًا
            ])
            // تحميل العلاقات اللازمة لتجنب مشكلة N+1 queries
            ->with(['student', 'subject', 'faculty', 'level', 'batch']);
    }
}
