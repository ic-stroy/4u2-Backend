<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslations extends Model
{
    use HasFactory;

    protected $table = "category_translations";
    protected $fillable = [
        'name',
        'category_id',
        'lang'
    ];
}
