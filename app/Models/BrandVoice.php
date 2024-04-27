<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class BrandVoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'website',
        'tone',
        'products',            
        'audience',            
        'tagline',            
        'industry', 
        'description', 
        'user_id', 
        'total'
    ];

    /**
     * Get the fields.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function products(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    } 
}
