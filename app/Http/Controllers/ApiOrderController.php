<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\CharacterizedProducts;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Products;
use App\Providers\AuthServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiOrderController extends Controller
{
    public function getCoupon(Request $request){
        $coupon = Coupon::where('name', $request->coupon)->where('status', 1)
            ->where('start_date', '<=', date('Y-m-d H:i:s'))
            ->where('end_date', '>=', date('Y-m-d H:i:s'))->first();
        $user = Auth::user();

        if($coupon){
            $good = [];
            $products = $request->products;
            $all_sum = 0;
            $order_coupon_price = 0;
            $order_count = Order::where('user_id', $user->id)->where('status', '!=', Constants::BASKED)->count();
            foreach($products as $product_data){
                $product = CharacterizedProducts::find($product_data['id']);
                if($product){
                    $product_ = Products::find($product->product_id);
                    if($product_){
                        $discount = $product_->discount;
                        if($product->sum){
                            if(!empty($discount)){
                                $categorizedProductSum = $discount->percent?$product->sum*(int)$product_data['count'] - $product->sum*(int)$product_data['count']*(int)$discount->percent/100:$product->sum*(int)$product_data['count'];
                            }else{
                                $categorizedProductSum = $product->sum*(int)$product_data['count'];
                            }
                        }else{
                            if(!empty($discount)){
                                $categorizedProductSum = $discount->percent?$product_->sum*(int)$product_data['count'] - $product_->sum*(int)$product_data['count']*(int)$discount->percent/100:$product_->sum*(int)$product_data['count'];
                            }else{
                                $categorizedProductSum = $product_->sum*(int)$product_data['count'];
                            }
                        }
                    }
                    $all_sum = $all_sum + $categorizedProductSum;
                }
            }
            if($all_sum < $coupon->min_price){
                $message=__("this order sum isn't enough for coupon. Coupon min price $coupon->min_price");
                return $this->error($message, 400);
            }

            if($coupon->order_quantity) {
                if($coupon->order_quantity > 0){
                    $order_coupon_price = (int)$this->setOrderCoupon($coupon, $all_sum);
                }else{
                    $message=__("Coupon left 0 quantity");
                    return $this->error($message, 400);
                }
            }elseif($coupon->order_number) {
                if($order_count+1 == $coupon->order_number){
                    $order_coupon_price = (int)$this->setOrderCoupon($coupon, $all_sum);
                }else{
                    $message=__("Coupon for your $coupon->order_number - order this is your $order_count - order");
                    return $this->error($message, 400);
                }
            }else{
                $order_coupon_price = (int)$this->setOrderCoupon($coupon, $all_sum);
            }

            $good = [
                'coupon_price'=>$order_coupon_price,
                'coupon'=>[
                    'id'=>$coupon->id,
                    'name'=>$coupon->name,
                    'price'=>$coupon->price,
                    'percent'=>$coupon->percent
                ]
            ];
            return $this->success('Success', 200, $good);
        }else{
            $message = __('coupon not found or expired or not active');
            return $this->error($message, 400);
        }
    }

    public function setOrderCoupon($coupon, $price){
        if ($coupon->percent) {
            $order_coupon_price = ($price/100)*($coupon->percent);
        }elseif($coupon->price){
            $order_coupon_price = $coupon->price;
        }
        return $order_coupon_price;
    }
//    public function addCoupon(Request $request){
//        $order_coupon_price = 0;
//        if ($coupon = Coupon::where('name', $request->coupon_name)
//            ->where('status', 1)
//            ->where('start_date', '<=', date('Y-m-d H:i:s'))
//            ->where('end_date', '>=', date('Y-m-d H:i:s'))->first()) {
//            if ($order=Order::where('id', $request->order_id)->first()) {
//                $order_count = Order::where('user_id', $order->user_id)->where('status', '!=', Constants::BASKED)->count();
//                if (!$order->coupon_id) {
//                    if($order->all_price < $coupon->min_price){
//                        $message= __("this order sum isn't enough for coupon. Coupon min price $coupon->min_price");
//                        return $this->error($message, 400);
//                    }
//                    switch ($coupon->type){
//                        case Constants::TO_ORDER_COUNT:
//                            if($coupon->order_count > 0){
//                                $coupon->order_count = $coupon->order_count - 1;
//                                $order_coupon_price = (int)$this->setOrderCoupon($coupon, $order->all_price);
//                                $coupon->save();
//                            }else{
//                                $message=__("Coupon left 0 quantity");
//                                return $this->error($message, 400);
//                            }
//                            break;
//                        case Constants::FOR_ORDER_NUMBER:
//                            if($order_count == $coupon->order_count){
//                                $order_coupon_price = (int)$this->setOrderCoupon($coupon, $order->all_price);
//                            }else{
//                                $message=__("Coupon for your $coupon->order_count - order this is your $order_count - order");
//                                return $this->error($message, 400);
//                            }
//                            break;
//                        default:
//                            $order_coupon_price = (int)$this->setOrderCoupon($coupon, $order->all_price);
//                    }
//                    if((int)$order_coupon_price > 0){
//                        $order->coupon_id = $coupon->id;
//                        $order->coupon_price = $order_coupon_price;
//                        $order->all_price = $order->all_price - $order_coupon_price;
//                    }
//                    $order->save();
//                    $data=[
//                        'id'=>$order->id,
//                        'coupon_price'=>$order->coupon_price,
//                        'price'=>$order->price,
//                        'discount_price'=>$order->discount_price,
//                        'grant_total'=>$order->all_price
//                    ];
//
//                    $message = __('success');
//                    return $this->success($message, 200,$data);
//                }else {
//                    $message=__('this order has a coupon');
//                    return $this->error($message, 400);
//                }
//            }
//            else {
//                $message=__('order not found');
//                return $this->error($message, 400);
//            }
//        }
//        $message=__('coupon not found or expired or not active');
//        return $this->error($message, 400);
//    }
}
