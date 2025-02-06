<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Settings extends Cluster
{
    protected static ?string $navigationGroup = 'Additional';

    public static function getNavigationLabel(): string
    {
        return __('other.settings');
    }
}
