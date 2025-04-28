<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name',
        'iso2',
        'iso3',
        'direction',
        'is_active',
    ];
}
