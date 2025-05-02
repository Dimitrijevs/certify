<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\LearningUserStudyRecord;

class TimeSpentOnCourse extends ChartWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Study Time';

    protected static ?string $maxHeight = '260px';

    protected function getFilters(): ?array
    {
        return [
            '7' => 'Last 7 days',
            '14' => 'Last 14 days',
            '30' => 'Last 30 days',
            '60' => 'Last 2 months',
            '90' => 'Last 3 months',
            '180' => 'Last 6 months',
            '365' => 'Last 12 months',
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
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $now)
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
                    $dateKey = $record->created_at->format('M d');
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
                    $monthKey = $record->created_at->format('M Y');
                    if (array_key_exists($monthKey, $timeSpent)) {
                        $timeSpent[$monthKey] += round(($record->time_spent / 60), 2);
                    }
                }
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Time Spent (minutes)',
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
                        'text' => 'Minutes'
                    ]
                ],
            ],
        ];
    }
}
