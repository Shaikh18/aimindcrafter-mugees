<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

class ChatSpecial extends Model
{
    use HasFactory;

    public $fillable = [
        'embedding_collection_id', 
        'title',
        'messages',
        'url',
        'user_id',
        'type'
    ];
    
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function embedding_collection(): BelongsTo
    {
        return $this->belongsTo(EmbeddingCollection::class);
    }
}
