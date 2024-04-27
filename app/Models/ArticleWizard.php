<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleWizard extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'titles',
        'keywords',
        'outlines',
        'talking_points',
        'current_step',
        'language',
        'image',
        'image_description',
        'tone',
        'creativity',
        'view_point',
        'max_words',
        'status',
        'selected_keywords',
        'selected_title',
        'selected_outline',
        'selected_talking_points',
    ];

    protected $casts = [
        'outlines' => 'array'
    ];
}


