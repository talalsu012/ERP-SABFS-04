<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Results extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'main.results';
    public static function getNavigationLabel(): string
   {
       return ucfirst(__(static::$title));
   }
    public static function getClusterBreadcrumb(): string
   {
       return ucfirst(__(static::$title));
   }
}
