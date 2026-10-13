<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Reg extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'main.registration';
    public static function getNavigationLabel(): string
   {
       return ucfirst(__(static::$title));
   }
    public static function getClusterBreadcrumb(): string
   {
       return ucfirst(__(static::$title));
   }
}
