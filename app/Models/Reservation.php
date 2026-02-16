<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'reservation_number',
        'guest_id',
        'room_id',
        'check_in',
        'check_out',
        'status',
        'total_price',
        'notes',
        'match1',
        'match2',
        'adults',
        'children',
        'payment_status',
    ];
    
    protected $table = 'reservations';
    
    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_price' => 'decimal:2',
    ];
    
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }
    
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
