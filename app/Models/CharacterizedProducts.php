<?php

namespace App\Models;

use App\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CharacterizedProducts extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'characterized_products';
    protected $fillable = [
        'id',
        'product_id',
        'size_id',
        'color_id',
        'count',
        'images'
    ];

    public function product(){
        return $this->hasOne(Products::class, 'id', 'product_id');
    }

    public function size(){
        return $this->hasOne(Sizes::class, 'id', 'size_id');
    }

    public function color(){
        return $this->hasOne(Color::class, 'id', 'color_id');
    }

    public function discount()
    {
        return $this->hasOne(Discount::class, 'product_id','product_id')->where('start_date', '<=', date('Y-m-d H:i:s'))->where('end_date', '>=', date('Y-m-d H:i:s'));
    }

    public function discount_withouth_expire()
    {
        $maxValue = Discount::max('end_date');
        return $this->hasOne(Discount::class, 'product_id','product_id')->where('end_date', $maxValue);
    }

    public function order_detail(){
        return $this->hasOne(OrderDetail::class, 'warehouse_id', 'id')->where('status', 6);
    }
}
