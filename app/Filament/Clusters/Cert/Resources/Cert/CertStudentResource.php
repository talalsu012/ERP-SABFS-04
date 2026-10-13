<?php

namespace App\Filament\Clusters\Cert\Resources\Cert;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Reg\Faculty;
use App\Models\Reg\Special;
use Illuminate\Support\Str;
use App\Models\Reg\Lt_column;
use App\Models\Reg\Department;
use Pages\ListOrderActivities;
use App\Filament\Clusters\Cert;
use App\Models\Cert\CertSetting;
use App\Models\Cert\CertStudent;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Alkoumi\LaravelHijriDate\Hijri;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Filament\Tables\Actions\ForceDeleteAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Clusters\Cert\Resources\Cert\CertStudentResource\Pages;
use App\Filament\Clusters\Cert\Resources\Cert\CertStudentResource\RelationManagers;
use App\Filament\Clusters\Cert\Resources\Cert\CertStudentResource\Pages\EditCertStudent;
use App\Filament\Clusters\Cert\Resources\Cert\CertStudentResource\Pages\ViewCertStudent;
use App\Filament\Clusters\Cert\Resources\Cert\CertStudentResource\Pages\ListCertStudents;
use App\Filament\Clusters\Cert\Resources\Cert\CertStudentResource\Pages\CreateCertStudent;

