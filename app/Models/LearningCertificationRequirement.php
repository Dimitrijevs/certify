<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningCertificationRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_type',
        'entity_id',
        'test_id',
        'school_id',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'entity_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'entity_id');
    }

    public function test()
    {
        return $this->belongsTo(LearningTest::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
