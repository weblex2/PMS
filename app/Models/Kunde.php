<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunde extends Model
{
    use HasFactory;

    protected $table = 'kunden';

    protected $fillable = [
        'name',
        'vorname',
        'firma',
        'email',
        'phone',
        'address',
        'city',
        'notes',
    ];
}
