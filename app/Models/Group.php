<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'school_id',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->hasMany(User::class, 'group_id');
    }

    public function instructors()
    {
        return $this->hasMany(User::class, 'group_id')
            ->where('role_id', 3);
    }

    public function certification_requirement()
    {
        return $this->hasMany(LearningCertificationRequirement::class, 'entity_id')
            ->where('entity_type', 'group');
    }
}
