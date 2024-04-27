<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomChat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'status',
        'chat_code',
        'user_id',
        'name',
        'sub_name',
        'logo',
        'group',
        'prompt',
        'type',
        'model',
        'code_interpreter',
        'retrieval',
        'function',
    ];
}
