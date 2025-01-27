<?php

namespace App\Filament\Widgets;

use App\CertificateRequirementsLogic;
use Filament\Widgets\Widget;

class CertificateRequirements extends Widget
{
    use CertificateRequirementsLogic;

    protected int | string | array $columnSpan = 'full';
    
    public string $place = 'dashboard';
    
    protected static string $view = 'filament.widgets.certificate-requirements';
}
