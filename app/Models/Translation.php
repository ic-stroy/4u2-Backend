<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;
    protected $table = 'translations';

    protected $fillable = [
        'lang',
        'lang_key',
        'lang_value'
    ];

    public function getModel(){
        return $this->hasOne(Translation::class, 'lang_key','lang_key')->where('lang', 'en');
    }
}
