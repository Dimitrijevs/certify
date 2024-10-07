<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type_id',
        'completed_test_id',
        'test_id',
        'name',
        'description',
        'valid_to',
        'thumbnail',
        'admin_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(LearningCertificateType::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function completedTest()
    {
        return $this->belongsTo(LearningTestResult::class);
    }

    public function test()
    {
        return $this->belongsTo(LearningTest::class);
    }
}
