<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cities extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'yy_cities';

    public function getDistricts(){
        return $this->hasMany(Cities::class, 'parent_id', 'id')->where('parent_id', '!=', 0);
    }

    public function region(){
        return $this->hasOne(Cities::class, 'id', 'parent_id');
    }
}
