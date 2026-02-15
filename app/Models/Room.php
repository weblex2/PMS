<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    protected $fillable = [
        'room_number',
        'type',
        'bed_count',
        'status',
        'description',
    ];
    
    protected $table = 'rooms';
    
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'room_articles')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
