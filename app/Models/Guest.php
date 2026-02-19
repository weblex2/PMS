<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        "name",
        "vorname",
        "nation1",
        "nation2",
        "match",
        "email",
        "phone",
        "address",
        "city",
        "match1",
        "phonetic",
    ];
    
    protected $table = "guests";
    
    protected static function booted()
    {
        static::saving(function ($guest) {
            // match1: UPPERCASE version of name
            $guest->match1 = $guest->name ? strtoupper($guest->name) : null;
            
            // phonetic: Soundex algorithm for fuzzy matching
            $guest->phonetic = $guest->generatePhonetic($guest->name);
        });
    }
    
    /**
     * Generate phonetic code (Soundex algorithm)
     */
    protected function generatePhonetic($name)
    {
        if (empty($name)) {
            return null;
        }
        
        // Clean the name - remove non-alphabetic characters
        $cleanName = preg_replace("/[^a-zA-Z]/", "", $name);
        
        if (empty($cleanName)) {
            return null;
        }
        
        return soundex($cleanName);
    }
}
