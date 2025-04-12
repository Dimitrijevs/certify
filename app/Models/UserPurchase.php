<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'test_id',
        'seller_id',
        'price',
        'currency',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(LearningCategory::class, 'course_id');
    }

    public function test()
    {
        return $this->belongsTo(LearningTest::class, 'test_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
