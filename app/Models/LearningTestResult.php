<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningTestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'finished_at',
        'total_time',
        'score',
        'is_passed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(LearningTestAnswer::class, 'result_id');
    }

    public function test()
    {
        return $this->belongsTo(LearningTest::class, 'test_id');
    }

    public function details()
    {
        return $this->hasMany(LearningTestAnswer::class, 'result_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($testResult) {

            $originalIsPassed = $testResult->getOriginal('is_passed');
            $newIsPassed = $testResult->is_passed;

            if (($originalIsPassed === false || is_null($originalIsPassed)) && $newIsPassed === true) {
                self::createCertificate($testResult);
            }
        });
    }

    protected static function createCertificate($testResult)
    {
        $newCertificate = new LearningCertificate();
        $newCertificate->user_id = $testResult->user_id;
        $newCertificate->completed_test_id = $testResult->id;
        $newCertificate->test_id = $testResult->test_id;
        $newCertificate->name = now()->format('Y') . ' ' . $testResult->test->name . ' Test';
        $newCertificate->description = __('learning/learningCertificate.this_certificate_recognizes_that') . " " . Auth::user()->name . " " . __('learning/learningCertificate.has_successfully_completed_the') . " \"" . $testResult->test->name . "\" " . __('learning/learningCertificate.description_end');
        $newCertificate->valid_to = Carbon::now()->addYears(2)->toDateString();
        $newCertificate->save();

        Notification::make()
            ->title('🎓 ' . __('learning/learningCertificate.certificate_created') . '!')
            ->body(__('learning/learningCertificate.congratulations_you_ve_earned_a_certificate_for_successfully_completing_the') . " \"" . $testResult->test->name . "\" " . __('learning/learningCertificate.test') . ".")
            ->success()
            ->send();
    }
}