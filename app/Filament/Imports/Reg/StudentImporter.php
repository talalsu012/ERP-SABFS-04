<?php

namespace App\Filament\Imports\Reg;

use App\Models\Reg\Student;

use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class StudentImporter extends Importer
{
    protected static ?string $model = Student::class;

    public static function getColumns(): array
    {
        return [
            // Identification
            self::universityNoColumn(),
            self::nameArColumn(),

            // Academic Information
            self::facultyCodeColumn(),
            self::levelCodeColumn(),
            self::batchCodeColumn(),
             self::student_accountColumn(),

            // Personal Information
            self::nationaltyNoColumn(),
           
            ...self::birthInfoColumns(),
            ...self::addressColumns(),
            ...self::contactColumns(),

            // Financial Information
            ...self::feesColumns(),
        ];
    }

    public function resolveRecord(): ?Student
    {
        return Student::firstOrNew([
            'university_no' => $this->data['university_no'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $successCount = number_format($import->successful_rows);
        $successRows = str('row')->plural($import->successful_rows);

        $body = "Your student import has completed and {$successCount} {$successRows} imported.";

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $failedCount = number_format($failedRowsCount);
            $failedRows = str('row')->plural($failedRowsCount);
            $body .= " {$failedCount} {$failedRows} failed to import.";
        }

        return $body;
    }

    /**************************
     * Column Definitions
     **************************/

    private static function universityNoColumn(): ImportColumn
    {
        return ImportColumn::make('university_no')
            ->label('University Number')
            ->numeric()
            // ->rules(['required', 'integer', 'unique:students,university_no'])
            // ->requiredMapping()
            ->example('20230001');
    }

    private static function nameArColumn(): ImportColumn
    {
        return ImportColumn::make('name_ar')
            ->label('Arabic Name')
            ->rules(['required', 'max:200'])
            // ->requiredMapping()
            ->example('محمد أحمد');
    }

    private static function facultyCodeColumn(): ImportColumn
    {
        return ImportColumn::make('faculty_code')
            ->label('Faculty Code')
            ->numeric()
            ->rules(['required', 'integer'])
            // ->requiredMapping()
            ->example('101');
    }

    private static function levelCodeColumn(): ImportColumn
    {
        return ImportColumn::make('level_code')
            ->label('Level Code')
            ->numeric()
            ->rules(['required', 'integer'])
            // ->requiredMapping()
            ->example('1');
    }

    private static function batchCodeColumn(): ImportColumn
    {
        return ImportColumn::make('batch_code')
            ->label('Batch Code')
            ->numeric()
            ->rules(['required', 'integer'])
            // ->requiredMapping()
            ->example('2023');
    }

    private static function nationaltyNoColumn(): ImportColumn
    {
        return ImportColumn::make('nationaltyno')
            ->label('National ID')
            ->rules(['max:30'])
            ->example('1234567890');
    }
    private static function student_accountColumn(): ImportColumn
    {
        return ImportColumn::make('student_account')
            ->label('StudentAccount')
            ->rules(['max:30'])
            ->example('4002002');
    }

    private static function birthInfoColumns(): array
    {
        return [
            ImportColumn::make('birth_place')
                ->label('Birth Place')
                ->rules(['max:255'])
                ->example('Khartoum'),

            ImportColumn::make('birth_date')
                ->label('Birth Date')
                ->rules(['date'])
                ->example('2000-01-01'),
        ];
    }

    private static function addressColumns(): array
    {
        return [
            ImportColumn::make('city')
                ->label('City')
                ->rules(['max:255'])
                ->example('Khartoum'),

            ImportColumn::make('localty')
                ->label('Locality')
                ->rules(['max:255'])
                ->example('Bahri'),

            ImportColumn::make('block')
                ->label('Block')
                ->rules(['max:255'])
                ->example('10'),

            ImportColumn::make('home_no')
                ->label('Home Number')
                ->rules(['max:255'])
                ->example('123'),
        ];
    }

    private static function contactColumns(): array
    {
        return [
            ImportColumn::make('email')
                ->label('Email')
                ->rules(['email', 'max:255'])
                ->example('student@example.com'),

            ImportColumn::make('mobile')
                ->label('Mobile')
                ->rules(['max:255'])
                ->example('+249123456789'),
        ];
    }

    private static function feesColumns(): array
    {
        return [
            ImportColumn::make('fees')
                ->label('Tuition Fees')
                ->numeric()
                ->rules(['required', 'integer'])
                // ->requiredMapping()
                ->example('5000'),

            ImportColumn::make('reg_fees')
                ->label('Registration Fees')
                ->numeric()
                ->rules(['required', 'integer'])
                // ->requiredMapping()
                ->example('1000'),

            ImportColumn::make('fees1')
                ->label('Additional Fees 1')
                ->numeric()
                ->rules(['integer'])
                ->example('0'),

            ImportColumn::make('fees2')
                ->label('Additional Fees 2')
                ->numeric()
                ->rules(['integer'])
                ->example('0'),

            ImportColumn::make('fees3')
                ->label('Additional Fees 3')
                ->numeric()
                ->rules(['integer'])
                ->example('0'),
        ];
    }
}
