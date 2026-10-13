<?php

namespace App\Filament\Clusters\Results\Pages\Res;

use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Services\ResultService;
use Filament\Support\Enums\MaxWidth;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use App\Filament\Clusters\Results\Pages\Res\BaseEntryMark;
use App\Filament\Clusters\Results\Pages\Res\EntryMarkForm;
use App\Filament\Clusters\Results\Pages\Res\EntryMarkTable;

class EntryMark extends BaseEntryMark
{


   // protected static ?string $title = 'entry_mark';


    protected ResultService $resultService;

    public function getSubNavigation(): array
    {
        return [];
    }
    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    public function mount(): void
    {
        $this->resultService = app(ResultService::class);
    }

    use EntryMarkForm {
        form as parentForm;
    }

    use EntryMarkTable {
        table as parentTable;
    }

    public function form(Form $form): Form
    {
        return $this->parentForm($form);
    }

    public function table(Table $table): Table
    {
        return $this->parentTable($table);
    }
}
