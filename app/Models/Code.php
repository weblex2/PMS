<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Code extends Model
{
    protected $fillable = ['form', 'code'];
    
    public $timestamps = true;

    /**
     * Get the description for a specific language
     */
    public static function getDescription(string $code, string $language = 'de'): ?string
    {
        $result = DB::table('codes_d')
            ->where('codes_id', $code)
            ->where('language', $language)
            ->value('description');
        
        return $result;
    }

    /**
     * Get all descriptions for a code as key-value array
     */
    public static function getDescriptions(string $code): array
    {
        return DB::table('codes_d')
            ->where('codes_id', $code)
            ->pluck('description', 'language')
            ->toArray();
    }

    /**
     * Get codes with descriptions for dropdowns
     * Returns array with code as key and description as value
     */
    public static function getDropdownOptions(string $language = 'de'): array
    {
        return DB::table('codes_d')
            ->where('language', $language)
            ->orderBy('description')
            ->pluck('description', 'codes_id')
            ->toArray();
    }

    /**
     * Get all unique codes with at least one description
     */
    public static function getCodesWithDescriptions(): array
    {
        return DB::table('codes_d')
            ->distinct()
            ->pluck('codes_id')
            ->toArray();
    }
}
