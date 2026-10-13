<?php

namespace App\Filament\Clusters\attendances\Pages\Reports;

use Filament\Pages\Page;
use App\Filament\Clusters\Results;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class att_reports extends Page
{

    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.clusters.attendances.pages.reports.att_reports';

    protected static ?string $cluster = Results::class;
    protected static ?int $navigationSort = 6;

    protected static ?string $title = 'Reports';

    public static function getNavigationGroup(): string
    {
        return __('Attendance.Attendance');
    }


    public static function getLabel(): ?string
    {
        return __("Attendance.report");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Attendance.report");
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__("Attendance.report"));
    }

}
