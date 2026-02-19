<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    protected $fillable = [
        'reservation_number',
        'guest_id',
        'check_in',
        'check_out',
        'status',
        'total_price',
        'notes',
        'adults',
        'children',
        'payment_status',
        'payment_method',
        'advance_payment',
        'reservation_type',
        'match1',
        'match2',
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
    
    public function paths(): HasMany
    {
        return $this->hasMany(ReservationPath::class)->orderBy('path_number');
    }
    
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'reservation_room')->withPivot('price');
    }
    
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
