<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_details';

    public $fillable = [
        'id',
        'order_id',
        'warehouse_id',
        'product_id',
        'quantity',
        'price',
        'image_front',
        'image_back',
        'coupon_id',
        'size_id',
        'color_id',
        'discount',
        'discount_price',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function order(){
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
    public function warehouse_product(){
        return $this->hasOne(CharacterizedProducts::class, 'id', 'warehouse_id');
    }

    public function product(){
        return $this->hasOne(Products::class, 'id', 'product_id');
    }
    public function size(){
        return $this->hasOne(Sizes::class, 'id', 'size_id');
    }
    public function color(){
        return $this->hasOne(Color::class, 'id', 'color_id');
    }

}
