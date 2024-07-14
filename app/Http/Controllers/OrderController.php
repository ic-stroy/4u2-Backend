<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Controller;
use App\Models\CharacterizedProducts;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{

    public function index(){
        $user = Auth::user();
        $orderedOrders_ = Order::where('status', Constants::ORDERED)->orderBy('updated_at', 'desc')->get();
        $performedOrders_ = Order::where('status', Constants::PERFORMED)->orderBy('updated_at', 'desc')->get();
        $cancelledOrders_ = Order::where('status', Constants::CANCELLED)->orderBy('updated_at', 'desc')->limit(101)->get();
        $acceptedByRecipientOrders_ = Order::where('status', Constants::ACCEPTED_BY_RECIPIENT)->orderBy('updated_at', 'desc')->limit(101)->get();
        $orderedOrders = $this->getOrders($orderedOrders_);
        $performedOrders = $this->getOrders($performedOrders_);
        $cancelledOrders = $this->getOrders($cancelledOrders_);
        $acceptedByRecipientOrders = $this->getOrders($acceptedByRecipientOrders_);
        $all_orders = [
            'orderedOrders'=>$orderedOrders,
            'performedOrders'=>$performedOrders,
            'cancelledOrders'=>$cancelledOrders,
            'acceptedByRecipientOrders'=>$acceptedByRecipientOrders,
        ];
        return view('order.index', [
            'all_orders'=>$all_orders,
            'user'=>$user
        ]);
    }

    public function finishedAllOrders(){
        $user = Auth::user();
        $cancelledOrders_ = Order::where('status', Constants::CANCELLED)->orderBy('updated_at', 'desc')->get();
        $acceptedByRecipientOrders_ = Order::where('status', Constants::ACCEPTED_BY_RECIPIENT)->orderBy('updated_at', 'desc')->get();
        $acceptedByRecipientOrders = $this->getOrders($acceptedByRecipientOrders_);
        $cancelledOrders = $this->getOrders($cancelledOrders_);
        $all_orders = [
            'cancelledOrders'=>$cancelledOrders,
            'acceptedByRecipientOrders'=>$acceptedByRecipientOrders,
        ];
        return view('order.finished_all_orders', [
            'all_orders'=>$all_orders,
            'user'=>$user
        ]);
    }

    public function getOrders($orders){
        $order_data = [];
        foreach($orders as $order){
//        $not_read_order_quantity = OrderDetail::where('order_id', $id)->where('is_read', 0)->count();
            $products = [];
            $performed_product_types = 0;
            $company_product_price = 0;
            $performed_company_product_price = 0;
            $company_discount_price = 0;
            $performed_company_discount_price = 0;
            $order_has = false;
            $order_detail_is_ordered = false;
            $user_name = '';
            if($order->user){
                $first_name = $order->user->first_name?$order->user->first_name.' ':'';
                $last_name = $order->user->last_name?$order->user->last_name.' ':'';
                $middle_name = $order->user->middle_name?$order->user->middle_name:'';
                $user_name = $first_name.''.$last_name.''.$middle_name;
            }
            foreach($order->orderDetail as $order_detail){
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
                    $products[] = [$order_detail, $order_detail_all_price, 'images'=>$images,
                        'discount_withouth_expire'=>$discount_withouth_expire
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
            if($order_has == true){
                $order_data[] = [
                    'order'=>$order,
                    'user_name'=>$user_name,
                    'order_detail_is_ordered'=>$order_detail_is_ordered,
                    'performed_product_types'=>$performed_product_types,
                    'products'=>$products,
                    'company_product_price'=>$company_product_price - $order_coupon_price,
                    'order_coupon_price'=>$order_coupon_price,
                    'company_discount_price'=>$company_discount_price,
                    'performed_company_product_price'=>$performed_company_product_price - $performed_order_coupon_price,
                    'performed_order_coupon_price'=>$performed_order_coupon_price,
                    'performed_company_discount_price'=>$performed_company_discount_price
                ];
            }
        }
        return $order_data;
    }

    public function address(Request $request){
        if($request->latitude){
            $latitude = $request->latitude;
        }else{
            $latitude = '';
        }
        if($request->longitude){
            $longitude = $request->longitude;
        }else{
            $longitude = '';
        }
        return view('order.address', ['latitude'=>$latitude, 'longitude'=>$longitude]);
    }

    public function category(){
//
//        $user = Auth::user();
//        foreach($user->unreadnotifications as $notification) {
//            if ($notification->type == "App\Notifications\OrderNotification") {
//                if (!empty($notification->data)) {
//                    $notification->data['product_images'] ? $notification->data['product_images'] : '';
//
//                    $not_read_ordered_quantity = OrderDetail::where('status', 0)->where()->count();
//                }
//            }
//        }

//        $ordered_orders = Order::where('status', Constants::ORDERED)->count();
//        $performed_orders = Order::where('status', Constants::PERFORMED)->count();
//        $cancelled_orders = Order::where('status', Constants::CANCELLED)->count();
//        $accepted_by_recipient_orders = Order::where('status', Constants::ACCEPTED_BY_RECIPIENT)->count();
//        return view('company.order.category', [
//            'ordered_orders'=>$ordered_orders,
//            'performed_orders'=>$performed_orders,
//            'cancelled_orders'=>$cancelled_orders,
//            'accepted_by_recipient_orders'=>$accepted_by_recipient_orders
//        ]);
    }

    public function show($id){
        $order = Order::find($id);
        return view('order.show', ['order'=>$order]);
    }

    public function destroy($id){
        return view('order.show');
    }

    public function cancellOrderDetail($id){
        $user = Auth::user();
        $orderDetail = OrderDetail::find($id);
        $order_details_discount_price = 0;
        $order_detail_price = 0;
        $cancelled_has = false;
        if($orderDetail){
            $orderDetail->status = Constants::ORDER_DETAIL_CANCELLED;

            $warehouse_product___ = CharacterizedProducts::find($orderDetail->warehouse_id);
            if($warehouse_product___) {
                $warehouse_product___->count = (int)$warehouse_product___->count + (int)$orderDetail->quantity;
                $warehouse_product___->save();
            }

            $orderDetail->save();
            $order = Order::whereIn('status', [Constants::ORDERED, Constants::PERFORMED, Constants::CANCELLED])->find($orderDetail->order_id);
            if($order){
                sleep(1);
                $order_details_status = OrderDetail::where('order_id', $orderDetail->order_id)->pluck('status')->all();
                if(!in_array(Constants::ORDER_DETAIL_BASKET, $order_details_status) && !in_array(Constants::ORDER_DETAIL_ORDERED, $order_details_status)){
                    foreach($user->unreadnotifications as $notification){
                        if($notification->type == "App\Notifications\OrderNotification"){
                            if(!empty($notification->data)){
                                if($notification->data['order_id'] == $order->id){
                                    $notification->read_at = date('Y-m-d H:i:s');
                                    $notification->save();
                                }
                            }
                        }
                    }
                    foreach($order->orderDetail as $order_detail){
                        if($order_detail->status == Constants::ORDER_DETAIL_PERFORMED){
                            $discount_price = $order_detail->discount_price??0;
                            $order_detail_price = $order_detail_price + $order_detail->price*$order_detail->quantity;
                            $order_details_discount_price = $order_details_discount_price + $discount_price;
                        }elseif($order_detail->status == Constants::ORDER_DETAIL_CANCELLED){
                            $cancelled_has = true;
                        }
                    }
                    if($order_detail_price <= 0 && $cancelled_has == true){
                        $order->status = Constants::CANCELLED;
                    }else{
                        $order->price = $order_detail_price;
                        $order->discount_price = $order_details_discount_price;
                        $order->all_price = $order_detail_price - $order_details_discount_price;
                        if((int)$order->coupon_price>0){
                            if($order->coupon){
                                $coupon_price = $this->setOrderCoupon($order->coupon, $order->all_price);
                            }else{
                                $coupon_price = $order->coupon_price;
                            }
                            $order->coupon_price = $coupon_price;
                        }else{
                            $coupon_price = 0;
                        }
                        $order->all_price = $order->all_price - $coupon_price;
                        $order->status = Constants::PERFORMED;
                    }
                    $order->save();

                    return redirect()->route('order.index')->with('cancelled', 'Product is cancelled');
                }
            }
//        $user = User::where('role_id', 1)->first();
//        $orderDetail->status = Constants::ORDER_DETAIL_PERFORMED;
//        $list_images = !empty($this->getImages($orderDetail->warehouse, 'warehouses')) ? $this->getImages($orderDetail->warehouse, 'warehouses')[0] : $this->getImages($orderDetail->warehouse->product, 'product')[0];
//        $data = [
//            'order_id'=>$order->id,
//            'order_detail_id'=>$orderDetail->id,
//            'order_all_price'=>$orderDetail->price*$orderDetail->quantity-(int)$orderDetail->discount_price - $coupon_price,
//            'product'=>[
//                'name'=>$orderDetail->warehouse->name,
//                'images'=>$list_images
//            ],
//            'receiver_name'=>$order->receiver_name,
//        ];
//        Notification::send($user, new OrderNotification($data));
        }
        return redirect()->route('order.index')->with('cancelled', 'Product is cancelled');
    }

    public function performOrderDetail($id){
        $user = Auth::user();
        $orderDetail = OrderDetail::find($id);
        $order_details_discount_price = 0;
        $order_detail_price = 0;
        if($orderDetail){
            if($orderDetail->status ==  Constants::ORDER_DETAIL_CANCELLED){
                $warehouse_product___ = CharacterizedProducts::find($orderDetail->warehouse_id);
                if($warehouse_product___) {
                    $warehouse_product___->count = (int)$warehouse_product___->count - (int)$orderDetail->quantity;
                    $warehouse_product___->save();
                }
            }
            $orderDetail->status = Constants::ORDER_DETAIL_PERFORMED;
            $orderDetail->save();
            $order = Order::whereIn('status', [Constants::ORDERED, Constants::PERFORMED, Constants::CANCELLED])->find($orderDetail->order_id);
            if($order){
                sleep(1);
                $order_details_status = OrderDetail::where('order_id', $orderDetail->order_id)->pluck('status')->all();
                if(!in_array(Constants::ORDER_DETAIL_BASKET, $order_details_status) && !in_array(Constants::ORDER_DETAIL_ORDERED, $order_details_status)){
                    foreach($order->orderDetail as $order_detail){
                        if($order_detail->status == Constants::ORDER_DETAIL_PERFORMED){
                            $discount_price = $order_detail->discount_price??0;
                            $order_detail_price = $order_detail_price + $order_detail->price*$order_detail->quantity;
                            $order_details_discount_price = $order_details_discount_price + $discount_price;
                        }
//                        elseif($order_detail->status == Constants::ORDER_DETAIL_CANCELLED){
//                            $order_detail->delete();
//                        }
                    }
                    if($order_detail_price>0){
                        $order->price = $order_detail_price;
                        $order->discount_price = $order_details_discount_price;
                        $order->all_price = $order_detail_price - $order_details_discount_price;
                        if((int)$order->coupon_price>0){
                            if($order->coupon){
                                $coupon_price = $this->setOrderCoupon($order->coupon, $order->all_price);
                            }else{
                                $coupon_price = $order->coupon_price;
                            }
                            $order->coupon_price = $coupon_price;
                        }else{
                            $coupon_price = 0;
                        }
                        $order->all_price = $order->all_price - $coupon_price;
                        $order->status = Constants::PERFORMED;
                        $order->save();
                        foreach($user->unreadnotifications as $notification){
                            if($notification->type == "App\Notifications\OrderNotification"){
                                if(!empty($notification->data)){
                                    if($notification->data['order_id'] == $order->id){
                                        $notification->read_at = date('Y-m-d H:i:s');
                                        $notification->save();
                                    };
                                }
                            }
                        }
                    }
                }
            }
//        $user = User::where('role_id', 1)->first();
//        $orderDetail->status = Constants::ORDER_DETAIL_PERFORMED;
//        $list_images = !empty($this->getImages($orderDetail->warehouse, 'warehouses')) ? $this->getImages($orderDetail->warehouse, 'warehouses')[0] : $this->getImages($orderDetail->warehouse->product, 'product')[0];
//        $data = [
//            'order_id'=>$order->id,
//            'order_detail_id'=>$orderDetail->id,
//            'order_all_price'=>$orderDetail->price*$orderDetail->quantity-(int)$orderDetail->discount_price - $coupon_price,
//            'product'=>[
//                'name'=>$orderDetail->warehouse->name,
//                'images'=>$list_images
//            ],
//            'receiver_name'=>$order->receiver_name,
//        ];
//        Notification::send($user, new OrderNotification($data));
        }

        return redirect()->route('order.index')->with('performed', 'Product is performed');
    }
    public function acceptedByRecipient($id){
        $order = Order::where('status', Constants::PERFORMED)->find($id);
        if(!$order){
            return redirect()->route('order.index')->with('error', 'Order not found');
        }
        $order->status = Constants::ACCEPTED_BY_RECIPIENT;
        $order->save();
//        $order_details = OrderDetail::where(['order_id'=>$order->id, 'status'=>3])->get();
//        foreach($order_details as $order_detail){
//            $order_detail->status = Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT;
//            $order_detail->save();
//        }
        return redirect()->route('order.index')->with('performed', 'Order is accepted by recipient');
    }

    public function cancellAcceptedByRecipient($id){
        $order = Order::where('status', Constants::ACCEPTED_BY_RECIPIENT)->find($id);
        if(!$order){
            return redirect()->route('order.index')->with('error', 'Order not found');
        }
        $order->status = Constants::PERFORMED;
        $order->save();
//        $order_details = OrderDetail::where(['order_id'=>$order->id, 'status'=>6])->get();
//        foreach($order_details as $order_detail){
//            $order_detail->status = Constants::ORDER_DETAIL_PERFORMED;
//            $order_detail->save();
//        }
        return redirect()->route('order.index')->with('performed', 'Order is accepted by recipient');
    }

    public function getImages($model, $text){
        if($model->images){
            $images_ = json_decode($model->images);
            $images = [];
            foreach ($images_ as $image_){
                switch($text){
                    case 'warehouse':
                        $images[] = asset('storage/warehouse/'.$image_);
                        break;
                    case 'product':
                        $images[] = asset('storage/products/'.$image_);
                        break;
                    case 'warehouses':
                        $images[] = asset('storage/warehouses/'.$image_);
                        break;
                    default:
                }
            }
        }else{
            $images = [];
        }
        return $images;
    }

    public function setOrderCoupon($coupon, $price){
        if ($coupon->percent) {
            $order_coupon_price = ($price/100)*($coupon->percent);
        }elseif($coupon->price){
            $order_coupon_price = $coupon->price;
        }
        return $order_coupon_price;
    }

    public function makeAllNotificationsAsRead(){
        $user = Auth::user();
        foreach($user->unreadnotifications as $notification){
            if($notification->type == "App\Notifications\OrderNotification"){
                if(!empty($notification->data)){
                    $notification->read_at = date('Y-m-d H:i:s');
                    $notification->save();
                }
            }
        }
        return redirect()->back();
    }
}
