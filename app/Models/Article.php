<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'article_number',
        'name',
        'description',
        'purchase_price',
        'selling_price',
        'stock',
        'category',
    ];
    
    protected $table = 'articles';
}
