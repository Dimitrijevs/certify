<?php

namespace Vendemy\Learning\Models;

use App\Models\PdfTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sort_id',
        'category_id',
        'is_active',
        'thumbnail',
        'name',
        'description',
        'min_score',
        'time_limit',
        'is_question_transition_enabled',
        'certificate_type_id',
    ];

    protected $casts = [
        'category_id' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($test) {
            if (empty($test->sort_id)) {
                $test->sort_id = LearningTest::max('sort_id') + 1;
            }
        });
    }


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
}
