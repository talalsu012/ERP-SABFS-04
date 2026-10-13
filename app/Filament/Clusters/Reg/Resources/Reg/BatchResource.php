<?php

namespace App\Filament\Clusters\Reg\Resources\Reg;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Reg\Batch;
use Filament\Tables\Table;
use App\Models\Result\Course;
use App\Filament\Clusters\Reg;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Reg\Resources\Reg\BatchResource\Pages;
use App\Filament\Clusters\Reg\Resources\Reg\BatchResource\RelationManagers;

class BatchResource extends Resource
{
    protected static ?string $model = Batch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Reg::class;

    protected static ?int $navigationSort = 7;
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
        return __('main.registration');
    }

    public static function getLabel(): ?string
    {
        return __("reg.batch");
    }

    public static function getPluralLabel(): ?string
    {
        return __("reg.batches");
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__("reg.batches"));
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
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make([
                        TextInput::make('code')->label(__('reg.code'))
                            ->unique(ignoreRecord: true)->maxLength(20)->required(),
                        TextInput::make('name')->label(__('reg.name'))->maxLength(50)->required(),
                        DatePicker::make('batch_start')->label(__('reg.start_date'))->required(),
                        DatePicker::make('batch_end')->label(__('reg.end_date'))->required(),
                        Select::make('course_code')->options(Course::all()->pluck('name', 'code'))
                            ->label(__('res.course')),

                        //     TextInput::make('company_name')
                        //     ->live(onBlur: true)
                        //     ,
                        // TextInput::make('vat_number')
                        //     ->visible(fn (Get $get): bool => filled($get('company_name')))
                        //     ->label(__('reg.vat_number'))
                        //     ->maxLength(15),
                        //     TextInput::make('contact_person')
                        //     ->label(__('reg.contact_person')),
                        //     TextInput::make('contact_phone')
                        //     ->label(__('reg.contact_phone')),
                    ])->columns(2)
                ])->columnSpan(['lg' => 2]),
                Group::make()->schema([
                    Section::make([
                        Placeholder::make('created_at')->label(__('reg.created_at'))
                            ->content(fn (Batch $record): ?string => $record->created_at?->diffForHumans())
                            ->hidden(fn (?Batch $record) => $record === null),
                        Placeholder::make('updated_at')->label(__('reg.updated_at'))
                            ->content(fn (Batch $record): ?string => $record->updated_at?->diffForHumans())
                            ->hidden(fn (?Batch $record) => $record === null),
                        // Forms\Components\View::make('batches')
                        //     ->label(__('reg.batches'))
                        //     ->viewData([
                        //         'batches' => Batch::query()->first()
                        //             // ->where('code', $form->getState('code'))
                        //             // ->when($form->getState('code'), fn($query) => $query->where('code', '!=', $form->getState('code')))
                        //             ->get(),
                        //     ])
                        //     ->view('filament.components.batches'),
                    ])
                ]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('reg.name'))->searchable(),
                TextColumn::make('course.name')->label(__('reg.course')),
                TextColumn::make('batch_start')->label(__('reg.start_date')),
                TextColumn::make('batch_end')->label(__('reg.end_date')),
                TextColumn::make('is_active')->label(__('reg.visibel')),
            ])

            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->native(false)->label(__('reg.creation_date_from')),
                        DatePicker::make('created_until')->native(false)->label(__('reg.creation_date_to'))->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->selectable()
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()->iconButton(),
                    Tables\Actions\DeleteAction::make()->iconButton(),
                    // Tables\Actions\StaMark::make()->iconButton(),
                    // Action::make('copyToSelected')
                    //     ->accessSelectedRecords()
                    //     ->action(function (Batch $record, Collection $selectedRecords) {
                    //         $selectedRecords->each(
                    //             fn (Batch $selectedRecord) => $selectedRecord->update([
                    //                 'is_active' => $record->is_active,
                    //             ]),
                    //         );
                    //     }),
                    // Tables\Actions\ReplicateAction::make()
                    //     ->excludeAttributes(['created_at', 'updated_at'])
                    //     ->beforeReplicaSaved(function (Batch $replica): void {
                    //         // Generate new code
                    //         do {
                    //             $newCode = $replica->getOriginal('code') . rand(10, 9999);
                    //         } while (Batch::where('code', $newCode)->exists());

                    //         $replica->code = $newCode;

                    //         // Verify course exists
                    //         if ($replica->course_code && !Course::where('code', $replica->course_code)->exists()) {
                    //             // Either unset the course_code or set a default
                    //             $replica->course_code = null;
                    //             // Or throw an exception with a helpful message
                    //             // throw new \Exception("Referenced course does not exist");
                    //         }
                    //     })
                    // ->after(static function (Transaction $original, Transaction $replica) {
                    //     $original->journalEntries->each(function (JournalEntry $entry) use ($replica) {
                    //         $entry->replicate([
                    //             'transaction_id',
                    //         ])->fill([
                    //             'transaction_id' => $replica->id,
                    //         ])->save();
                    //     });
                    // })

                ])
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
            'index' => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'edit' => Pages\EditBatch::route('/{record}/edit'),
        ];
    }
}
