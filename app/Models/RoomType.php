<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'default_bed_count',
        'base_price',
    ];
    
    protected $table = 'room_types';
    
    protected $casts = [
        'base_price' => 'decimal:2',
        'default_bed_count' => 'integer',
    ];
}
