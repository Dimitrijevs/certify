<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningTestDetail extends Model
{
    use HasFactory;

    protected $table = 'learning_test_questions';

    protected $fillable = [
        'test_id',
        'is_active',
        'question_title',
        'question_description',
        'answer_type', // 'text', 'select_option'
        'points',
        'visual_content',
    ];

    public function test()
    {
        return $this->belongsTo(LearningTest::class);
    }

    public function answers()
    {
        return $this->hasMany(LearningTestQuestionAnswer::class, 'question_id');
    }
}
