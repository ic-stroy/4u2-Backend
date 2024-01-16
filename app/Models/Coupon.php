<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'coupons';

    protected $fillable = [
        'percent',
        'price',
        'category_id',
        'warehouse_id',
        'company_id'
    ];

    public function warehouse(){
        return $this->hasOne(CharacterizedProducts::class, 'id', 'warehouse_product_id');
    }
    public function product(){
        return $this->hasOne(Products::class, 'id', 'product_id');
    }
    public function category(){
        return $this->hasOne(Category::class, 'id', 'category_id')->where('step', 0);
    }
    public function subCategory(){
        return $this->hasOne(Category::class, 'id', 'category_id')->where('step', 1);
    }
}
