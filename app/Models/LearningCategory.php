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
        'sort_id',
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->sort_id)) {
                $category->sort_id = LearningCategory::max('sort_id') + 1;
            }
        });
    }
}
