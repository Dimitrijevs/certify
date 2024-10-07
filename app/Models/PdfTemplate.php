<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdfTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'pdf_type',
        'logo',
        'name',
        'lang',
        'is_default',
        'is_active',
        'page_orientation',
        'source_code',
    ];

    protected $casts = [
        'logo' => 'array',
    ];
}
