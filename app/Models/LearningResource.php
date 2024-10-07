<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'sort_id',
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($resource) {
            if (empty($resource->sort_id)) {
                $resource->sort_id = LearningResource::max('sort_id') + 1;
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(LearningCategory::class, 'category_id');
    }

    public function getHasFileUploadAttribute(): bool
    {
        return !empty($this->file_upload);
    }

    public function getHasVideoUrlAttribute(): bool
    {
        return !empty($this->video_url);
    }
}
