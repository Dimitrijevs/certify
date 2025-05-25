<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\LearningUserStudyRecord;
use Illuminate\Contracts\Support\Htmlable;

class TimeSpentOnCourse extends ChartWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '260px';

    public function getHeading(): string | Htmlable | null
    {
        return __('other.study_time');
    }

    protected function getFilters(): ?array
    {
        return [
            '7' => __('other.last_7_days'),
            '14' => __('other.last_14_days'),
            '30' => __('other.last_30_days'),
            '60' => __('other.last_2_months'),
            '90' => __('other.last_3_months'),
            '180' => __('other.last_6_months'),
            '365' => __('other.last_12_months'),
        ];
    }

    protected function getData(): array
    {
        if (is_null($this->filter)) {
            $this->filter = 365;
        }

        $userId = Auth::id();
        $filterDays = (int) $this->filter;

        // Use a cache key unique to this user AND the current filter
        $cacheKey = "time_spent_chart_data_user_{$userId}_{$filterDays}";

        // Return cached data if available (expires after 30 minutes)
        return Cache::remember($cacheKey, 60 * 30, function () use ($userId, $filterDays) {
            $now = now();
            $startDate = now()->subDays($filterDays);

            $studyRecords = LearningUserStudyRecord::where('user_id', $userId)
                ->whereDate('started_at', '>=', $startDate)
                ->whereDate('started_at', '<=', $now)
                ->get();

            $labels = [];
            $timeSpent = [];

            // Choose format based on filter length
            $useDailyFormat = $filterDays <= 30;

            if ($useDailyFormat) {
                // Daily format for 30 days or less
                for ($i = $filterDays - 1; $i >= 0; $i--) {
                    $day = now()->subDays($i);
                    $dateKey = $day->format('M d');
                    $labels[] = $dateKey;
                    $timeSpent[$dateKey] = 0;
                }

                foreach ($studyRecords as $record) {
                    $dateKey = Carbon::parse($record->started_at)->format('M d');
                    if (array_key_exists($dateKey, $timeSpent)) {
                        $timeSpent[$dateKey] += round(($record->time_spent / 60), 2);
                    }
                }
            } else {
                // Monthly format for longer periods
                // Calculate how many months to show based on days
                $monthsToShow = ceil($filterDays / 30);

                for ($i = $monthsToShow - 1; $i >= 0; $i--) {
                    $month = now()->subMonths($i);
                    $monthKey = $month->format('M Y');
                    $labels[] = $monthKey;
                    $timeSpent[$monthKey] = 0;
                }

                foreach ($studyRecords as $record) {
                    $monthKey = Carbon::parse($record->started_at)->format('M Y');
                    if (array_key_exists($monthKey, $timeSpent)) {
                        $timeSpent[$monthKey] += round(($record->time_spent / 60), 2);
                    }
                }
            }

            return [
                'datasets' => [
                    [
                        'label' => __('other.time_spent_minutes'),
                        'data' => array_values($timeSpent),
                        'fill' => false,
                        'borderColor' => '#2563eb',
                        'tension' => 0.4
                    ],
                ],
                'labels' => $labels,
            ];
        });
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 90,
                    'ticks' => [
                        'stepSize' => 10,
                    ],
                    'title' => [
                        'display' => true,
                        'text' => __('other.minutes'),
                    ]
                ],
            ],
        ];
    }
}
