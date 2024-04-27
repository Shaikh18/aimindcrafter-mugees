<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatHistorySpecial extends Model
{
    use HasFactory;

    const ROLE_USER = "user";
    const ROLE_BOT = "bot";

    public $fillable = [
        'user_id',
        'chat_id', 
        'role', 
        'content'
    ];
}
