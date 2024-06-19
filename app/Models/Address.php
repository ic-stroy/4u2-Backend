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

    public function order(){
        return $this->hasOne(Order::class, 'address_id', 'id')->whereIn('status', [1, 2, 3]);
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
