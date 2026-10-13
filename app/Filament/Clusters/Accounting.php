<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Accounting extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';


    protected static ?string $title = 'main.accounting';
    protected static ?int $navigationSort = 5;
    public static function getNavigationLabel(): string
   {
       return ucfirst(__(static::$title));
   }
    public static function getClusterBreadcrumb(): string
   {
       return ucfirst(__(static::$title));
   }

}
