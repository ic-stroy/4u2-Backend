<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'color';

    public function CharacterizedProducts(){
        return $this->hasOne(CharacterizedProducts::class, 'color_id', 'id');
    }

    public function getTranslatedContent(){
        return $this->hasOne(ColorTranslations::class, 'color_id', 'id')->where('lang', app()->getLocale());
    }

    public function getTranslatedModel()
    {
        return $this->hasOne(ColorTranslations::class, 'color_id', 'id')->select('id', 'color_id', 'name');
    }

    public function warehouses(){
        return $this->hasMany(CharacterizedProducts::class, 'color_id', 'id');
    }
}
