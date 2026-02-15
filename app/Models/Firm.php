<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    protected $fillable = ["name", "code", "organization_id", "is_active"];
    
    public $timestamps = true;
}
