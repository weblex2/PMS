<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    protected $fillable = [
        'article_id',
        'price',
        'valid_from',
        'valid_until',
        'price_type',
        'notes',
    ];
    
    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
        'price' => 'decimal:2',
    ];
    
    protected $table = 'prices';
    
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
