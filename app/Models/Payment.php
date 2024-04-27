<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * Payment belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'plan_id',
        'price',
        'currency',
        'gateway',
        'status',
        'plan_name',
        'words',
        'valid_until',
        'images',
        'characters',
        'minutes',
        'dalle_images',
        'sd_images',
        'invoice'
    ];
}
