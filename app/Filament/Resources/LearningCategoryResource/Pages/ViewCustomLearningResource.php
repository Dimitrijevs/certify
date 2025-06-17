<?php

namespace App\Filament\Resources\LearningCategoryResource\Pages;

use App\Models\UserPurchase;
use Filament\Actions\Action;
use App\Models\LearningCategory;
use App\Models\LearningResource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\LearningUserStudyRecord;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use App\Filament\Resources\LearningCategoryResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class ViewCustomLearningResource extends Page
{
    use InteractsWithRecord;

    protected static string $resource = LearningCategoryResource::class;

    public $resources;

    public function mount(int|string $record)
    {
        $this->record = LearningResource::findOrFail($record);

        if (!$this->checkPurchase()) {
            Notification::make()
                ->title(__('learning/learningCertificate.you_dont_have_permissions'))
                ->danger()
                ->send();

            return redirect()->route('filament.app.resources.learning-categories.course-welcome-page', [
                'record' => $this->record->category_id,
            ]);
        }

        $this->resources = LearningResource::where('category_id', $this->record->category_id)->get();
    }

    public function checkPurchase()
    {
        $allow_access = UserPurchase::where('user_id', Auth::id())
            ->where('course_id', $this->record->category_id)
            ->exists();

        if (!$allow_access) {
            $allow_access = LearningCategory::where('id', $this->record->category_id)
                ->where('created_by', Auth::id())
                ->exists();
        }

        return $allow_access;
    }

    public function getTitle(): string
    {
        return false;
    }

    public function getParent()
    {
        return $this->record->category;
    }

    public function getThumbnail($value)
    {
        return Storage::url($value);
    }

    public function getResources()
    {
        return LearningResource::where('category_id', $this->record->category_id)->get();
    }

    public function getUserActivity()
    {
        $user = Auth::user()->id;
        $resources = LearningResource::where('category_id', $this->record->category_id)->where('is_active', true)->get(['id', 'name']);

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

    public function getPreviousResource()
    {
        $resources = LearningResource::where('category_id', $this->record->category_id)->where('is_active', 1)
            ->orderBy('id')
            ->get(['id', 'name']);

        $currentIndex = $resources->search(function ($item) {
            if ($item->id == $this->record->id) {
                return $item;
            }
        });

        if ($currentIndex > 0) {
            $previousResource = $resources[$currentIndex - 1];
            return $previousResource;
        }

        return null;
    }

    public function getNextResource()
    {
        $resources = LearningResource::where('category_id', $this->record->category_id)->where('is_active', 1)
            ->orderBy('id')
            ->get(['id', 'name']);

        $currentIndex = $resources->search(function ($item) {
            if ($item->id == $this->record->id) {
                return $item;
            }
        });

        if ($currentIndex !== false && $currentIndex < $resources->count() - 1) {
            $nextResource = $resources[$currentIndex + 1];
            return $nextResource;
        }

        return null;
    }

    protected function getResourceAction()
    {
        return Action::make('Resource edit')
            ->url(fn() => CustomEditResource::getUrl(['record' => $this->record->id], isAbsolute: false));
    }

    protected function getCategoryAction()
    {
        $category_id = LearningResource::where('id', $this->record->id)->value('category_id');
        $record = LearningCategory::findOrFail($category_id);
        return Action::make('Category edit')
            ->url(fn() => EditLearningCategory::getUrl(['record' => $record->id], isAbsolute: false));
    }

    public function getProgress()
    {
        $user = Auth::user()->id;
        $resources = LearningResource::where('category_id', $this->record->category_id)->where('is_active', true)->get();
        $total = count($resources);

        $completed = 0;
        foreach ($resources as $resource) {
            if (
                LearningUserStudyRecord::where('user_id', $user)
                    ->where('resource_id', $resource->id)
                    ->exists()
            ) {
                $completed += 1;
            }
        }

        if ($total != 0) {
            $percents = round($completed / $total, 2) * 100;

            return $percents;
        } else {
            return 0;
        }
    }

    protected static string $view = 'filament.resources.learning-category-resource.pages.view-custom-learning-resource';
}
