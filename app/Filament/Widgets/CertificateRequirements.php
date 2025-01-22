<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CertificateRequirements extends Widget
{
    protected int | string | array $columnSpan = 'full';
    
    public string $place = 'dashboard';

    public function getRequirements()
    {
        $user = Auth::user();
        $cacheKey = "user_$user->id";

        return Cache::remember($cacheKey, 60 * 2, function () use ($user) {
            $userRequirements = $user->certification_requirement ?? [];

            $groupRequirements = optional($user->group)->certification_requirement ?? [];
            $schoolRequirements = optional($user->school)->certification_requirement ?? [];

            $allRequirements = collect($userRequirements)
                ->merge($groupRequirements)
                ->merge($schoolRequirements)
                ->unique('test_id');

            return $allRequirements;
        });
    }

    public function checkCertificate($test)
    {
        $user = Auth::user();

        if (is_null($user)) {
            return null;
        }

        $userCertificates = $user->certificates;

        if ($userCertificates->isEmpty()) {
            return null;
        }

        $certificate = $userCertificates->where('test_id', $test)->first();

        return $certificate;
    }

    public function checkCertificateType($tests)
    {
        foreach ($tests as $test) {
            $certificate = $this->checkCertificate($test->id);
            if (!$certificate) {
                return false;
            }
        }

        return true;
    }
    
    protected static string $view = 'filament.widgets.certificate-requirements';
}
