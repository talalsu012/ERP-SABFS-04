<?php

namespace App\Filament\Resources\Reg;

use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Reg\Student;

use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Reg\StudentResource\Pages;
use App\Filament\Resources\Reg\StudentResource\FormSchema;
use App\Filament\Resources\Reg\StudentResource\TableSchema;
use App\Filament\Resources\Reg\StudentResource\RelationManagers;

class StudentResource extends Resource
{


    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';



    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()
            ->orderBy('created_at', 'DESC');
    }

    public static function getLabel(): ?string
    {
        return __("reg.student");
    }

    public static function getPluralLabel(): ?string
    {
        return __("reg.students");
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__("reg.students"));
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canEdit(Model $record): bool
    {
        return true;
    }

    public static function canDelete(Model $record): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form->schema(FormSchema::getSchema());
    }

    public static function table(Table $table): Table
    {
        return TableSchema::getTableSchema($table)
            ->bulkActions(TableSchema::getBulkActions());
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PaymentRelationManager::class,
            RelationManagers\MarksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'view' => Pages\ViewStudent::route('/{record}/view'),
             'activities' => Pages\ListStudentsActivites::route('/{record}/activities'),
        ];
    }
}