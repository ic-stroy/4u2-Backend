<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTranslations extends Model
{
    use HasFactory;

    protected $table = "product_translations";
    protected $fillable = [
        'name',
        'product_id',
        'lang'
    ];
}
