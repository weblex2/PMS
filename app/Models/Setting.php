<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ["setting_key", "value"];
    protected $primaryKey = "setting_key";
    public $incrementing = false;
}
