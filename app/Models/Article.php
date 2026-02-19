<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    protected $fillable = [
        "article_number",
        "name",
        "description",
        "category",
    ];
    
    protected $table = "articles";
    
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }
    
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, "room_articles")
                    ->withPivot("quantity")
                    ->withTimestamps();
    }
    
    public function getCurrentPriceAttribute()
    {
        return $this->prices()
            ->where("valid_from", "<=", now())
            ->where(function ($query) {
                $query->where("valid_until", ">=", now())
                      ->orWhereNull("valid_until");
            })
            ->orderBy("valid_from", "desc")
            ->first();
    }
}
