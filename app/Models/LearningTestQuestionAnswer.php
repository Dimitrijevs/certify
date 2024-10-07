<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningTestQuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'order_id',
        'answer',
        'is_correct',
    ];

    public function question()
    {
        return $this->belongsTo(LearningTestDetail::class);
    }
}
