<?php

namespace App\Models;

use App\Models\LearningUserStudyRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'thumbnail',
        'language_id',
        'categories',
        'description',
        'is_active',
        'price',
        'discount',
        'currency_id',
        'created_by',
        'is_public',
        'available_for_everyone',
        'aproved_by',
    ];

    protected $casts = [
        'categories' => 'array',
    ];

    public function learningResources()
    {
        return $this->hasMany(LearningResource::class, 'category_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function activities()
    {
        return $this->hasMany(LearningUserStudyRecord::class, 'category_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'aproved_by');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($category) {
            if (is_null($category->price)) {
                $category->price = 0;
                $category->saveQuietly();
            }

            $recipients = User::where('role_id', '<', 3)
                ->get();

            Notification::make()
                ->title('New Learning Material Created')
                ->info()
                ->body('A new learning material has been created: ' . $category->name)
                ->actions([
                    Action::make('view')
                        ->icon('tabler-eye')
                        ->url(function () use ($category) {
                            return '/app/learning-categories/' . $category->id . '/edit';
                        })
                        ->button(),
                ])
                ->sendToDatabase($recipients);
        });

        static::updated(function ($category) {
            if (is_null($category->price)) {
                $category->price = 0;
                $category->saveQuietly();
            }
        });
    }
}
