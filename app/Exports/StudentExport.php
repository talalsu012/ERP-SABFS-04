<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery; 
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;

class StudentExport implements  FromView
{
    protected Builder $query;
    
    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function view(): View
    {
        return view('exports.student', [
            'students' => $this->query->get()
        ]);
    }
}