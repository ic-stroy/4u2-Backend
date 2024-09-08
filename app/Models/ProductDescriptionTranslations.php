<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDescriptionTranslations extends Model
{
    use HasFactory;

    protected $table='product_description_translations';
    protected $fillable = [
        'name',
        'product_id',
        'lang'
    ];
}
