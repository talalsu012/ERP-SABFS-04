<?php

namespace App\Livewire\Res;

use Livewire\Component;
use Livewire\Attributes\On;

class PrintHeader extends Component
{
    public $fac= [];

    #[On('ShowSubjectHeaderFn')]
    public function setHeaderValue($parHeaderTablePrint= [])
    {
        $this->fac = $parHeaderTablePrint;
     }
    public function render()
    {
        return view('livewire.res.print-header');
    }
}
