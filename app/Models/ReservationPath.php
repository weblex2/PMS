<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReservationPath extends Model
{
    protected $table = "reservation_paths";
    
    protected $fillable = [
        "reservation_id",
        "path_number",
        "check_in",
        "check_out",
    ];
    
    protected $casts = [
        "check_in" => "date",
        "check_out" => "date",
    ];
    
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
    
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, "path_room")->withPivot("price");
    }
}
