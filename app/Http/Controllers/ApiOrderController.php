<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\CharacterizedProducts;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Notifications\OrderNotification;
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
                $product = CharacterizedProducts::with([
                    'product',
                    'product.discount'
                 ])->whereHas('product')->find($product_data['id']);
                    $product_ = $product->product;
                    $discount = $product_->discount;
                    if($product->sum){
                        if($discount){
                            $categorizedProductSum = $discount->percent?$product->sum*(int)$product_data['count'] - $product->sum*(int)$product_data['count']*(int)$discount->percent/100:$product->sum*(int)$product_data['count'];
                        }else{
                            $categorizedProductSum = $product->sum*(int)$product_data['count'];
                        }
                    }else{
                        if($discount){
                            $categorizedProductSum = $discount->percent?$product_->sum*(int)$product_data['count'] - $product_->sum*(int)$product_data['count']*(int)$discount->percent/100:$product_->sum*(int)$product_data['count'];
                        }else{
                            $categorizedProductSum = $product_->sum*(int)$product_data['count'];
                        }
                    }
                    $all_sum = $all_sum + $categorizedProductSum;
            }
            if($all_sum < $coupon->min_price){
                $message = "this order sum isn't enough for coupon. Coupon min price $coupon->min_price";
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
                    $message = "Coupon for your $coupon->order_number - order this is your $order_count - order";
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


    public function getMyOrders(Request $request){
        $language = $request->header('language')??'en';
        $user = Auth::user()->load([
            'ordersOrdered',
            'ordersOrdered.address',
            'ordersOrdered.address.user',
            "ordersOrdered.address.cities",
            "ordersOrdered.address.cities.getTranslatedModel" => function ($query) use ($language) {
                $query->where('lang', $language);
            },
            "ordersOrdered.address.cities.region",
            "ordersOrdered.address.cities.region.getTranslatedModel" => function ($query) use ($language) {
                 $query->where('lang', $language);
            },
            'ordersOrdered.orderDetail',
            'ordersOrdered.orderDetail.color',
            'ordersOrdered.orderDetail.size',
            'ordersOrdered.orderDetail.warehouse_product',
            'ordersOrdered.orderDetail.warehouse_product.product',
            'ordersOrdered.orderDetail.warehouse_product.discount_withouth_expire',
            'ordersPerformed',
            'ordersPerformed.address',
            'ordersPerformed.address.user',
            "ordersPerformed.address.cities",
            "ordersPerformed.address.cities.getTranslatedModel" => function ($query) use ($language) {
                $query->where('lang', $language);
            },
            "ordersPerformed.address.cities.region",
            "ordersPerformed.address.cities.region.getTranslatedModel" => function ($query) use ($language) {
                $query->where('lang', $language);
            },
            'ordersPerformed.orderDetail',
            'ordersPerformed.orderDetail.color',
            'ordersPerformed.orderDetail.size',
            'ordersPerformed.orderDetail.warehouse_product',
            'ordersPerformed.orderDetail.warehouse_product.product',
            'ordersPerformed.orderDetail.warehouse_product.discount_withouth_expire',
            'ordersCancelled',
            'ordersCancelled.address',
            'ordersCancelled.address.user',
            "ordersCancelled.address.cities",
            "ordersCancelled.address.cities.getTranslatedModel" => function ($query) use ($language) {
                 $query->where('lang', $language);
            },
            "ordersCancelled.address.cities.region",
            "ordersCancelled.address.cities.region.getTranslatedModel" => function ($query) use ($language) {
                $query->where('lang', $language);
            },
            'ordersCancelled.orderDetail',
            'ordersCancelled.orderDetail.color',
            'ordersCancelled.orderDetail.size',
            'ordersCancelled.orderDetail.warehouse_product',
            'ordersCancelled.orderDetail.warehouse_product.product',
            'ordersCancelled.orderDetail.warehouse_product.discount_withouth_expire',
            'ordersAccepted',
            'ordersAccepted.address',
            'ordersAccepted.address.user',
            "ordersAccepted.address.cities",
            "ordersAccepted.address.cities.getTranslatedModel" => function ($query) use ($language) {
                $query->where('lang', $language);
            },
            "ordersAccepted.address.cities.region",
            "ordersAccepted.address.cities.region.getTranslatedModel" => function ($query) use ($language) {
                $query->where('lang', $language);
            },
            'ordersAccepted.orderDetail',
            'ordersAccepted.orderDetail.color',
            'ordersAccepted.orderDetail.size',
            'ordersAccepted.orderDetail.warehouse_product',
            'ordersAccepted.orderDetail.warehouse_product.product',
            'ordersAccepted.orderDetail.warehouse_product.discount_withouth_expire'
        ]);
        $ordersOrdered = $this->getOrders($user->ordersOrdered, $language);
        $ordersPerformed = $this->getOrders($user->ordersPerformed, $language);
        $ordersCancelled = $this->getOrders($user->ordersCancelled, $language);
        $ordersAccepted = $this->getOrders($user->ordersAccepted, $language);
        $orders = [
            "ordersOrdered" => $ordersOrdered,
            "ordersPerformed" => $ordersPerformed,
            "ordersCancelled" => $ordersCancelled,
            "ordersAccepted" => $ordersAccepted,
        ];
        return $this->success('Success', 200, $orders);
    }

    public function getOrders($orders, $language){
        $order_data = [];
        $order_data = $orders->map(function($order){
//        $not_read_order_quantity = OrderDetail::where('order_id', $id)->where('is_read', 0)->count();
            $products = [];
            $performed_product_types = 0;
            $company_product_price = 0;
            $performed_company_product_price = 0;
            $company_discount_price = 0;
            $performed_company_discount_price = 0;
            $order_has = false;
            $order_detail_is_ordered = false;
            $delivery_type = '';
            foreach($order->orderDetail as $order_detail){
                $translate_product_name = '';
                $city_translate = '';
                $region_translate = '';
                if($order_detail->status == Constants::ORDER_DETAIL_ORDERED){
                    $order_detail_is_ordered = true;
                }

                $discount_withouth_expire = 0;
                $images = [];

                if($order_detail->warehouse_id){
                    $order_has = true;

                    if($order_detail->status == Constants::ORDER_DETAIL_PERFORMED) {
                        $performed_product_types = $performed_product_types + 1;
                        $performed_company_product_price = $performed_company_product_price + $order_detail->price * $order_detail->quantity - (int)$order_detail->discount_price;
                        $performed_company_discount_price = $performed_company_discount_price + (int)$order_detail->discount_price;
                    }

                    $company_product_price = $company_product_price + $order_detail->price * $order_detail->quantity - (int)$order_detail->discount_price;
                    $order_detail_all_price = (int)$order_detail->price * (int)$order_detail->quantity - (int)$order_detail->discount_price;
                    $company_discount_price = $company_discount_price + (int)$order_detail->discount_price;

                    if($order_detail->warehouse_product){
                        $discount_withouth_expire = $order_detail->warehouse_product->discount_withouth_expire?$order_detail->warehouse_product->discount_withouth_expire->percent:0;
                    }else{
                        $discount_withouth_expire = 0;
                    }

                    if($order_detail->warehouse_product) {
                        if ($order_detail->warehouse_product->product) {
                            if ($order_detail->warehouse_product->product->images) {
                                $images_ = json_decode($order_detail->warehouse_product->product->images);
                            } else {
                                $images_ = [];
                            }
                            $images = [];
                            foreach ($images_ as $image_) {
                                $images[] = asset('storage/products/' . $image_);
                            }
                        } else {
                            $images = [];
                        }
                    }else{
                        $images = [];
                    }
                    $translate_product_name = optional(optional(optional($order_detail->warehouse_product)->product)->getTranslatedContent)->name??'';

                    $products[] = [$order_detail, $order_detail_all_price, 'images'=>$images,
                        'discount_withouth_expire'=>$discount_withouth_expire, 'size'=>$order_detail->size??'',
                        'color'=>$order_detail->color??[], 'name'=>$translate_product_name
                    ];
                }
            }
            if((int)$order->coupon_price>0){
                if($order->coupon){
                    $order_coupon_price = $this->setOrderCoupon($order->coupon, $company_product_price);
                    $performed_order_coupon_price = $this->setOrderCoupon($order->coupon, $performed_company_product_price);
                }else{
                    $order_coupon_price = $order->coupon_price??0;
                    $performed_order_coupon_price = $order->coupon_price??0;
                }
            }else{
                $order_coupon_price = $order->coupon_price??0;
                $performed_order_coupon_price = $order->coupon_price??0;
            }
            if($order->address){
                if($order->address->user){
                    if($order->address->user->is_admin == 1){
                        $delivery_type = "Pick-up";
                    }else{
                        $delivery_type = "Delivery";
                    }
                }
                $address = $order->address->name;
                if($order->address->cities){
                    $city_translate = optional(optional($order->address->cities)->getTranslatedContent)->name??'';
                    if($order->address->cities->region){
                        $region_translate = optional(optional($order->address->cities->region)->getTranslatedContent)->name??'';
                        $address_name = $address.' '.$city_translate.' '.$region_translate;
                    }else{
                        $address_name = $address.' '.$city_translate;
                    }
                }else{
                    $address_name = $address;
                }
            }else{
                $address_name = '';
            }
            if($order_has == true){
                return [
                    'order'=>$order,
                    'order_created'=>date('Y-m-d H:i:s', strtotime($order->created_at)),
                    'address'=>$address_name,
                    'delivery_type'=>$delivery_type,
                    'status'=>$order->status??'',
                    'order_detail_is_ordered'=>$order_detail_is_ordered,
                    'performed_product_types'=>$performed_product_types,
                    'products'=>$products,
                    'product_price'=>$company_product_price - $order_coupon_price,
                    'order_coupon_price'=>$order_coupon_price,
                    'discount_price'=>$company_discount_price,
                    'performed_product_price'=>$performed_company_product_price - $performed_order_coupon_price,
                    'performed_order_coupon_price'=>$performed_order_coupon_price,
                    'performed_discount_price'=>$performed_company_discount_price
                ];
            }
        });
        return $order_data;
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
        $language = $request->header('language')??'uz';
        if($request->selected_products && $request->payment && $request->address_id){
            $order = new Order();
            $order->save();
            $user = Auth::user();
            $data = [];
            $products = $request->selected_products;
            $order_coupon_price = 0;
            $all_discount_price = 0;
            $categorizedProductAllPrice = 0;
            $order_count = Order::where('user_id', $user->id)->count();
            foreach($products as $product_data){
                $order_detail = new OrderDetail();
                $categorizedProductPrice = 0;
                $discount_price = 0;
                $product = CharacterizedProducts::with([
                    'product',
                    'product.discount'
                 ])->whereHas('product')->find($product_data['id']);
                if($product){
                    if((int)$product->count < (int)$product_data['count']){
                        return $this->error("There are only left $product->count quantity", 400);
                    }else{
                        $product->count = (int)$product->count - (int)$product_data['count'];
                        $product->save();
                    }
                    $product_ = $product->product;
                    $discount = $product_->discount;
                    $order_detail->warehouse_id = (int)$product_data['id'];
                    $order_detail->quantity = (int)$product_data['count'];
                    if($product->size_id){
                        $order_detail->size_id = $product->size_id;
                    }
                    if(isset($product_data['color'])) {
                        if ($product_data['color']['id']) {
                            $order_detail->color_id = (int)$product_data['color']['id'];
                        }
                    }else{
                        $order_detail->color_id = (int)$product->color_id;
                    }
                    $order_detail->discount = isset($product_data['discount'])?(int)$product_data['discount']:null;
                    $order_detail->price = (int)$product->sum;
                    $order_detail->status = Constants::ORDER_DETAIL_ORDERED;
                    if($product->sum){
                        $categorizedProductPrice = $product->sum*(int)$product_data['count'];
                        if($discount){
                            if((int)$discount->percent != 0){
                                $discount_price = $product->sum*(int)$product_data['count']*(int)$discount->percent/100;
                            }
                        }
                    }else{
                        $categorizedProductPrice = $product_->sum*(int)$product_data['count'];
                        if($discount){
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
                $categorizedProductAllPrice = $categorizedProductAllPrice + $categorizedProductPrice;

                $order_detail->order_id = $order->id;
                $order_detail->save();
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
                $order->user_card_id = $request->card_id;
            }
            if($all_discount_price > 0){
                $order->discount_price = $all_discount_price;
            }
            $order->address_id = $request->address_id;
            $order->receiver_name = $request->first_name;
            $order->phone_number = $request->phone_number;
            $order->created_at = date('Y-m-d h:i:s');
            if(!$order->code){
                $length = 8;
                $order_id = (string)$order->id;
                $order_code = (string)str_pad($order_id, $length, '0', STR_PAD_LEFT);
                $order->code = $order_code;
            }
            $order->save();

            $users = User::where('is_admin', Constants::ADMIN)->get();
            $list_images = !empty($this->getImages($product_))?$this->getImages($product_)[0]:'no';
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
//            event(new PostNotification(['user'=>$order->receiver_name, 'sum'=>$all_sum]));
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
            $pick_up_info = $this->getPickUpInfo($order->load([
                'address',
                'address.cities',
                'address.cities.getTranslatedModel' => function ($query) use ($language) {
                    $query->where('lang', $language);
                },
                'address.cities.region',
                'address.cities.region.getTranslatedModel' => function ($query) use ($language) {
                    $query->where('lang', $language);
                },
            ]));
            $pick_up_info['order']->save();
            $info = [
                'code'=>$pick_up_info['order']->code,
                'address'=>$pick_up_info['address'],
                'pick_up_time'=>$pick_up_info['pick_up_time']
            ];

            return $this->success('Success', 200, $info);
        }else{
            $message = translate('There is no product');
            return $this->error($message, 400);
        }
    }

    public function getPickUpInfo($order){
        if((int)date('H') < 17){
            $deliver_date = strtotime('+2 days');
            $delivering_time = 'The day after tomorrow';
        }elseif((int)date('H') == 17 && (int)date('i') == 0){
            $deliver_date = strtotime('+2 days');
            $delivering_time = 'The day after tomorrow';
        }else{
            $deliver_date = strtotime('+3 days');
            $delivering_time = 'After three days';
        }
        if($order->address){
            $address = $order->address->name;
            if($order->address->cities){
                $city = $order->address->cities->name;
                if($order->address->cities->region){
                    if($order->address->cities->region->name == 'Toshkent shahri'){
                        if((int)date('H') < 17){
                            $deliver_date = strtotime('+1 day');
                            $delivering_time = 'Tomorrow';
                        }elseif((int)date('H') == 17 && (int)date('i') == 0){
                            $deliver_date = strtotime('+1 day');
                            $delivering_time = 'Tomorrow';
                        }else{
                            $deliver_date = strtotime('+2 days');
                            $delivering_time = 'The day after tomorrow';
                        }
                    }
                    $region = $order->address->cities->region->name;
                    $address_name = $address.' '.$city.' '.$region;
                }else{
                    $address_name = $address.' '.$city;
                }
            }else{
                $address_name = $address;
            }
        }else{
            $address_name = '';
        }
        $order->delivery_date = date('Y-m-d H:i:s', $deliver_date);
        return [
            'order'=>$order,
            'address'=>$address_name,
            'pick_up_time'=>$delivering_time
        ];
    }

    public function getImages($model){
        if($model->images){
            $images_ = json_decode($model->images);
            $images = [];
            foreach ($images_ as $image_){
                 $images[] = asset('storage/products/'.$image_);
            }
        }else{
            $images = [];
        }
        return $images;
    }
}
