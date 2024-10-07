<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningCertificateType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'days_before_expiry_warning',
    ];

    public function learningCertificates()
    {
        return $this->hasMany(LearningCertificate::class, 'type_id');
    }

    public function learning_test()
    {
        return $this->hasMany(LearningTest::class, 'certificate_type_id');
    }

    public function certificate_requirement()
    {
        return $this->hasMany(LearningCertificationRequirement::class, 'certification_type_id');
    }
}
