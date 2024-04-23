<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\CharacterizedProducts;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Products;
use App\Models\User;
use App\Notifications\OrderNotification;
use App\Providers\AuthServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

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
                $message=translate("this order sum isn't enough for coupon. Coupon min price $coupon->min_price");
                return $this->error($message, 400);
            }

            if($coupon->order_quantity) {
                if($coupon->order_quantity > 0){
                    $order_coupon_price = (int)$this->setOrderCoupon($coupon, $all_sum);
                }else{
                    $message=translate("Coupon left 0 quantity");
                    return $this->error($message, 400);
                }
            }elseif($coupon->order_number) {
                if($order_count+1 == $coupon->order_number){
                    $order_coupon_price = (int)$this->setOrderCoupon($coupon, $all_sum);
                }else{
                    $message=translate("Coupon for your $coupon->order_number - order this is your $order_count - order");
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
            $message = translate('coupon not found or expired or not active');
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

    public function confirmOrder(Request $request){
        if($request->selected_products && $request->payment && $request->address_id){
            $order = new Order();
            $order_detail = new OrderDetail();
            $user = Auth::user();
            $data = [];
            $products = $request->selected_products;
            $order_coupon_price = 0;
            $all_discount_price = 0;
            $categorizedProductAllPrice = 0;
            $order_count = Order::where('user_id', $user->id)->count();
            foreach($products as $product_data){
                $categorizedProductPrice = 0;
                $discount_price = 0;
                $product = CharacterizedProducts::find($product_data['id']);
                if($product){
                    $product_ = Products::find($product->product_id);
                    if($product_){
                        $discount = $product_->discount;
                        $order_detail->warehouse_id = (int)$product_data['id'];
                        $order_detail->quantity = (int)$product_data['count'];
                        $order_detail->size_id = $product->size_id;
                        $order_detail->color_id = (int)$product_data['color']['id'];
                        $order_detail->discount = (int)$product_data['discount'];
                        $order_detail->price = (int)$product->price;
                        $order_detail->status = Constants::ACTIVE;
                        if($product->sum){
                            $categorizedProductPrice = $product->sum*(int)$product_data['count'];
                            if(!empty($discount)){
                                if((int)$discount->percent != 0){
                                    $discount_price = $product->sum*(int)$product_data['count']*(int)$discount->percent/100;
                                }
                            }
                        }else{
                            $categorizedProductPrice = $product_->sum*(int)$product_data['count'];
                            if(!empty($discount)){
                                if((int)$discount->percent != 0){
                                    $discount_price = $product_->sum*(int)$product_data['count']*(int)$discount->percent/100;
                                }
                            }
                        }
                        if($discount_price > 0){
                            $all_discount_price = $all_discount_price + $discount_price;
                            $order_detail->discount_price = $discount_price;
                        }
                    }
                }
                $categorizedProductAllPrice = $categorizedProductAllPrice + $categorizedProductPrice;
            }

            $all_sum = $categorizedProductAllPrice - $all_discount_price;
            if($request->coupon){
                $coupon = Coupon::where('name', $request->coupon)->first();
                if($coupon) {
                    if ($all_sum > $coupon->min_price) {
                        if ($coupon->order_quantity) {
                            if ($coupon->order_quantity > 0) {
                                $order_coupon_price = (int)$this->setOrderCoupon($coupon, $all_sum);
                            }
                        } elseif ($coupon->order_number) {
                            if ($order_count + 1 == $coupon->order_number) {
                                $order_coupon_price = (int)$this->setOrderCoupon($coupon, $all_sum);
                            }
                        } else {
                            $order_coupon_price = (int)$this->setOrderCoupon($coupon, $all_sum);
                        }
                    }
                    $order->coupon_id = $coupon->id;
                }
            }
            $all_sum = $all_sum - $order_coupon_price;
            $order->price = $categorizedProductAllPrice;
            $order->user_id = $user->id;
            $order->all_price = $all_sum;
            $order->status = Constants::ORDER_DETAIL_ORDERED;
            $order->coupon_price = $order_coupon_price;
            if($request->payment == 'Cash'){
                $order->payment_method = Constants::CASH;
            }elseif($request->payment == 'Online'){
                $order->payment_method = Constants::ONLINE;
            }
            if($all_discount_price > 0){
                $order->discount_price = $all_discount_price;
            }
            $order->address_id = $request->address_id;
            $order->save();
            $order_detail->order_id = $order->id;
            $order_detail->save();

            $users = User::where('is_admin', Constants::ADMIN)->get();
            $list_images = $this->getImages($product_);
            $data = [
                'order_id'=>$order->id,
                'order_all_price'=>$all_sum,
                'product'=>[
                    'name'=>$product_->name,
                    'images'=>$list_images
                ],
                'receiver_name'=>$order->receiver_name,
            ];
            Notification::send($users, new OrderNotification($data));
//                $good = [
//                    'coupon_price'=>$order_coupon_price,
//                    'coupon'=>[
//                        'id'=>$coupon->id,
//                        'name'=>$coupon->name,
//                        'price'=>$coupon->price,
//                        'percent'=>$coupon->percent
//                    ]
//                ];
//                return $this->success('Success', 200, $good);
//            }

            return $this->success('Success', 200, $data);
        }else{
            $message = translate('There is no product');
            return $this->error($message, 400);
        }
    }

    public function getImages($model){
        if($model->images){
            $images_ = json_decode($model->images);
            $images = [];
            foreach ($images_ as $image_){
                 $images[] = asset('storage/warehouse/'.$image_);
            }
        }else{
            $images = [];
        }
        return $images;
    }
}
