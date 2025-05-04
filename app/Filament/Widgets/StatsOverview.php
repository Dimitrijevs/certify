<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\School;
use App\Models\LearningTest;
use App\Models\UserPurchase;
use App\Models\LearningCertificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\LearningUserStudyRecord;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $cacheKey = 'stats.widget.' . Auth::id();

        $statsData = Cache::remember($cacheKey, 60 * 30, function () {
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
                $timeInSeconds = LearningUserStudyRecord::where('user_id', Auth::id())->sum('time_spent');

                $minutes = floor($timeInSeconds / 60);
                $seconds = $timeInSeconds % 60;
                $stat1 = sprintf('%d:%02d', $minutes, $seconds);
                $stat1Label = 'Total Time Spent';
                $stat1Description = 'Your total time spent on learning';

                $stat2 = UserPurchase::where('user_id', Auth::user()->id)->count();
                $stat2Label = 'Your Purchases';
                $stat2Description = 'Total number of purchases you made';

                $stat3 = LearningCertificate::where('user_id', Auth::user()->id)->count();
                $stat3Label = 'Your Certificates';
                $stat3Description = 'Total number of certificates you received';
            }

            return [
                'stat1' => $stat1 ?? null,
                'stat1Label' => $stat1Label ?? null,
                'stat1Description' => $stat1Description ?? null,
                'stat2' => $stat2 ?? null,
                'stat2Label' => $stat2Label ?? null,
                'stat2Description' => $stat2Description ?? null,
                'stat3' => $stat3 ?? null,
                'stat3Label' => $stat3Label ?? null,
                'stat3Description' => $stat3Description ?? null,
            ];
        });

        return [
            Stat::make($statsData['stat1Label'], $statsData['stat1'])
                ->chart([1, 1, 1])
                ->description($statsData['stat1Description'])
                ->color('success'),
            Stat::make($statsData['stat2Label'], $statsData['stat2'])
                ->chart([1, 1, 1])
                ->description($statsData['stat2Description'])
                ->color('success'),
            Stat::make($statsData['stat3Label'], $statsData['stat3'])
                ->chart([1, 1, 1])
                ->description($statsData['stat3Description'])
                ->color('success'),
        ];
    }
}
