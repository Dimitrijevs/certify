<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningTestAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'result_id',
        'test_question_id',
        'user_answer',
        'points',
        'question_time',
    ];

    public function result()
    {
        return $this->belongsTo(LearningTestResult::class);
    }

    public function question()
    {
        return $this->belongsTo(LearningTestDetail::class, 'test_question_id');
    }
}
