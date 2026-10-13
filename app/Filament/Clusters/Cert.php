<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Cert extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static ?string $title = 'main.certificate';
    protected static ?int $navigationSort = 3;
    public static function getNavigationLabel(): string
   {
       return ucfirst(__(static::$title));
   }
    public static function getClusterBreadcrumb(): string
   {
       return ucfirst(__(static::$title));
   }

}
