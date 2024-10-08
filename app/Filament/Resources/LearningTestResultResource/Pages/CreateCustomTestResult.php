<?php

namespace App\Filament\Resources\LearningTestResultResource\Pages;

use Carbon\Carbon;
use Livewire\Attributes\On;
use App\Models\LearningTest;
use Filament\Actions\Action;
use App\Models\LearningTestAnswer;
use App\Models\LearningTestResult;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\LearningTestResultResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class CreateCustomTestResult extends Page
{
    use InteractsWithRecord;

    protected static string $resource = LearningTestResultResource::class;

    public bool $view_test;
    public ?Model $result = null;
    public ?string $user_answer = null;
    public Carbon|string|null $start_time = null;
    public int $position;
    public bool $transition_enabled = false;
    public ?Model $current_question = null;


    protected function getHeaderActions(): array
    {
        if ($this->view_test) {
            return [
                Action::make('view')
                    ->label(__('learning/learningTestResult.form.completed_tests'))
                    ->icon('tabler-checkbox')
                    ->color('gray')
                    ->url(LearningTestResultResource::getUrl('index')),
            ];
        }

        return [];
    }

    public function getBreadcrumbs(): array
    {
        if ($this->view_test) {
            $resource = __('learning/learningTestResult.label_plural');
            $resource_link = '/learning-test-results';
            $test = __('learning/learningTestResult.label');
        } else {
            $resource = __('learning/learningTest.label_plural');
            $resource_link = '/learning-tests';
            $test = __('learning/learningTest.requirements.test');
        }

        return [
            $resource_link => $resource,
            null => $test,
        ];
    }

    public function getTitle(): string | Htmlable
    {
        if ($this->view_test) {
            return __('learning/learningTestResult.label');
        } else {
            return __('learning/learningTest.requirements.test');
        }
    }

    public function mount(int|string $record, int $question, bool $viewTest)
    {
        // if user can watch test
        $this->view_test = $viewTest;

        // position in array of test questions
        $this->position = $question - 1;

        // Fetch the test record
        if ($this->view_test) {
            $this->result = LearningTestResult::where('user_id', Auth::id())
                ->where('id', $record)
                ->whereNotNull('finished_at')
                ->with('details')
                ->first();

            $this->record = $this->result->test;
        } else {
            $this->record = LearningTest::findOrFail($record);
            $this->result = $this->getOrCreateTestResult();
        }

        // **Add this check to prevent out-of-bounds access**
        $totalQuestions = $this->record->details->count();

        if ($this->position >= $totalQuestions) {
            // Redirect to the first question
            return redirect()->route('filament.app.resources.learning-test-results.do-test', [
                'record' => $this->record->id,
                'question' => 1,
                'viewTest' => $this->view_test ? 1 : 0,
            ]);
        }

        // Continue with the rest of your code
        if ($this->record->is_question_transition_enabled || $this->view_test) {
            $this->transition_enabled = true;

            $questionId = $this->record->details->get($this->position)?->id;

            $this->user_answer = $this->getUserAnswer($questionId)?->user_answer;
        }

        if (!$this->view_test) {
            if ($this->checkTestCooldown() == false) {
                return redirect()->route('filament.app.resources.learning-test-results.index');
            };
        }

        // current question
        $this->current_question = $this->getQuestion();

        $this->checkFirstUnansweredQuestion();
    }

    protected function checkTestCooldown()
    {
        if (is_null($this->record->cooldown) || $this->record->cooldown == 0) {
            return true;
        } else {
            $lastAttempt = LearningTestResult::where('user_id', Auth::user()->id)
                ->where('test_id', $this->record->id)
                ->whereNotNull('finished_at')
                ->orderBy('created_at', 'desc')
                ->first();

            if (is_null($lastAttempt)) {
                return true;
            } else {
                // Delete the current result
                $this->result->delete();

                $cooldown = $this->record->cooldown;

                $lastAttemptTime = $lastAttempt->created_at;
                $cooldownTime = $lastAttemptTime->addMinutes($cooldown);
                return now() > $cooldownTime;
            }
        }
    }

    protected function checkFirstUnansweredQuestion()
    {
        // Check if it is the first unanswered question
        $details = $this->result->details;
        $questions = $this->record->details->where('is_active', true);

        foreach ($questions as $index => $question) {
            if (!$this->transition_enabled || ($this->position > $this->record->details->where('is_active', true)->count() - 1)) {
                if ($details->doesntContain('test_question_id', $question->id)) {
                    if ($index == $this->position) {
                        $this->current_question = $question;
                        break;
                    }

                    return redirect()->route('filament.app.resources.learning-test-results.do-test', [
                        'record' => $this->record->id,
                        'question' => $index + 1,
                        'viewTest' => 0
                    ]);
                }
            }
        }
    }

    protected function getOrCreateTestResult(): LearningTestResult
    {
        $result = LearningTestResult::where('test_id', $this->record->id)
            ->where('user_id', Auth::id())
            ->whereNull('finished_at')
            ->first();

        if (is_null($result)) {
            $result = new LearningTestResult();
            $result->test_id = $this->record->id;
            $result->user_id = Auth::id();
            $result->finished_at = null;
            $result->save();

            return $result;
        }

        return $result;
    }

    public function getQuestion()
    {
        $details = $this->record->details->where('is_active', true)->values();

        if ($details->has($this->position)) {
            return $details->get($this->position)->load('answers');
        }

        return null;
    }

    public function getUserAnswer($index)
    {
        if (is_null($index)) {
            return null;
        }

        return $this->result->details
            ->where('test_question_id', $index)
            ->first();
    }

    public function getCorrectAnswer($index)
    {
        $question = $this->record->details->where('id', $index)->first();

        if ($question) {
            $question->load('answers');
            return $question->answers->firstWhere('is_correct', true)->answer;
        }

        return null;
    }

    public function isAnswerCorrect($userAnswer = null, $correctAnswer = null)
    {
        $cleanUserAnswer = strtolower(preg_replace('/\s+/', '', $userAnswer));
        $cleanCorrectAnswer = strtolower(preg_replace('/\s+/', '', $correctAnswer));

        return $cleanUserAnswer == $cleanCorrectAnswer;
    }

    public function getInitialTimeLeft()
    {
        $createdAt = Carbon::parse($this->result->created_at);
        $timeLimitInMinutes = $this->record->time_limit;

        $timeLimitInSeconds = $timeLimitInMinutes * 60;

        $finishTime = $createdAt->addSeconds($timeLimitInSeconds);

        $timeLeftInSeconds = now()->diffInSeconds($finishTime, false);

        return round($timeLeftInSeconds);
    }

    #[On('end-time')]
    public function endTest()
    {
        $finished_at = now();

        $earnedPoints = $this->result->details->sum('points');
        $maxPoints = $this->record->details->where('is_active', true)->sum('points');
        $pointsToPass = $maxPoints - $this->record->min_score;

        $is_passed = $earnedPoints >= $pointsToPass;

        $this->result->finished_at = $finished_at;
        $this->result->total_time = round($this->result->created_at->diffInSeconds($finished_at));
        $this->result->points = $earnedPoints;
        $this->result->is_passed = $is_passed;
        $this->result->save();

        return redirect()->route('filament.app.resources.learning-test-results.finish-page', ['record' => $this->result->id]);
    }

    #[On('start-time')]
    public function getStartTime($startTime)
    {
        $this->start_time = $startTime;
    }

    public function submitForm()
    {
        if ($this->view_test) {
            $details = $this->record->details->where('is_active', true)->count();
            if ($details - 1 == $this->position) {
                return redirect()->route('filament.app.resources.learning-test-results.index');
            }

            return redirect()->route('filament.app.resources.learning-test-results.do-test', ['record' => $this->result->id, 'question' => $this->position + 2, 'viewTest' => 1]);
        }

        if (!$this->start_time instanceof Carbon) {
            $this->start_time = Carbon::parse($this->start_time);
        }
        $total_time = round($this->start_time->diffInSeconds(now()));

        $correctAnswer = $this->getCorrectAnswer($this->current_question->id);
        $points = $this->isAnswerCorrect($this->user_answer, $correctAnswer) ? $this->current_question->points : 0;

        LearningTestAnswer::updateOrCreate(
            [
                'result_id' => $this->result->id,
                'test_question_id' => $this->current_question->id,
            ],
            [
                'user_answer' => $this->user_answer ?? null,
                'points' => $points,
                'question_time' => $total_time,
            ]
        );

        if ($this->record->details->where('is_active', true)->count() == $this->result->details->count()) {
            return $this->endTest();
        } else {
            $this->position++;
            return redirect()->route('filament.app.resources.learning-test-results.do-test', ['record' => $this->record->id, 'question' => $this->position + 1, 'viewTest' => 0]);
        }
    }

    protected static string $view = 'filament.resources.learning-test-result-resource.pages.create-custom-test-result';
}
