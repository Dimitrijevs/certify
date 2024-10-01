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
}
