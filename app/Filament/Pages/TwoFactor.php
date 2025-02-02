<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Clusters\Settings;

class TwoFactor extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $cluster = Settings::class;

    protected static string $view = 'filament.pages.two-factor';
}
