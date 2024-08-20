<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityTranslations extends Model
{
    use HasFactory;

    protected $table = "city_translations";
    protected $fillable = [
        'name',
        'city_id',
        'lang'
    ];
}
