<?php

namespace Vendemy\Learning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
