<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserPurchase;
use Livewire\WithPagination;
use App\Models\LearningCategory;
use App\Models\LearningResource;
use Illuminate\Support\Facades\Auth;
use App\Models\LearningUserStudyRecord;

class ShowMyLearningList extends Component
{
    use WithPagination;

    public $progresses = [];

    public function userBoughtCourse()
    {
        $courseIds = UserPurchase::where('user_id', Auth::id())
            ->whereNotNull('course_id')
            ->pluck('course_id')
            ->toArray();

        $courses = LearningCategory::whereIn('id', $courseIds)
            ->orderBy('created_at', 'desc')
            ->with([
                'learningResources' => function ($query) {
                    $query->where('is_active', true);
                }
            ])
            ->paginate(5);

        foreach ($courses as $course) {
            $this->progresses[$course->id] = $this->progress($course->id);
        }

        return $courses;
    }

    public function progress($category_id)
    {
        $user = Auth::user()->id;
        $resources = LearningResource::where('category_id', $category_id)->where('is_active', true)->get();

        $total_resources = count($resources);
        $seen_resources = 0;

        foreach ($resources as $resource) {
            $is_seen = LearningUserStudyRecord::where('user_id', $user)
                ->where('resource_id', $resource->id)
                ->exists();

            if ($is_seen) {
                $seen_resources++;
            }
        }

        if ($total_resources == 0) {
            return 0;
        }

        $progress = ($seen_resources / $total_resources) * 100;

        return round($progress);
    }

    public function render()
    {
        $courses = $this->userBoughtCourse();

        return view('livewire.show-my-learning-list', [
            'courses' => $courses,
            'progresses' => $this->progresses,
        ]);
    }
}
