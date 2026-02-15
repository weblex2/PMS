<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'name',
        'vorname',
        'nation1',
        'nation2',
        'match',
        'email',
        'phone',
        'address',
        'city',
    ];
    
    protected $table = 'guests';
}
