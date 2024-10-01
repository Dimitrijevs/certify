<?php

namespace Vendemy\Learning\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningTestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'finished_at',
        'total_time',
        'score',
        'is_passed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function test()
    {
        return $this->belongsTo(LearningTest::class, 'test_id');
    }

    public function details()
    {
        return $this->hasMany(LearningTestAnswer::class, 'result_id');
    }
}