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
            if ($testResult->is_passed) {
                $newCertificate = new LearningCertificate();
                $newCertificate->user_id = $testResult->user_id;
                $newCertificate->completed_test_id = $testResult->id;   
                $newCertificate->test_id = $testResult->test_id;
                $newCertificate->name = now()->format('Y') . ' ' . $testResult->test->name . ' Test';   
                $newCertificate->description = "This certificate recognizes that " . Auth::user()->name . " has successfully completed the \"" . $testResult->test->name . "\" assessment. This achievement demonstrates proficiency in the subject matter and commitment to professional development. The skills and knowledge validated by this certificate are valuable assets in today's competitive environment.";
                $newCertificate->valid_to = Carbon::now()->addYears(2)->toDateString();
                $newCertificate->save();

                Notification::make()
                    ->title('🎓 Certificate Created!')
                    ->body("Congratulations! You've earned a certificate for successfully completing the \"" . $testResult->test->name . "\" Test")
                    ->success()
                    ->send();
            }
        });
    }
}