<?php

namespace App\Filament\Resources\LearningCategoryResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\LearningCategoryResource;

class EditLearningCategory extends EditRecord
{
    protected array $widgets = [];

    protected static string $resource = LearningCategoryResource::class;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        if ($this->record->created_by != Auth::id() && Auth::user()->role_id > 3) {
            Notification::make()
                ->title(__('learning/learningCategory.you_are_not_autorized_to_edit_this_course'))
                ->danger()
                ->send();

            $this->redirect($this->previousUrl);
        }

        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();

        $this->widgets = [
            [
                'label' => __('learning/learningResource.visits'),
                'icon' => 'tabler-eye',
                'value_type' => 'integer',
                'value' => 0,
            ],
            [
                'label' => __('learning/learningResource.users'),
                'icon' => 'tabler-users-group',
                'value_type' => 'integer',
                'value' => 0,
            ],
            [
                'label' => __('learning/learningResource.average_time_spent'),
                'icon' => 'tabler-clock',
                'value_type' => 'time',
                'value' => 0,
            ]
        ];

        $this->calculateViews();
        $this->calculateUsers();
        $this->calculateAverageTimeSpent();
    }

    protected function calculateViews()
    {
        $this->widgets[0]['value'] = Cache::remember('learning_category_' . $this->record->id . '_views', 60 * 10, function () {
            return $this->record->activities()->count();
        });
    }

    protected function calculateUsers()
    {
        $this->widgets[1]['value'] = Cache::remember('learning_category_' . $this->record->id . '_users', 60 * 10, function () {
            return $this->record->activities()->distinct('user_id')->count();
        });
    }

    protected function calculateAverageTimeSpent()
    {
        $this->widgets[2]['value'] = Cache::remember('learning_category_' . $this->record->id . '_average_time_spent', 60 * 10, function () {
            $totalTime = $this->record->activities()->sum('time_spent');
            $userCount = $this->record->activities()->distinct('user_id')->count();

            if ($userCount > 0) {
                return round($totalTime / $userCount, 2);
            }

            return 0;
        });
    }

    public function getTitle(): string|Htmlable
    {
        return __('learning/learningCategory.edit_course');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('verify')
                ->label(__('learning/learningCategory.verify'))
                ->visible(function () {
                    if (Auth::user()->role_id < 3 && $this->record->is_public == false) {
                        return true;
                    }

                    return false;
                })
                ->color('primary')
                ->icon('tabler-circle-check')
                ->action(function () {
                    $this->record->is_public = true;
                    $this->record->aproved_by = Auth::id();
                    $this->record->saveQuietly();

                    return Notification::make()
                        ->title(__('learning/learningCategory.course_verified'))
                        ->success()
                        ->send();
                }),
            Action::make('unverify')
                ->label(__('learning/learningCategory.unverify'))
                ->visible(function () {
                    if (Auth::user()->role_id < 3 && $this->record->is_public == true) {
                        return true;
                    }

                    return false;
                })
                ->color('danger')
                ->icon('tabler-circle-x')
                ->action(function () {
                    $this->record->is_public = false;
                    $this->record->aproved_by = Auth::id();
                    $this->record->saveQuietly();

                    return Notification::make()
                        ->title(__('learning/learningCategory.course_unverified'))
                        ->success()
                        ->send();
                }),
            Action::make('save')
                ->label(__('learning/learningTest.form.save_changes'))
                ->action('save')
                ->icon('tabler-checkbox'),
            Actions\DeleteAction::make()
                ->icon('tabler-trash'),
        ];
    }
}
