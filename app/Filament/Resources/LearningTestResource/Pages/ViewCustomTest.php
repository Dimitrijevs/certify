<?php

namespace App\Filament\Resources\LearningTestResource\Pages;

use App\Models\LearningTest;
use Filament\Actions\Action;
use App\Models\LearningCategory;
use App\Models\LearningTestResult;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\LearningTestResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class ViewCustomTest extends Page
{
    use InteractsWithRecord;

    protected static string $resource = LearningTestResource::class;

    public function mount(int | string $record): void
    {
        $this->record = LearningTest::findOrFail($record);
    }

    public function getTitle(): string
    {
        return __('learning/learningTest.label');
    }

    protected function getHeaderActions(): array
    {
        if (auth()->user()->can('update_learning::test')) {
            return [
                Action::make('edit')
                    ->label(__('learning/learningTest.form.edit'))
                    ->color('gray')
                    ->icon('tabler-eye-edit')
                    ->url(LearningTestResource::getUrl('edit', ['record' => $this->record->id])),
            ];
        }

        return [];
    }

    public function getCategoryName($id): string
    {
        $name = LearningCategory::findOrFail($id)->name;
        return $name;
    }

    public function cooldownFinished()
    {
        if (is_null($this->record->cooldown)) {
            return true;
        } else {
            $lastAttempt = LearningTestResult::where('user_id', Auth::user()->id)
                ->where('test_id', $this->record->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if (is_null($lastAttempt)) {
                return true;
            } else {
                $cooldown = $this->record->cooldown;

                $lastAttemptTime = $lastAttempt->created_at;
                $cooldownTime = $lastAttemptTime->addMinutes($cooldown);
                return now() > $cooldownTime;
            }
        }
    }

    protected static string $view = 'filament.resources.learning-test-resource.pages.view-custom-test';
}
