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
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'entity_id');
    }

    public function test()
    {
        return $this->belongsTo(LearningTest::class);
    }
}
