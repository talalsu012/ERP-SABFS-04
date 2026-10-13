<?php

namespace App\Filament\Resources\Reg\StudentResource;

use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Reg\Batch;
use App\Models\Reg\Level;
use App\Models\Reg\Faculty;
use App\Models\Reg\Special;
use Illuminate\Support\Str;
use App\Models\Reg\Lt_column;
use App\Models\Reg\Department;
use App\Traits\HasFacultyScope;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Stichoza\GoogleTranslate\GoogleTranslate;

class FormSchema
{
    use HasFacultyScope;
    public static function getSchema(): array
    {
        return [
            Tabs::make(__('reg.basic_info'))
                ->tabs([
                    Tab::make(__('reg.basic_info'))
                        ->schema([
                            Split::make([
                                Section::make([
                                    TextInput::make('name_ar')
                                    ->label(__('reg.name_ar'))
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Set $set, ?string $state) {
                                        // Translate Arabic to English
                                        $translated = (new GoogleTranslate())
                                                ->setSource('ar')
                                                ->setTarget('en')
                                                ->translate($state);

                                        $set('name_en', $translated);

                                        // Generate slug
                                        // $set('slug', Str::slug($translated));
                                    })
                                // ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                ,
                                TextInput::make('name_en')
                                    ->label(__('reg.name_en'))
                                    ->required()
                                    ->maxLength(255),
                                // TextInput::make('slug')
                                //     ->label(__('reg.slug'))
                                //     ->required(),


                                ]),
                                Section::make([
                                    TextInput::make('university_no')
                                        ->label(__('reg.university_no'))
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(20)->required(),

                                    TextInput::make('indexno')
                                        ->label(__('reg.indexno'))
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(20),

                                      
                                        
                                        
                                ])->grow(false),
                            ])->from('md')->columnSpanFull(),

                            Fieldset::make()
                                ->schema([
                                    Select::make('faculty_code')
                                        ->label(__('reg.faculty'))->required()
                                         ->options(Faculty::get()->pluck('name', 'code'))
                                       // ->options(fn () => static::getUserFacultyOptions())
                                        ->live()->native()->searchable(),

                                    Select::make('department_code')->label(__('reg.department'))
                                        ->options(fn (Get $get): Collection => Department::query()
                                            ->where('faculty_code', $get('faculty_code'))
                                            ->pluck('name', 'code'))->live()->searchable(),

                                    Select::make('special_code')
                                        ->label(__('reg.special'))
                                        ->options(fn (Get $get): Collection => Special::query()
                                            ->where('department_code', $get('department_code'))
                                            ->pluck('name', 'code'))->searchable(),

                                    Select::make('level_code')
                                        ->label(__('reg.level'))
                                        ->options(Level::all()->pluck('name', 'code'))
                                        ->searchable()
                                        ->preload(),

                                    Select::make('stady_code')
                                        ->label(__('reg.stady'))
                                        ->native()
                                        ->options(Lt_column::all()
                                            ->where('grouped', '2006')
                                            ->pluck('name', 'code'))
                                        ->selectablePlaceholder(false),

                                    Select::make('batch_code')
                                        ->label(__('reg.batch'))->required()
                                        ->relationship('batch', 'name')
                                        ->searchable()
                                        ->createOptionForm([
                                            TextInput::make('code')->label(__('reg.code'))
                                                ->unique(ignoreRecord: true)->maxLength(20)->required(),
                                            TextInput::make('name')->label(__('reg.name'))->maxLength(50)->required(),
                                        ])
                                        ->createOptionAction(function (Action $action) {
                                            return $action
                                                ->modalHeading(__('reg.batches'))
                                                ->modalSubmitActionLabel('Create ' . __('reg.batch'))
                                                ->modalWidth('sm');
                                        }),

                                    ToggleButtons::make('admission_code')
                                        ->label(__('reg.admission'))
                                        ->inline()
                                        ->options(Lt_column::all()
                                            ->where('grouped', '2001')
                                            ->pluck('name', 'code'))
                                        ->required(),

                                    Select::make('academic_year_code')
                                        ->label(__('reg.academic_year'))
                                        ->selectablePlaceholder(false)
                                        ->native()
                                        ->options(Lt_column::all()
                                            ->where('grouped', '2003')
                                            ->pluck('name', 'code')),

                                    Select::make('status_code')
                                        ->label(__('reg.status_code'))
                                        ->selectablePlaceholder(false)
                                        ->native()
                                        ->options(Lt_column::all()
                                            ->where('grouped', '2004')
                                            ->pluck('name', 'code')),
                                            
                                    Select::make('student_account')
                                        ->label(__('reg.student_account'))
                                        ->native()
                                        ->options(Lt_column::all()
                                            ->where('grouped', '4002')
                                            ->pluck('name', 'code'))
                                        ->selectablePlaceholder(true),        
                                ])
                                ->columns(3),
                        ])->columns(3),

                    Tab::make(__('reg.personal_info'))
                        ->schema([
                            Split::make([
                                Section::make([
                                    Select::make('nationality_code')
                                        ->label(__('reg.nationality'))
                                        ->native()
                                        ->options(Lt_column::all()
                                            ->where('grouped', '1002')
                                            ->pluck('name', 'code'))
                                        ->required()
                                        ->selectablePlaceholder(false)
                                        ->searchable(),

                                    Radio::make('gender_code')->inline()
                                        ->options(Lt_column::all()
                                            ->where('grouped', '1001')
                                            ->pluck('name', 'code'))
                                        ->required()
                                        ->label(__('reg.gender')),

                                    TextInput::make('birth_place')->label(__('reg.birth_place'))->required(),

                                    DatePicker::make('birth_date')->label(__('reg.birth_date'))
                                        ->native(false)
                                        ->displayFormat('d/m/Y')
                                        ->required()
                                        ->placeholder('MM/DD/YYYY'),
                                ])->columns(2),
                                FileUpload::make('photo')
                                    ->label(__('reg.image'))
                                    ->image()
                                    ->avatar()
                                    ->imageEditor()
                                    ->circleCropper()
                                    ->disk('public')
                                    ->directory('std'),
                            ])->from('xl'),

                            Fieldset::make()
                                ->schema([
                                    Fieldset::make(__('reg.address'))
                                        ->schema([
                                            TextInput::make('nationaltyno')
                                                ->label(__('reg.nationaltyno'))
                                                ->unique(ignoreRecord: true)
                                                ->maxLength(20)->required(),

                                            TextInput::make('email')->label(__('reg.email'))->email()->required()
                                                ->unique(ignoreRecord: true),
                                            TextInput::make('mobile')->label(__('reg.phone'))->tel()
                                                ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                                            TextInput::make('social_media')->label(__('reg.social_media'))
                                                ->suffixIcon('heroicon-m-ellipsis-horizontal-circle'),
                                            TextInput::make('activites')->label(__('reg.activites')),
                                            TextInput::make('skill')->label(__('reg.skill')),

                                            Select::make('state_code')
                                                ->options(Lt_column::all()
                                                    ->where('grouped', '1003')
                                                    ->pluck('name', 'code'))
                                                ->label(__('reg.state'))->native(),
                                            TextInput::make('city')->label(__('reg.city')),
                                            TextInput::make('localty')->label(__('reg.localty')),
                                            TextInput::make('block')->label(__('reg.block')),
                                            TextInput::make('home_no')->label(__('reg.homeno')),
                                        ])->columns(4),

                                    Fieldset::make('parent')->label(__('reg.guardian'))
                                        ->schema([
                                            TextInput::make('parent')->label(__('reg.guardian_name')),
                                            Toggle::make('parent_alive')->inline(false)->label(__('reg.father_alive')),
                                            TextInput::make('parent_job')->label(__('reg.parent_job')),
                                            TextInput::make('parent_address')->label(__('reg.parent_address')),
                                            TextInput::make('parent_contacts')->label(__('reg.parent_contacts')),

                                            TextInput::make('mother')->label(__('reg.mother_name')),
                                            Toggle::make('mother_alive')->inline(false)->label(__('reg.mother_alive')),
                                            TextInput::make('mother_job')->label(__('reg.mother_job')),
                                            TextInput::make('mother_address')->label(__('reg.mother_address')),
                                            TextInput::make('mother_contacts')->label(__('reg.mother_contacts')),
                                        ])->columns(4),
                                ])->columns(2)
                        ]),

                    Tab::make(__('reg.fees'))
                        ->schema([
                            Fieldset::make(__('reg.fees_students'))
                                ->schema([
                                    TextInput::make('reg_fees')
                                        ->label(__('reg.regfees'))
                                        ->numeric()
                                        ->required(),

                                    TextInput::make('fees')
                                        ->label(__('reg.fees'))
                                        ->numeric()->required(),

                                    ToggleButtons::make('currency')
                                        ->inline()
                                        ->options([
                                            'SDG' => 'SDG',
                                            'USD' => 'USD',
                                            'SAR' => 'SAR'
                                        ])
                                        ->colors([
                                            'SDG' => 'info',
                                            'USD' => 'warning',
                                            'SAR' => 'success',
                                        ])
                                        ->icons([
                                            'SDG' => 'heroicon-o-pencil',
                                            'USD' => 'heroicon-o-clock',
                                            'SAR' => 'heroicon-o-check-circle',
                                        ])
                                        ->label(__('reg.currency'))->default('SDG')
                                        ->required(),

                                    TextInput::make('fees1')
                                        ->label(__('reg.fees1'))
                                        ->numeric()->default(0)->required(),

                                    TextInput::make('fees2')
                                        ->label(__('reg.fees2'))
                                        ->numeric()->default(0)->required(),

                                    TextInput::make('fees3')
                                        ->label(__('reg.fees3'))
                                        ->numeric()->default(0)->required(),
                                ])->columns(3),

                            Fieldset::make(__('reg.nonfees_students'))
                                ->schema([
                                    TextInput::make('fees6')
                                        ->label(__('reg.fees6'))
                                        ->numeric()->default(0)->required(),

                                    TextInput::make('fees7')
                                        ->label(__('reg.fees7'))
                                        ->numeric()->default(0)->required(),

                                    TextInput::make('fees8')
                                        ->label(__('reg.fees8'))
                                        ->numeric()->default(0)->required(),

                                    Select::make('discount_percent')
                                        ->label(__('reg.discount_percent'))
                                        ->native()
                                        ->options(Lt_column::all()
                                            ->where('grouped', '2008')
                                            ->pluck('name', 'code'))
                                        ->columnSpan(2)
                                ])->columns(6),

                            Fieldset::make(__('reg.Installments'))
                                ->schema([
                                    Select::make('installment_code')
                                        ->label(__('reg.Installments'))
                                        ->native()
                                        ->options(Lt_column::all()
                                            ->where('grouped', '2005')
                                            ->pluck('name', 'code')),
                                    TextInput::make('pay1')->label(__('reg.pay1'))->numeric()->required()->default(0)->disabled(),
                                    TextInput::make('pay2')->label(__('reg.pay2'))->numeric()->required()->default(0)->disabled(),
                                    TextInput::make('pay3')->label(__('reg.pay3'))->numeric()->required()->default(0)->disabled(),
                                    TextInput::make('pay4')->label(__('reg.pay4'))->numeric()->required()->default(0)->disabled(),
                                ])->columns(5),
                        ]),

                    Tab::make(__('reg.notes'))
                        ->schema([
                            MarkdownEditor::make('note')
                                ->toolbarButtons([
                                    'attachFiles',
                                    'bold',
                                    'h2',
                                    'h3',
                                    'strike',
                                    'underline',
                                    'undo',
                                ])
                        ]),
                ])->columnSpanFull()
        ];
    }
}