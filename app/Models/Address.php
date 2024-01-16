<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'addresses';

    public $fillable = [
        'id',
        'name',
        'longitude',
        'latitude',
        'city_id',
        'user_id',
    ];

    public function cities(){
        return $this->hasOne(Cities::class, 'id', 'city_id');
    }
}
