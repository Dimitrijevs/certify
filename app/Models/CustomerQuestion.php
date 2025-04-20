<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerQuestion extends Model
{
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'is_answered',
        'answered_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answeredBy()
    {
        return $this->belongsTo(User::class, 'answered_by');
    }
}
