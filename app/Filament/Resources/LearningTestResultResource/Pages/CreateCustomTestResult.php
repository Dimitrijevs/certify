<?php

namespace App\Filament\Resources\LearningTestResultResource\Pages;

use Carbon\Carbon;
use Livewire\Attributes\On;
use App\Models\LearningTest;
use App\Models\UserPurchase;
use Filament\Actions\Action;
use App\Models\LearningTestAnswer;
use App\Models\LearningTestResult;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
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

    public function getTitle(): string|Htmlable
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
            $this->result = LearningTestResult::where('id', $record)
                ->whereNotNull('finished_at')
                ->with('details')
                ->first();

            $this->record = $this->result->test;
        } else {
            $this->record = LearningTest::findOrFail($record);
            $this->result = $this->getOrCreateTestResult();
        }

        if (!$this->checkPurchase($record)) {
            Notification::make()
                ->title('You do not have access to this test')
                ->body('You need to purchase this test to access it.')
                ->danger()
                ->send();

            return redirect()->route('filament.app.pages.dashboard');
        }

        // Add this check to prevent out-of-bounds access
        $totalQuestions = $this->record->details->where('is_active', true)->count();

        if ($this->position >= $totalQuestions) {
            // Redirect to the first question
            return redirect()->route('filament.app.resources.learning-test-results.do-test', [
                'record' => $this->view_test ? $this->result->id : $this->record->id,
                'question' => 1,
                'viewTest' => $this->view_test ? 1 : 0,
            ]);
        }

        if ($this->record->is_question_transition_enabled || $this->view_test) {
            $this->transition_enabled = true;

            $this->user_answer = $this->getUserAnswer($this->position)?->user_answer;
        }

        if (!$this->view_test) {
            if ($this->checkTestCooldown() == false) {
                return redirect()->route('filament.app.resources.learning-test-results.index');
            }
            ;
        }

        // current question
        $this->current_question = $this->getQuestion();

        if (!$this->view_test) {
            $this->checkFirstUnansweredQuestion();
        }
    }

    public function checkPurchase($id)
    {
        $purchase = UserPurchase::where('user_id', Auth::id())
            ->where('test_id', $id)
            ->exists();

        return $purchase;
    }

    protected function checkTestCooldown()
    {
        if (is_null($this->record->cooldown) || $this->record->cooldown == 0) {
            return true;
        }

        $lastAttempt = LearningTestResult::where('user_id', Auth::user()->id)
            ->where('test_id', $this->record->id)
            ->whereNotNull('finished_at')
            ->orderBy('created_at', 'desc')
            ->first();

        if (is_null($lastAttempt)) {
            return true;
        }

        $cooldown = $this->record->cooldown;
        $lastAttemptTime = $lastAttempt->created_at;
        $cooldownTime = $lastAttemptTime->addMinutes($cooldown);

        if (now() > $cooldownTime) {
            return true;
        }

        // Delete the current result
        $this->result->delete();
        return false;
    }

    public function getQuestions()
    {
        if ($this->view_test) {
            $answers = $this->result->details;

            $questionsArray = [];
            foreach ($answers as $answer) {
                $questionsArray[] = $answer->question;
            }

            return collect($questionsArray)->sortBy('id')->values();
        } else {
            return $this->record->details->where('is_active', true)->values();
        }
    }

    public function getSumOfQuestionPoints(): int
    {
        $answers = $this->result->details;
        $points = 0;

        foreach ($answers as $answer) {
            $points += $answer->question->points;
        }

        return $points;
    }

    protected function checkFirstUnansweredQuestion()
    {
        // Check if shuffled question IDs are stored in the session
        if (session()->has('shuffled_question_ids')) {
            $shuffledQuestionIds = session('shuffled_question_ids');
        } else {
            // Get active details and shuffle them
            $details = $this->record->details->where('is_active', true)->values();
            $shuffledDetails = $details->shuffle(); // Shuffle the collection

            // Store shuffled question IDs in the session
            $shuffledQuestionIds = $shuffledDetails->pluck('id')->toArray();
            session(['shuffled_question_ids' => $shuffledQuestionIds]);
        }

        // Retrieve the user's answers
        $details = $this->result->details;

        // Loop through the shuffled question IDs
        foreach ($shuffledQuestionIds as $index => $questionId) {
            $question = $this->record->details->where('id', $questionId)->first();

            if (!$this->transition_enabled || ($this->position > count($shuffledQuestionIds) - 1)) {
                if ($details->doesntContain('test_question_id', $questionId)) {
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
        if ($this->view_test) {
            $details = $this->record->details->where('is_active', true)->values();

            if ($details->has($this->position)) {
                return $details->get($this->position)->load('answers');
            }

            return null;
        } else {
            // Clear the session question order if starting a new test
            if ($this->position === 0) {
                session()->forget('shuffled_question_ids');
            }

            if (session()->has('shuffled_question_ids')) {
                $shuffledQuestionIds = session('shuffled_question_ids');
            } else {
                // Get active details and shuffle them
                $details = $this->record->details->where('is_active', true)->values();
                $shuffledDetails = $details->shuffle(); // Shuffle the collection

                // Store shuffled question IDs in the session
                $shuffledQuestionIds = $shuffledDetails->pluck('id')->toArray();
                session(['shuffled_question_ids' => $shuffledQuestionIds]);
            }

            // Check if the position exists in the shuffled IDs
            if (array_key_exists($this->position, $shuffledQuestionIds)) {
                $questionId = $shuffledQuestionIds[$this->position];
                return $this->record->details()->where('id', $questionId)->with('answers')->first();
            }

            return null;
        }
    }

    public function getAnswers()
    {
        // Convert the collection to an array
        $answersArray = $this->current_question->answers;

        if ($this->view_test) {
            return $answersArray;
        } else {
            $answers = $answersArray->shuffle();

            return $answers;
        }
    }

    public function getUserAnswer($position)
    {
        if ($this->view_test) {
            $userAnswer = $this->result->details->sortBy('test_question_id')->values()->get($position);

            return $userAnswer;
        } else {
            if (is_null($position)) {
                return null;
            }

            // Retrieve shuffled question IDs from the session
            if (session()->has('shuffled_question_ids')) {
                $shuffledQuestionIds = session('shuffled_question_ids');

                // Check if the position exists in the shuffled IDs
                if (array_key_exists($position, $shuffledQuestionIds)) {
                    $questionId = $shuffledQuestionIds[$position];

                    // Find the user's answer for the question based on the question ID
                    return $this->result->details
                        ->where('test_question_id', $questionId)
                        ->first();
                }
            }

            return null;
        }
    }

    public function getCorrectAnswer($question_id)
    {
        $question = $this->record->details->where('id', $question_id)->first();

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

        $userAnswersCount = $this->result->details->count();
        $questionsCount = $this->record->details->count();
        if ($userAnswersCount < $questionsCount) {
            $answers = $this->result->details;
            $questions = $this->getQuestions();

            // Find unanswered questions
            $unansweredQuestions = $questions->filter(function ($question) use ($answers) {
                return !$answers->contains('test_question_id', $question->id);
            });

            // Create LearningTestAnswer records for unanswered questions
            foreach ($unansweredQuestions as $question) {
                LearningTestAnswer::create([
                    'result_id' => $this->result->id,
                    'test_question_id' => $question->id,
                    'user_answer' => null,
                    'points' => 0,
                    'question_time' => 0,
                ]);
            }
        }

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
