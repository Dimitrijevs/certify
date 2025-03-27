<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'country',
        'postal_code',
        'phone',
        'email',
        'avatar',
        'website',
        'description',
        'created_by',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function students()
    {
        return $this->hasMany(User::class)->where('role_id', 4);
    }

    public function teachers()
    {
        return $this->hasMany(User::class)->where('role_id', 3);
    }

    public function certificates()
    {
        return $this->hasManyThrough(LearningCertificate::class, User::class, 'school_id', 'user_id', 'id', 'id');
    }

    public function certification_requirement()
    {
        return $this->hasMany(LearningCertificationRequirement::class, 'entity_id')
            ->where('entity_type', 'school');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
