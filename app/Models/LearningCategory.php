<?php

namespace App\Models;

use App\Models\LearningUserStudyRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'thumbnail',
        'description',
        'is_active',
        'active_from',
        'active_till',
    ];

    public function learningResources()
    {
        return $this->hasMany(LearningResource::class, 'category_id');
    }

    public function activities()
    {
        return $this->hasMany(LearningUserStudyRecord::class, 'category_id');
    }
}
