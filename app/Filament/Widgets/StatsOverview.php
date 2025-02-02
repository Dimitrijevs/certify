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
            $stat1Label = 'Users';
            $stat1Description = 'Total number of users in the system';

            $stat2 = School::count();
            $stat2Label = 'Institutions';
            $stat2Description = 'Total number of institutions in the system';

            $stat3 = LearningTest::count();
            $stat3Label = 'Tests';
            $stat3Description = 'Total number of tests in the system';
        } else if (Auth::user()->role_id == 3) {
            $stat1 = User::where('school_id', Auth::user()->school_id)->count();
            $stat1Label = 'Users';
            $stat1Description = 'Total number of users in the institution';

            $stat2 = User::where('group_id', Auth::user()->group_id)->count();
            $stat2Label = 'Group Users';
            $stat2Description = 'Total number of users in the group';

            $stat3 = LearningCertificate::whereIn('user_id', function ($query) {
                $query->select('id')
                    ->from('users')
                    ->where('school_id', Auth::user()->school_id);
            })->count();
            $stat3Label = 'Certificates';
            $stat3Description = 'Total number of assigned certificates in the institution';
        } else {
            $stat1 = User::where('school_id', Auth::user()->school_id)->count();
            $stat1Label = 'Users';
            $stat1Description = 'Total number of users in the institution';

            $stat2 = User::where('group_id', Auth::user()->group_id)->count();
            $stat2Label = 'Group Users';
            $stat2Description = 'Total number of users in the group';

            $stat3 = LearningCertificate::whereIn('user_id', function ($query) {
                $query->select('id')
                    ->from('users')
                    ->where('group_id', Auth::user()->group_id);
            })->count();
            $stat3Label = 'Certificates';
            $stat3Description = 'Total number of assigned certificates in the group';
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
