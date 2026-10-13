<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class attendances extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 4;
    protected static ?string $title = 'Attendance.Attendance';
    public static function getNavigationLabel(): string
   {
       return ucfirst(__(static::$title));
   }
    public static function getClusterBreadcrumb(): string
   {
       return ucfirst(__(static::$title));
   }
}
