<?php

namespace App\Models;

use App\Models\PdfTemplate;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'categories',
        'available_for_everyone',
        'is_active',
        'thumbnail',
        'name',
        'language_id',
        'is_public',
        'cooldown',
        'description',
        'min_score',
        'time_limit',
        'is_question_transition_enabled',
        'layout_id',
        'price',
        'discount',
        'currency_id',
        'created_by',
        'aproved_by',
    ];

    protected $casts = [
        'category_id' => 'array',
        'categories' => 'array',
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

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'aproved_by');
    }

    public function purchases()
    {
        return $this->hasMany(UserPurchase::class, 'test_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($test) {
            if (is_null($test->price)) {
                $test->price = 0;
            }

            if ($test->price == 0 && $test->discount > 0) {
                $test->discount = 0;
            }
            
            $test->saveQuietly();

            $recipients = User::where('role_id', '<', 3)
                ->get();

            Notification::make()
                ->title(__('learning/learningTest.new_learning_material_created'))
                ->info()
                ->body(__('learning/learningTest.a_new_learning_material_has_been_created') . ': ' . $test->name)
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

            if ($test->price == 0 && $test->discount > 0) {
                $test->discount = 0;
            }
       
            $test->saveQuietly();
        });
    }
}
