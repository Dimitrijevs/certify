<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use App\CertificateRequirementsLogic;

class CertificateRequirements extends Widget
{
    use CertificateRequirementsLogic;

    public static function canView(): bool
    {
        return Auth::user()->group_id ? true : false;
    }

    protected int|string|array $columnSpan = 'full';

    public string $place = 'dashboard';

    protected static string $view = 'filament.widgets.certificate-requirements';
}
