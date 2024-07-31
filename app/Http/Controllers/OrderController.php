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
        $deliveredOrders_ = Order::where('status', Constants::ORDER_DELIVERED)->orderBy('updated_at', 'asc')->get();
        $readyForPickup_ = Order::where('status', Constants::READY_FOR_PICKUP)->orderBy('updated_at', 'asc')->get();
        $acceptedByRecipientOrders_ = Order::where('status', Constants::ACCEPTED_BY_RECIPIENT)->orderBy('updated_at', 'desc')->limit(101)->get();
        $orderedOrders = $this->getOrders($orderedOrders_);
        $performedOrders = $this->getOrders($performedOrders_);
        $cancelledOrders = $this->getOrders($cancelledOrders_);
        $deliveredOrders = $this->getOrders($deliveredOrders_);
        $readyForPickup = $this->getOrders($readyForPickup_);
        $acceptedByRecipientOrders = $this->getOrders($acceptedByRecipientOrders_);
        $all_orders = [
            'orderedOrders'=>$orderedOrders,
            'performedOrders'=>$performedOrders,
            'cancelledOrders'=>$cancelledOrders,
            'deliveredOrders'=>$deliveredOrders,
            'readyForPickup'=>$readyForPickup,
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
            $user_name = '';
            $user_full_name = '';
            $user_gender = '';
            $user_birth_date = '';
            $user_info = [
                'user_name'=>'',
                'role'=>'',
                'birth_date'=>'',
                'gender'=>'',
                'phone_number'=>'',
                'email'=>'',
            ];
            $user_email = '';
            $address = ['name'=>'', 'status'=>''];
            if ($order){
                if ($order->user){
                    $role = '';
                    $first_name = $order->user->first_name ? $order->user->first_name . ' ' : '';
                    $last_name = $order->user->last_name ? $order->user->last_name . ' ' : '';
                    $middle_name = $order->user->middle_name ? $order->user->middle_name : '';
                    $user_name = $first_name . '' . $last_name;
                    $user_full_name = $first_name . '' . $last_name.''.$middle_name;
                    if($order->user->gender == Constants::MALE){
                        $user_gender = translate('Male');
                    }elseif($order->user->gender == Constants::FEMALE){
                        $user_gender = translate('Female');
                    }
                    $user_email = $order->user->email??'';
                    if($order->user->role){
                        $role = $order->user->role->name??'';
                    }
                    $user_info = [
                        'user_name'=>$user_full_name,
                        'role'=>$role,
                        'birth_date'=>$user_birth_date,
                        'gender'=>$user_gender,
                        'phone_number'=>$order->user->email,
                        'email'=>$user_email,
                    ];
                }
                if($order->address) {
                    $address_type = '';
                    if($order->address->user){
                        if($order->address->user->role_id && $order->address->user->role_id != 4){
                            $address_type = 'pick_up';
                        }else{
                            $address_type = 'deliver';
                        }
                    }
                    if ($order->address->cities) {
                        if ($order->address->cities->region) {
                            $region_name = $order->address->cities->region->name ?? "";
                        }
                        $city_name = $order->address->cities->name ?? "";
                    }
                    $address_name = $order->address->name ?? '';
                    $address_postcode = $order->address->postcode ?? '';
                    $address = [
                        'name'=>$region_name.' '.$city_name.' '.$address_name. ' '.$address_postcode,
                        'status'=>$address_type
                    ];
                }
            }


            $products = [];
            $performed_product_types = 0;
            $company_product_price = 0;
            $performed_company_product_price = 0;
            $company_discount_price = 0;
            $performed_company_discount_price = 0;
            $products_quantity = 0;
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
                    $products_quantity = $products_quantity + $order_detail->quantity;
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
                    'user_info'=>$user_info,
                    'address'=>$address,
                    'order'=>$order,
                    'user_name'=>$user_name,
                    'order_detail_is_ordered'=>$order_detail_is_ordered,
                    'performed_product_types'=>$performed_product_types,
                    'products'=>$products,
                    'products_quantity'=>$products_quantity,
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
        }

        return redirect()->route('order.index')->with('performed', 'Product is performed');
    }
    public function acceptedByRecipient($id){
        $order = Order::where('status', [Constants::ORDER_DELIVERED, Constants::READY_FOR_PICKUP])->find($id);
        if(!$order){
            return redirect()->route('order.index')->with('error', 'Order not found');
        }
        $order->status = Constants::ACCEPTED_BY_RECIPIENT;
        $order->save();
        return redirect()->route('order.index')->with('performed', 'Order is accepted by recipient');
    }

    public function orderDelivered($id){
        $order = Order::where('status', Constants::PERFORMED)->find($id);
        if(!$order){
            return redirect()->route('order.index')->with('error', 'Order not found');
        }
        $order->status = Constants::ORDER_DELIVERED;
        $order->save();
        return redirect()->route('order.index')->with('performed', 'Order is accepted by recipient');
    }

    public function readyForPickup($id){
        $order = Order::where('status', Constants::PERFORMED)->find($id);
        if(!$order){
            return redirect()->route('order.index')->with('error', 'Order not found');
        }
        $order->status = Constants::READY_FOR_PICKUP;
        $order->save();
        return redirect()->route('order.index')->with('performed', 'Order is accepted by recipient');
    }

    public function cancellAcceptedByRecipient($id){
        $order = Order::where('status', Constants::ACCEPTED_BY_RECIPIENT)->find($id);
        if($order){
            if($order->address) {
                if ($order->address->user) {
                    if ($order->address->user->role_id && $order->address->user->role_id != 4) {
                        $order->status = Constants::READY_FOR_PICKUP;
                        $order->save();
                        return redirect()->route('order.index')->with('performed', 'Order is ready for pickup');
                    } else {
                        $order->status = Constants::ORDER_DELIVERED;
                        $order->save();
                        return redirect()->route('order.index')->with('performed', 'Order is delivered');
                    }
                }
            }
            return redirect()->route('order.index')->with('performed', 'There is no address in order');
        }else{
            return redirect()->route('order.index')->with('error', 'Order not found');
        }
    }

    public function cancellOrderDelivered($id){
        $order = Order::where('status', Constants::ORDER_DELIVERED)->find($id);
        if(!$order){
            return redirect()->route('order.index')->with('error', 'Order not found');
        }
        $order->status = Constants::PERFORMED;
        $order->save();
        return redirect()->route('order.index')->with('performed', 'Order is accepted by recipient');
    }

    public function cancellReadyForPickup($id){
        $order = Order::where('status', Constants::READY_FOR_PICKUP)->find($id);
        if(!$order){
            return redirect()->route('order.index')->with('error', 'Order not found');
        }
        $order->status = Constants::PERFORMED;
        $order->save();
        return redirect()->route('order.index')->with('performed', 'Order is accepted by recipient');
    }

    public function deleteOrderDetail($id){
        $order_detail = OrderDetail::find($id);
        if($order_detail){
            $order_detail->delete();
            return redirect()->back()->with('performed', 'Order detail deleted from order');
        }else{
            return redirect()->back()->with('cancelled', 'Order detail not found');
        }
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
