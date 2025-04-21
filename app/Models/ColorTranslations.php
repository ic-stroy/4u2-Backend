<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorTranslations extends Model
{
    use HasFactory;

    protected $table = "color_translations";
    protected $fillable = [
        'name',
        'color_id',
        'lang'
    ];

    public function getModel(){
        return $this->hasOne(Color::class, 'id','color_id');
    }
}
