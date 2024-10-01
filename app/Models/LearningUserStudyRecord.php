<?php

namespace Vendemy\Learning\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningUserStudyRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'resource_id',
        'started_at',
        'finished_at',
        'video_watched',
        'video_progress',
        'video_duration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resource()
    {
        return $this->belongsTo(LearningResource::class);
    }
}
