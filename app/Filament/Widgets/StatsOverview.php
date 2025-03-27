<?php

namespace App\Filament\Widgets;

use App\Models\LearningCertificate;
use App\Models\User;
use App\Models\School;
use App\Models\LearningTest;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        if (Auth::user()->role_id < 3) {
            $stat1 = User::count();
            $stat1Label = __('other.users');
            $stat1Description = __('other.total_number_of_users_in_the_system');

            $stat2 = School::count();
            $stat2Label = __('other.institutions');
            $stat2Description = __('other.total_number_of_institutions_in_the_system');

            $stat3 = LearningTest::count();
            $stat3Label = __('other.tests');
            $stat3Description = __('other.total_number_of_tests_in_the_system');
        } else if (Auth::user()->role_id == 3) {
            $stat1 = User::where('school_id', Auth::user()->school_id)->count();
            $stat1Label = __('other.users');
            $stat1Description = __('other.total_number_of_users_in_the_institution');

            $stat2 = User::where('group_id', Auth::user()->group_id)->count();
            $stat2Label = __('other.group_users');
            $stat2Description = __('other.total_number_of_users_in_the_group');

            $stat3 = LearningCertificate::whereIn('user_id', function ($query) {
                $query->select('id')
                    ->from('users')
                    ->where('school_id', Auth::user()->school_id);
            })->count();
            $stat3Label = __('other.Certificates');
            $stat3Description = __('other.total_number_of_assigned_certificates_in_the_institution');
        } else {
            $stat1 = User::where('school_id', Auth::user()->school_id)->count();
            $stat1Label = __('other.institution_users');
            $stat1Description = __('other.total_number_of_users_in_the_institution');

            $stat2 = User::where('group_id', Auth::user()->group_id)->count();
            $stat2Label = __('other.group_users');
            $stat2Description = __('other.total_number_of_users_in_the_group');

            $stat3 = LearningCertificate::whereIn('user_id', function ($query) {
                $query->select('id')
                    ->from('users')
                    ->where('group_id', Auth::user()->group_id);
            })->count();
            $stat3Label = __('other.certificates');
            $stat3Description = __('other.total_number_of_assigned_certificates_in_the_group');
        }

        return [
            Stat::make($stat1Label, $stat1)
                ->chart([1, 1, 1])
                ->description($stat1Description)
                ->color('success'),
            Stat::make($stat2Label, $stat2)
                ->chart([1, 1, 1])
                ->description($stat2Description)
                ->color('success'),
            Stat::make($stat3Label, $stat3)
                ->chart([1, 1, 1])
                ->description($stat3Description)
                ->color('success'),
        ];
    }
}
