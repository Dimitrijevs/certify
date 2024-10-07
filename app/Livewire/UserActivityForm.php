<?php

namespace App\Livewire;

use DateTime;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\LearningResource;
use Illuminate\Support\Facades\Auth;
use App\Models\LearningUserStudyRecord;

class UserActivityForm extends Component
{
    public $record;

    // for video player 
    public $video_duration; // total video duration
    public $video_progress; // latest watched time
    public $video_watched; // total seconds spent on video
    public $start_time; // start time of the user activity

    public $video_start; // get data from db to set video start

    public function mount(int | string $record): void
    {
        $this->record = LearningResource::findOrFail($record);
        $this->sendVideoTime();
    }
    
    public function render()
    {
        return view('livewire.user-activity-form');
    }

    #[On("update-video-duration")]
    public function updateVideoDuration($videoDuration): void
    {
        // $hours = floor($videoDuration / 3600);
        // $minutes = floor(($videoDuration / 60) % 60);
        // $seconds = $videoDuration % 60;

        // $this->video_duration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        $this->video_duration = $videoDuration;
    }

    #[On("update-video-progress")]
    public function updateVideoProgress($latestWatchedTime): void
    {
        // $hours = floor($latestWatchedTime / 3600);
        // $minutes = floor(($latestWatchedTime / 60) % 60);
        // $seconds = $latestWatchedTime % 60;

        // $this->video_progress = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        $this->video_progress = $latestWatchedTime;
    }

    #[On("update-video-watched")]
    public function updateVideoWatched($timeWatched): void
    {
        // $hours = floor($timeWatched / 3600);
        // $minutes = floor(($timeWatched / 60) % 60);
        // $seconds = $timeWatched % 60;

        // $this->video_watched = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        $this->video_watched = $timeWatched;
    }

    #[On("start-time")]
    public function updateStartTime($startTime): void
    {
        $start_time = date('Y-m-d H:i:s', strtotime($startTime));
        $this->start_time = new DateTime($start_time);
    }

    // send previous video progress to the video player
    public function sendVideoTime(): void
    {
        $user = Auth::user()->id;
        $userActivities = LearningUserStudyRecord::where('user_id', $user)
            ->where('resource_id', $this->record->id)
            ->get();

        if ($userActivities->isEmpty()) {
            $this->video_start = 0;
        }

        $latest_point = 0;
        foreach ($userActivities as $activity) {
            if ($activity->video_progress > $latest_point) {
                $latest_point = $activity->video_progress;
            }
        }

        $this->video_start = $latest_point;

        $this->dispatch('video-start', latestPoint: $this->video_start);
    }

    public function recordLeave($resource_id): void
    {
        $finish_time = new DateTime();
        $start_time = $this->start_time->format('Y-m-d H:i:s');

        $interval = $finish_time->diff($this->start_time);
        $totalSeconds = ($interval->days * 24 * 60 * 60) + ($interval->h * 60 * 60) + ($interval->i * 60) + $interval->s;
        // $totalHours = $interval->days * 24 + $interval->h;
        // $formattedInterval = sprintf('%02d:%02d:%02d', $totalHours, $interval->i, $interval->s);

        LearningUserStudyRecord::create([
            'user_id' => Auth::user()->id,
            'category_id' => $this->record->category->id,
            'resource_id' => $resource_id,
            'started_at' => $start_time,
            'finished_at' => $finish_time,
            'time_spent' => $totalSeconds,
            'video_watched' => round($this->video_watched) ?? null,
            'video_progress' => round($this->video_progress) ?? null,
            'video_duration' => round($this->video_duration) ?? null,
        ]);
    }
}
