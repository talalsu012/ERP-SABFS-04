<?php

namespace App\Filament\Clusters\Reg\Resources\Reg;


use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Reg\Batch;
use App\Models\Reg\Level;
use Filament\Tables\Table;
use App\Models\Reg\Faculty;
use App\Models\Reg\Payment;
use App\Models\Reg\Student;
use Illuminate\Support\Str;
use App\Filament\Clusters\Reg;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Grouping\Group;
use Illuminate\Contracts\View\View;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Actions\ForceDeleteAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Reg\Resources\Reg\PaymentResource\Pages;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;



class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Reg::class;

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        // return static::getModel()::count();
        $sum = static::getModel()::sum('amount');

        return number_format($sum, 2) . __('reg.currency_sdg'); // Format to 2 decimal places with currency symbol
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()->orderBy('created_at', 'DESC');
    }
    public static function getLabel(): ?string
    {
        return __("reg.payments");
    }

    public function getTitle(): string | Htmlable
    {
        return __('');
    }

    public static function getPluralLabel(): ?string
    {
        return __("reg.payments");
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__("reg.payments"));
    }


    protected function getHeaderWidgets(): array
    {
        return [
            // admission::class,
            // RegisterChart::class,
        ];
    }

    // This will make the create action appear in the navigation



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('university_no')
                    ->label(__('reg.name'))->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Set $set) {
                        if (!empty($state)) {

                            $prefix = 'SABFS'; // University prefix

                            $studentId = $state; // assuming student_id is a field in your form
                            $date = now()->format('Ymd');
                            $prefix = 'VOUCH'; // or make this configurable
                            $serial = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

                            $voucher_no = "{$prefix}-{$date}-{$studentId}-{$serial}";
                            $set('voucher_no', $voucher_no);


                            // Get student and set payment amount
                            $student = Student::where('university_no', $state)->first();

                            if ($student) {
                                // Check which payment field has value and set amount
                                if ($student->pay1 > 0) {
                                    $set('amount', $student->pay1);
                                } elseif ($student->pay2 > 0) {
                                    $set('amount', $student->pay2);
                                } elseif ($student->pay3 > 0) {
                                    $set('amount', $student->pay3);
                                } elseif ($student->pay4 > 0) {
                                    $set('amount', $student->pay4);
                                }

                                if($student->level_code > 0) {
                                    $set('level_code', $student->level_code);
                                    $set('batch_code', $student->batch_code);
                                    $set('branch', 'Local');
                                }
                            }
                        }
                    })
                    ->getSearchResultsUsing(fn (string $search): array => Student::query()
                        ->where('name_ar', 'like', "{$search}%")
                        ->orWhere('university_no', '=', "{$search}")
                        ->limit(50)
                        ->get()
                        ->mapWithKeys(fn (Student $student) => [
                            $student->university_no => "{$student->university_no} - {$student->name_ar}"
                        ])
                        ->toArray())
                    ->getOptionLabelUsing(fn ($value): ?string => ($student = Student::find($value))
                        ? "{$student->university_no} - {$student->name_ar}"
                        : null)
                    ->live()
                    ->native()
                    ->searchable()
                    ->columnspan(2),

                Forms\Components\TextInput::make('amount')
                    ->label(__('reg.amount'))
                    ->required()
                    ->numeric(),
                ToggleButtons::make('currency')
                    ->label(__('reg.currency'))
                    ->inline()
                    ->options([
                        'SDG' => 'SDG',
                        'USD' => 'USD',
                        'TRN' => 'TRN',
                    ])->default('SDG')
                    ->required(),

                Forms\Components\TextInput::make('voucher_no')
                    ->unique(ignoreRecord: true)
                    ->required()->label(__('reg.voucher_no'))
                   // ->disabled()
                    ->dehydrated(),

                ToggleButtons::make('sys_type')
                    ->inline()->label(__('reg.deposit'))
                    ->grouped()
                    ->options([
                        'REG' => 'REG',
                        'CRT' => 'CRT',
                        'OTH' => 'OTH',
                    ])->default('REG')
                    ->required(),

                Forms\Components\DatePicker::make('bankdate')
                    ->label(__('reg.bankdate'))
                    ->default(now())
                    ->required(),



                ToggleButtons::make('bank')
                    ->inline()->label(__('reg.deposit'))
                    ->grouped()
                    ->options([
                        'CASH' => 'CASH',
                        'CHECK' => 'CHECK',
                    ])->default('CASH')
                    ->required(),

                Select::make('level_code')
                    ->label(__('reg.level'))
                    ->options(Level::all()->pluck('name', 'code'))
                    ->searchable()
                    ->preload(),

                Select::make('batch_code')
                    ->label(__('reg.batch'))->required()
                    ->relationship('batch', 'name')
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('branch')
                    ->maxLength(255)->label(__('reg.branch'))
                    ->default(null),



            ])->columns(4);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->heading(__('reg.payments'))
            ->description(__('reg.paymentDescription'))
            ->columns([
                Tables\Columns\TextColumn::make('university_no')
                    ->label(__('reg.university_no'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.name_ar')
                    ->label(__('reg.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('reg.amount'))
                    ->formatStateUsing(function ($state) {
                        return number_format($state, 2, '.', ',');
                    })
                    ->summarize(
                        Sum::make()
                            ->formatStateUsing(function ($state) {
                                return number_format($state, 2, '.', ',');
                            })
                    )
                    // ->format(fn ($state): string => number_format($state, 2) . ' SDG')
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.faculty.name')
                    ->label(__('reg.faculty'))
                    // ->summarize(Sum::make())
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch.name')
                    ->label(__('reg.batch'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('level.name')
                    ->label(__('reg.level'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('voucher_no')
                    ->label(__('reg.voucher_no'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('bankdate')
                    ->date()->label(__('reg.bankdate'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('bank')
                    ->label(__('reg.bank'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('branch')
                    ->label(__('reg.branch'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency')
                    ->label(__('reg.currency'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('sys_type')
                    ->label(__('reg.sys_type'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('reg.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->deferLoading()
            ->striped()
            ->groups([
                Group::make('created_at')
                    ->date()
                    ->label(__('reg.bankdate')),
                Group::make('bank')
                    ->label(__('reg.bank')),
            ])

            ->filters([
                TrashedFilter::make(),
                QueryBuilder::make()
                    ->constraints([
                        SelectConstraint::make('student.faculty_code')
                            ->label(__('reg.faculty'))
                            ->options(Faculty::all()
                                ->pluck('name', 'code')),
                        SelectConstraint::make('level_code')->label(__('reg.level'))
                            ->options(Level::all()->pluck('name', 'code')),
                        SelectConstraint::make('batch_code')->label(__('reg.batch'))
                            ->options(Batch::all()
                                ->pluck('name', 'code')),
                        TextConstraint::make('student.name_ar')
                            ->label(__('reg.name_ar'))
                            ->icon('heroicon-m-user-plus'),
                        TextConstraint::make('student.bank')
                            ->label(__('reg.bank'))
                            ->icon('heroicon-m-user-plus'),
                        TextConstraint::make('voucher_no')
                            ->label(__('reg.voucher_no'))
                            ->icon('heroicon-m-user-plus'),
                        NumberConstraint::make('amount')
                            ->label(__('reg.amount'))
                            ->icon('heroicon-m-currency-dollar'),
                        DateConstraint::make('bankdate')
                            ->label(__('reg.bankdate'))
                            ->icon('heroicon-m-currency-dollar'),
                    ])
                    ->constraintPickerColumns(2),
            ])->filtersFormColumns(2)
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                    // Action::make('print')
                    //     ->label('Print')

                    //     ->url(fn (Payment $record): string => route('filament.admin.reg.resources.reg.payments.print', $record))
                    //     ->color('success')
                    //     ->icon('heroicon-o-chart-bar'),
                    Action::make('print')
                        ->label('Print')
                        ->modalContent(fn (Payment $record): View => view(
                            'print-payment-test',
                            ['record' => $record],
                        ))
                        // ->slideOver()
                        ->modalWidth(MaxWidth::Medium)
                        // ->url(fn (Payment $record): string => route('filament.admin.reg.resources.reg.payments.print', $record))
                        //E:\pro\erp12\resources\views\print-payment-test.blade.php
                        ->color('success')
                        ->icon('heroicon-o-chart-bar'),

                ])
            ], position: ActionsPosition::BeforeCells)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
            'print' => Pages\PrintPayment::route('/{record}/print'),

        ];
    }
}