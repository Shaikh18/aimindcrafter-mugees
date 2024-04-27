<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image',
        'video',
        'storage',
        'job_id',
        'status',
        'seed',
        'cfg_scale',
        'motion_bucket_id',
    ];
}
