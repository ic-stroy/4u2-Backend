<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    public $fillable = [
        'id',
        'price',
        'status',     // 1 basked 2 ordered 3 accepted 4 on_the_way 5 finished
        'delivery_date',
        'delivery_price',
        'all_price',
        'user_id',
        'coupon_id',
        'address_id',
        'coupon_price',
        'address_id',
        'receiver_name',
        'phone_number',
        'payment_method',
        'user_card_id',
        'discount_price',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function orderDetail(){
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function address(){
        return $this->hasOne(Address::class, 'id', 'address_id');
    }
}
