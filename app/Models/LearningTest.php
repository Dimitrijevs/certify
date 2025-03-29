<?php

namespace App\Models;

use App\Models\PdfTemplate;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'is_active',
        'thumbnail',
        'name',
        'is_public',
        'cooldown',
        'description',
        'min_score',
        'time_limit',
        'is_question_transition_enabled',
        'layout_id',
        'price',
        'discount',
        'currency',
        'created_by',
        'aproved_by',
    ];

    protected $casts = [
        'category_id' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(LearningCategory::class);
    }

    public function details()
    {
        return $this->hasMany(LearningTestDetail::class, 'test_id');
    }

    public function layout()
    {
        return $this->belongsTo(PdfTemplate::class, 'layout_id');
    }

    public function requirements()
    {
        return $this->hasMany(LearningCertificationRequirement::class, 'test_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'aproved_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($test) {
            if (is_null($test->price)) {
                $test->price = 0;
            }

            if ($test->price = 0 && $test->discount > 0) {
                $test->discount = 0;
            }

            $test->saveQuietly();

            $recipients = User::where('role_id', '<', 3)
                ->get();

            Notification::make()
                ->title('New Learning Material Created')
                ->info()
                ->body('A new learning material has been created: ' . $test->name)
                ->actions([
                    Action::make('view')
                        ->icon('tabler-eye')
                        ->url(function () use ($test) {
                            return '/app/learning-tests/' . $test->id . '/edit';
                        })
                        ->button(),
                ])
                ->sendToDatabase($recipients);
        });

        static::updated(function ($test) {
            if (is_null($test->price)) {
                $test->price = 0;
            }

            if ($test->price = 0 && $test->discount > 0) {
                $test->discount = 0;
            }
            
            $test->saveQuietly();
        });
    }
}
