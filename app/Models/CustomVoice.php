<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomVoice extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'voice',
        'voice_id',
        'gender',      
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