class CertStudentResource extends Resource
{
    protected static ?string $model = CertStudent::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Cert::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $titles = 'cert.students';
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
        return __('main.certificate');
    }

    public static function getLabel(): ?string
    {
        return __(static::$titles);
    }

    public static function getPluralLabel(): ?string
    {
        return __(static::$titles);
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__(static::$titles));
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return true;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make([

                ])
                    ->schema([
                        Split::make([
                            Section::make([
                                Forms\Components\TextInput::make('universityno')
                                    ->label(__('reg.university_no'))
                                    ->maxLength(255)
                                    ->default(null),

                                TextInput::make('name_ar')
                                    ->label(__('cert.name_ar'))
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Set $set, ?string $state) {
                                        // Translate Arabic to English
                                        $translated = (new GoogleTranslate())->setSource('ar')->setTarget('en')->translate($state);
                                        $set('name_en', $translated);

                                        // Generate slug
                                        $set('slug', Str::slug($translated));
                                    })
                                // ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::upper(Str::slug($state))))
                                ,
                                TextInput::make('name_en')
                                    ->label(__('cert.name_en'))
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('slug')
                                    ->label(__('cert.slug'))
                                    ->required(),
                            ]),
                            Section::make([
                                Select::make('admission_code')
                                    ->label(__('reg.admission'))
                                    ->options(
                                        CertSetting::where('groups', 'admission')
                                            ->pluck('name_ar', 'id')
                                    ),
                                Select::make('academic_year_code') // Assuming 'grade_id' is the foreign key
                                    ->label(__('reg.academic_year'))
                                    ->options(Lt_column::all()
                                        ->where('grouped', '2003')
                                        ->pluck('name', 'code'))
                                    ->required(),

                                Select::make('stady_code') // Assuming 'grade_id' is the foreign key
                                    ->label(__('reg.stady'))
                                    ->options(Lt_column::all()
                                        ->where('grouped', '2006')
                                        ->pluck('name', 'code'))
                                    ->required(),

                                Select::make('nationalty_code')
                                    ->label(__('reg.nationality'))
                                    ->native()
                                    ->options(Lt_column::all()
                                        ->where('grouped', '1002')
                                        ->pluck('name', 'code'))
                                    ->required()
                                    ->selectablePlaceholder(false)
                                    ->searchable(),
                            ]),
                            Section::make([
                                Select::make('degree_id') // Assuming 'grade_id' is the foreign key
                                    ->label(__('reg.degree'))
                                    ->options(
                                        CertSetting::where('groups', 'degree')
                                            ->pluck('name_ar', 'id') // Replace 'name' with the column you want to display
                                    )
                                    ->searchable()
                                    ->required(),
                                Select::make('grade_id') // Assuming 'grade_id' is the foreign key
                                    ->label(__('reg.grade'))
                                    ->options(
                                        CertSetting::where('groups', 'grade')
                                            ->pluck('name_ar', 'id') // Replace 'name' with the column you want to display
                                    )
                                    ->searchable()
                                    ->required(),

                                Select::make('faculty_code')
                                    ->label(__('cert.faculty'))
                                    ->options(Faculty::pluck('name', 'code'))
                                    ->required(),

                                Select::make('department_code')->label(__('reg.department'))
                                    ->options(fn (Get $get): Collection => Department::query()
                                        ->where('faculty_code', $get('faculty_code'))
                                        ->pluck('name', 'code'))->live()->searchable(),

                                Select::make('special_code')
                                    ->label(__('reg.special'))
                                    ->options(fn (Get $get): Collection => Special::query()
                                        ->where('department_code', $get('department_code'))
                                        ->pluck('name', 'code'))->searchable(),

                            ]),
                            Section::make([
                                Radio::make('gender_code')->inline()
                                    ->options(Lt_column::all()
                                        ->where('grouped', '1001')
                                        ->pluck('name', 'code'))
                                    ->required()
                                    ->label(__('reg.gender')),

                                Forms\Components\TextInput::make('meeting_no')
                                    ->label(__('reg.meeting_no'))
                                    ->maxLength(255)
                                    ->default(null),


                                Forms\Components\DatePicker::make('alumia_date')
                                    ->label(__('reg.alumia_date'))
                                    ->native(false)
                                    ->displayFormat('d F Y')
                                    ->locale('ar')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        // Convert Gregorian to Hijri when date changes
                                        $hijriDate = Hijri::MediumDate($state);
                                        $set('hijri_date', $hijriDate);
                                    }),


                                TextInput::make('hijri_date')
                                    ->label('Hijri Date')
                                    // ->readOnly()
                                    ->dehydrated(),

                            ])->grow(false),
                        ])->from('lg')
                    ]),



                Forms\Components\TextInput::make('remarks_en')
                    ->label(__('reg.remarks_en'))
                    ->maxLength(255)
                    ->default(null),

                Forms\Components\TextInput::make('remarks')
                    ->label(__('reg.remarks'))
                    ->maxLength(255)
                    ->default(null),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('universityno')
                    ->label(__('reg.university_no'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('reg.name_ar'))
                    ->size(TextColumn\TextColumnSize::Large)
                    // ->description(fn (CertStudent $record): string => $record->name_en, position: 'above')
                    ->description(fn (CertStudent $record): string => $record->name_en)
                    ->searchable(),

                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('reg.name_en'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('alumia_date')
                    ->label(__('reg.alumia_date'))
                    ->description(function (CertStudent $record): string {
                        return "{$record->Year}  {$record->hijri_date}";
                    })
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('year')
                    ->label(__('reg.year'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('hijri_date')
                    ->label(__('reg.hijri_date'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('admission.name')
                    ->label(__('reg.admission'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('academicyear.name')
                    ->label(__('reg.academic_year'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stady.name')
                    ->label(__('reg.stady'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('degree.name_ar')
                    ->label(__('reg.degree'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grade.name_ar')
                    ->label(__('reg.grade'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('faculty.name')
                    ->label(__('reg.faculty'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name_ar')
                    ->label(__('reg.department'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('special.name_ar')
                    ->label(__('reg.special_code'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nationalty.name')
                    ->label(__('reg.nationality'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('meeting_no')
                    ->label(__('reg.meeting_no'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('remarks')
                    ->label(__('reg.remarks'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('remarks_en')
                    ->label(__('reg.remarks_en'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('reg.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('reg.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('reg.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                     
                    // Add an action to view the certificate
                // Tables\Actions\Action::make('view_certificate')
                // ->label('View Certificate')
                // ->url(fn (CertStudent $record): string => static::getUrl('certificate', ['record' => $record]))
                // ->icon('heroicon-o-document-text'),

                    RestoreAction::make(),
                    ForceDeleteAction::make(),

		 Tables\Actions\Action::make('affidavit')
                        ->label(__('الافادة عربي'))
                        ->icon('heroicon-o-document-arrow-down')
                        ->url(fn (CertStudent $record): string => route('certificates.affidavit', [
                            'certificate' => $record->id,
                            'lang' => 'ar' // Default to Arabic
                        ]))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('affidavit')
                        ->label(__('الافادة انجليزي'))
                        ->icon('heroicon-o-document-arrow-down')
                        ->url(fn (CertStudent $record): string => route('certificates.affidavit', [
                            'certificate' => $record->id,
                            'lang' => 'en' // Default to English
                        ]))
                        ->openUrlInNewTab(),
                        
                    Tables\Actions\Action::make('generate')
                        ->label(__('cert.general_en'))
                        ->icon('heroicon-o-document-arrow-down')
                        ->url(fn (CertStudent $record): string => route('certificates.generate', [
                            'certificate' => $record->id,
                            'lang' => 'en' // Default to English
                        ]))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('generateArabic')
                        ->label(__('cert.general_ar'))
                        ->icon('heroicon-o-document-arrow-down')
                        ->url(fn (CertStudent $record): string => route('certificates.generate', [
                            'certificate' => $record->id,
                            'lang' => 'ar'
                        ]))
                        ->openUrlInNewTab()
                        ->visible(fn (CertStudent $record): bool => !empty($record->name_ar)),
                    Tables\Actions\Action::make('detailsArabic')
                        ->label(__('cert.detail_ar'))
                        ->icon('heroicon-o-document-arrow-down')
                        ->url(fn (CertStudent $record): string => route('transcript.details', [
                            'universityNo' => $record->universityno,
                            'lang' => 'ar'
                        ]))
                        ->openUrlInNewTab()
                        ->visible(fn (CertStudent $record): bool => !empty($record->name_ar)),
                    Tables\Actions\Action::make('detailsEnglish')
                        ->label(__('cert.detail_en'))
                        ->icon('heroicon-o-document-arrow-up')
                        ->url(fn (CertStudent $record): string => route('transcript.details', [
                            'universityNo' => $record->universityno,
                            'lang' => 'en'
                        ]))
                        ->openUrlInNewTab()
                        ->visible(fn (CertStudent $record): bool => !empty($record->name_en)),
                ]),
            ], position: ActionsPosition::BeforeColumns)
            ->filters([
                TrashedFilter::make(),
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
            'index' => Pages\ListCertStudents::route('/'),
            'create' => Pages\CreateCertStudent::route('/create'),
            // 'view' => Pages\ViewCertStudent::route('/{record}'),
            'edit' => Pages\EditCertStudent::route('/{record}/edit'),
            // Register the custom certificate page
            'certificate' => Pages\ViewCertificate::route('/{record}/certificate'),
        ];
    }
}