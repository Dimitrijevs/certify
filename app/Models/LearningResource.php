<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'is_active',
        'gallery',
        'file_upload',
        'video_url',
        'video_type',
    ];

    protected $casts = [
        'file_upload' => 'array',
        'gallery' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(LearningCategory::class, 'category_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($record) {
            $parent = LearningCategory::find($record->category_id);

            if ($parent->updated_at < $record->updated_at) {
                $parent->updated_at = $record->updated_at;
                $parent->saveQuietly();
            }
        });

        static::updated(function ($record) {
            $parent = LearningCategory::find($record->category_id);

            if ($parent->updated_at < $record->updated_at) {
                $parent->updated_at = $record->updated_at;
                $parent->saveQuietly();
            }
        });

        static::deleted(function ($record) {
            $parent = LearningCategory::find($record->category_id);

            if ($parent->updated_at < $record->updated_at) {
                $parent->updated_at = $record->updated_at;
                $parent->saveQuietly();
            }
        });
    }
}
