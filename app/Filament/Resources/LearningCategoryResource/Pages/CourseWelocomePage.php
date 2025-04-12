<?php

namespace App\Filament\Resources\LearningCategoryResource\Pages;

use Filament\Actions\Action;
use App\Models\LearningResource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\LearningUserStudyRecord;
use App\Filament\Resources\LearningCategoryResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class CourseWelocomePage extends Page
{
    use InteractsWithRecord;

    protected static string $resource = LearningCategoryResource::class;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label('Edit')
                ->url(route('filament.app.resources.learning-categories.edit', ['record' => $this->record->id]))
                ->icon('tabler-edit')
                ->color('primary')
                ->visible(function () {
                    return Auth::user()->role_id < 3 || Auth::user()->id == $this->record->created_by;
                }),
        ];
    }

    public function getTitle(): string
    {
        return $this->record->name;
    }

    public function userActivity()
    {
        $user = Auth::id();
        $resources = LearningResource::where('category_id', $this->record->id)->where('is_active', true)->get(['id', 'name']);

        $activities = [];

        foreach ($resources as $resource) {
            $is_seen = LearningUserStudyRecord::where('user_id', $user)
                ->where('resource_id', $resource->id)
                ->exists();

            $activity = new \stdClass();
            $activity->id = $resource->id;
            $activity->name = $resource->name;
            $activity->is_seen = $is_seen;
            $activity->is_current = $resource->id == $this->record->id;

            $activities[] = $activity;
        }

        return $activities;
    }

    public function getTotalPrice()
    {
        $price = $this->record->price ?? 0;
        $discount = $this->record->discount ?? 0;

        if ($price == 0 || $discount == 100) {
            return 0;
        }

        // Calculate discounted price
        if ($discount > 0) {
            $discountedPrice = $price - ($price * $discount / 100);

            return round($discountedPrice, 2);
        }

        return round($price, 2);
    }

    protected static string $view = 'filament.resources.learning-category-resource.pages.course-welocome-page';
}
