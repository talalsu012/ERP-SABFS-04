<?php

namespace App\Filament\Clusters\Results\Resources\Res\CourseResource\RelationManagers;



use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Reg\Batch;
use Filament\Tables\Table;
use App\Models\Result\Subject;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\AttachAction;
use Filament\Resources\RelationManagers\RelationManager;

class SubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'courseSubjects';




    public function form(Form $form): Form
    {
        return $form

            ->schema([
                // Select::make('subject_code')
                // ->label(__('res.subject'))
                //     ->options(Subject::all()->pluck('name_ar', 'code'))
                //     ->searchable()
                //     ->required(),

                Select::make('subject_code')
                    ->label(__('res.subject'))
                    ->options(
                        Subject::all()
                            ->mapWithKeys(fn ($subject) => [
                                $subject->code => "{$subject->code} - {$subject->name_ar}"
                            ])
                    )
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return Subject::query()
                            ->where('code', 'like', "%{$search}%")
                            ->orWhere('name_ar', 'like', "%{$search}%")
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn ($subject) => [
                                $subject->code => "{$subject->code} - {$subject->name_ar}"
                            ]);
                    })
                    ->getOptionLabelUsing(function ($value) {
                        $subject = Subject::find($value);
                        return $subject ? "{$subject->code} - {$subject->name_ar}" : null;
                    })
                    ->required(),

                Select::make('batch_code')
                    ->label(__('reg.batch'))
                    ->options(Batch::all()->pluck('name', 'code'))
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\TextColumn::make('subject.name_ar')
                    ->label(__('res.subject')),
                Tables\Columns\TextColumn::make('batch_code')
                    ->label(__('reg.batch'))
                    ->formatStateUsing(fn ($state) => Batch::find($state)?->name ?? $state),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('إرفاق ' . \__('res.subject')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
