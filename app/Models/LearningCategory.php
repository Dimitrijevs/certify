<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'thumbnail',
        'description',
        'sort_id',
        'is_active',
        'active_from',
        'active_till',
    ];
}
