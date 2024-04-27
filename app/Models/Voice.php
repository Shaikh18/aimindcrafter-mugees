<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voice extends Model
{
    use HasFactory;

    protected $fillable = [
        'voice',
        'voice_id',
        'gender',
        'language_code',          
        'vendor_id',
        'vendor',
        'vendor_img',
        'status',
        'avatar_url',
        'voice_type',
        'sample_url',
        'description',
    ];
}
