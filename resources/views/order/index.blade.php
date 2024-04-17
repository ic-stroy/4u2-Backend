@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')

    <style>
        #headingNine{
            height: auto;
            display: flex;
            align-items: center;
        }
        #headingNine a{
            width: 100%;
            font-size: 15px;
        }
        #headingNine .card-header{
            padding: 14px;
        }
        .function-column{
            display: flex;
            align-items: center;
        }
        .function-column>div{
            width: 100%;
        }
        .order_content{
            display: flex;
            flex-direction: column;
        }
        .white_text{
            color:white
        }
        .carousel-control-prev, .carousel-control-next{
            top:50%;
            background-color: transparent;
        }
        .carousel-control-prev{
            margin-left: -30px;
        }
        .carousel-control-next{
            margin-right: -30px;
        }
        .carousel-control-prev-icon, .carousel-control-next-icon{
            color:#6C8BC0 !important;
            width: 34px;
            height: 34px;
        }
        .carousel-inner{
            padding:0px;
        }
        .order_product_images>img{
            transition: 0.4s;
        }
        .order_product_images>img:hover{
            transform: scale(1.14);
        }
        .color_green{
            color:forestgreen;
        }
        .color_red{
            color:red;
        }
        .color_reddish{
            color:#ff8000;
        }
    </style>
    @if(!empty($orderedOrders) || !empty($performedOrders) || !empty($cancelledOrders) || !empty($acceptedByRecipientOrders))
        <div id="success-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="card">
                        <div class="card-header">
                            <div class="text-center">
                                <i class="dripicons-warning h1 text-success"></i>
                                <h4 class="mt-2">{{ translate('Are you sure you want to accept this order')}}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-around" id="product_image"></div>
                            <div id="product_name"></div>
                            <div id="order_size"></div>
                            <div id="order_color"></div>
                            <div id="remaining_quantity"></div>
                            <div id="order_quantity"></div>
                        </div>
                        <div class="card-footer">
                            <form class="d-flex justify-content-between" action="" method="POST" id="perform_order">
                                @csrf
                                @method('POST')
                                <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal"> {{ translate('No')}}</button>
                                <button type="submit" class="btn btn-success my-2"> {{ translate('Yes')}} </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="carousel-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color: #989CA2">
                    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner" id="carousel_product_images">

                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleFade" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- /.modal -->
        <div id="carousel-upload-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color: #989CA2;">
                    <div id="carouselExample_Fade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner" id="carousel_product_upload_images"></div>
                        <a class="carousel-control-prev" href="#carouselExample_Fade" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExample_Fade" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="warning-order-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-warning"></i>
                            <h4 class="mt-2">{{ translate('Are you sure to cancel this order')}}</h4>
                            <form style="display: inline-block;" action="" method="POST" id="cancell_order">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal"> {{ translate('No')}}</button>
                                <button type="submit" class="btn btn-warning my-2"> {{ translate('Yes')}} </button>
                            </form>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div id="waiting-to-perform-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-warning"></i>
                            <h4 class="mt-2">{{translate('Waiting for superadmin performing')}}</h4>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div id="accepted-success-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-success"></i>
                            {{--                        <h4 class="mt-2">{{translate('Accepted by super admin')}}</h4>--}}
                            <h4 class="mt-2">{{translate('Performed by admin')}}</h4>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div id="accepted-by-recepient-success-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-success"></i>
                            <h4 class="mt-2">{{translate('Accepted by recepient')}}</h4>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div class="card">

            <div class="card-body">
                <ul class="nav nav-pills navtab-bg nav-justified">
                    <li class="nav-item">
                        <a href="#ordered" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                            {{translate('Ordered')}}
                            @if(count($orderedOrders) > 0)
                                <span class="badge bg-danger"> {{translate('new')}} {{count($orderedOrders)}}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#performed" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            {{translate('Performed')}}
                            @if(count($performedOrders) > 0)
                                <span class="badge bg-danger"> {{count($performedOrders)}}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#cancelled" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            {{translate('Cancelled')}}
                            @if(count($cancelledOrders) > 0)
                                <span class="badge bg-danger"> {{count($cancelledOrders)}}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#accepted_by_recepient" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            {{translate('Accepted by recepient')}}
                            @if(count($acceptedByRecipientOrders) > 0)
                                <span class="badge bg-danger"> {{count($acceptedByRecipientOrders)}}</span>
                            @endif
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="ordered">
                        <h4 class="mt-0 header-title">{{translate('Ordered orders list')}}</h4>
                        @php
                            $i=0
                        @endphp
                        @foreach($orderedOrders as $order)
                            @php
                                $i++;
                                if(!empty($order['order']->user->personalInfo)){
                                    $first_name = isset($order['order']->user->personalInfo->first_name)?$order['order']->user->personalInfo->first_name.' ':'';
                                    $last_name = isset($order['order']->user->personalInfo->last_name)?$order['order']->user->personalInfo->last_name.' ':'';
                                    $middle_name = isset($order['order']->user->personalInfo->middle_name)?$order['order']->user->personalInfo->middle_name:'';
                                    $user_name = $first_name.''.$last_name.''.$middle_name;
                                }
                            @endphp
                            <div class="accordion custom-accordion" id="custom-accordion-one">
                                <div class="card mb-0">
                                    <div class="card-header" id="headingNine">
                                    <span class="m-0 position-relative" style="width: 100%">
                                        <a class="custom-accordion-title text-reset d-block"
                                           data-bs-toggle="collapse" href="#collapseNine{{$i}}"
                                           aria-expanded="true" aria-controls="collapseNine">
                                            <div class="row align-items-center">
                                                <div class="col-8 d-flex flex-column justify-content-center">
                                                    @if((int)$order['product_types'] < count($order['order']->orderDetail))
                                                        <h4 style="line-height: 2; font-size: 16px">
                                                            {{translate('There are ')}}
                                                            <b>{{count($order['order']->orderDetail)}}</b>
                                                            {{translate('items in order. these are selling for')}}
                                                            <b>{{$order['order']->all_price}}</b>
                                                            @if($order['order']->coupon_price)
                                                                {{translate('all coupon costed')}}
                                                                <b style="color: #10C469">{{$order['order']->coupon_price}}</b>
                                                            @endif
                                                        </h4>
                                                        <hr style="margin:4px">
                                                    @endif
                                                    <h4 style="line-height: 2; font-size: 16px">
                                                        @if($user_name){{$user_name}}@endif
                                                        <span style="color: orange">{{translate('Ordered')}}</span>
                                                        @if($order['product_types'] != 0)
                                                            <b>{{ $order['product_types'] }}</b>
                                                            {{translate('products are yours. you will sell for')}}
                                                            <b style="color: #10C469">{{$order['company_product_price']}}</b>
                                                            @if($order['order_coupon_price'] != 0)
                                                                {{translate('Your coupon is costed')}}
                                                                <b style="color: red">{{$order['order_coupon_price']}}</b>
                                                            @endif
                                                            @if($order['company_discount_price'] != 0)
                                                                {{translate('your discount is costed')}}
                                                                <b style="color: red">{{$order['company_discount_price']}}</b>
                                                            @endif
                                                        @endif
                                                        @if($order['order']->payment_method == \App\Constants::CASH_ON_DELIVERY)
                                                            <span class="badge bg-info">{{translate('cash on delivery')}}</span>
                                                        @elseif($order['order']->payment_method == \App\Constants::BANK_CARD)
                                                            <span class="badge bg-info">{{translate('bank card')}}</span>
                                                        @endif
                                                    </h4>
                                                    @if($order['performed_company_product_price'] != 0)
                                                        <hr style="margin:4px">
                                                        <h4 style="line-height: 2; font-size: 16px">
                                                            <span style="color: #10C469">{{translate('Performed')}}</span>
                                                            @if($order['performed_product_types'] != 0)
                                                                <b>{{ $order['performed_product_types'] }}</b>
                                                                {{translate('you are selling for')}}
                                                                <b style="color: #10C469">{{$order['performed_company_product_price']}}</b>
                                                                @if($order['performed_order_coupon_price'] != 0)
                                                                    {{translate('your coupon is costed')}}
                                                                    <b style="color: red">{{$order['performed_order_coupon_price']}}</b>
                                                                @endif
                                                                @if($order['performed_company_discount_price'] != 0)
                                                                    {{translate('your discount is costed')}}
                                                                    <b style="color: red">{{$order['performed_company_discount_price']}}</b>
                                                                @endif
                                                            @endif
                                                        </h4>
                                                    @elseif($order['order']->status == \App\Constants::CANCELLED)
                                                        <hr style="margin:4px">
                                                        <b style="line-height: 2; font-size: 16px; color: red">{{translate('You cancelled all products !')}}</b>
                                                    @endif
                                                </div>
                                                <div class="col-4 d-flex justify-content-between">
                                                        @if($order['order']->status)
                                                        @switch($order['order']->status)
                                                            @case(\App\Constants::ORDERED)
                                                            <span style="line-height: 1; font-size: 16px">
                                                                    <b>{{translate('ORDERED')}}</b>
                                                                </span>
                                                            <span class="badge bg-danger">
                                                                    {{translate('New')}}
                                                                </span>
                                                            @break
                                                            @case(\App\Constants::PERFORMED)
                                                            <span style="line-height: 1; font-size: 16px">
                                                                    <b>{{translate('PERFORMED')}}</b>
                                                                </span>
                                                            <span class="badge bg-success">
                                                                    {{translate('In progress')}}
                                                                </span>
                                                            @break
                                                            @case(\App\Constants::CANCELLED)
                                                            <span style="line-height: 1; font-size: 16px">
                                                                    <b>{{translate('CANCELLED')}}</b>
                                                                </span>
                                                            <span class="badge bg-danger">
                                                                    {{translate('Cancelled')}}
                                                                </span>
                                                            @break
                                                            @case(\App\Constants::ACCEPTED_BY_RECIPIENT)
                                                            <span style="line-height: 1; font-size: 16px">
                                                                    <b>{{translate('ACCEPTED BY RECIPIENT')}}</b>
                                                                </span>
                                                            <span class="badge bg-info">>
                                                                    {{translate('Delivered')}}
                                                                </span>
                                                            @break
                                                        @endswitch
                                                    @endif
                                                    {{--                                            @if($order['order_detail_is_ordered'] == true)--}}
                                                    {{--                                                <span class="badge bg-danger">{{translate('new')}}</span>--}}
                                                    {{--                                            @endif--}}
                                                    <span>
                                                        <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </span>
                                    </div>
                                    <div id="collapseNine{{$i}}" class="collapse fade"
                                         aria-labelledby="headingFour"
                                         data-bs-parent="#custom-accordion-one">
                                        @foreach($order['products_with_anime'] as $products_with_anime)
                                            @php
                                                $order_detail_image_front_exists = storage_path('app/public/warehouse/'.$products_with_anime[0]->image_front);
                                                if(file_exists($order_detail_image_front_exists)){
                                                    $order_detail_image_front = asset('storage/warehouse/'.$products_with_anime[0]->image_front);
                                                }else{
                                                    $order_detail_image_front = null;
                                                }
                                                $order_detail_image_back_exists = storage_path('app/public/warehouse/'.$products_with_anime[0]->image_back);
                                                if(file_exists($order_detail_image_back_exists)){
                                                    $order_detail_image_back = asset('storage/warehouse/'.$products_with_anime[0]->image_back);
                                                }else{
                                                    $order_detail_image_back = null;
                                                }
                                                if(!$order_detail_image_front && !$order_detail_image_back){
                                                    if($products_with_anime[0]->product->images){
                                                        $images_ = json_decode($products_with_anime[0]->product->images);
                                                        $images = [];
                                                        foreach ($images_ as $key => $image_){
                                                            if($key < 2){
                                                                $images[] = asset('storage/products/'.$image_);
                                                            }
                                                        }
                                                    }else{
                                                        $images = [];
                                                    }

                                                }else{
                                                    $images = [$order_detail_image_front??'no', $order_detail_image_back??'no'];
                                                }
                                                $product_discount_withouth_expire = !empty($products_with_anime[0]->product->discount_whithout_expire)?$products_with_anime[0]->product->discount_whithout_expire->percent:0;
                                            @endphp
                                            <hr>
                                            <div class="row">
                                                <div class="col-2 order_product_images" onclick='getImages("{{implode(" ", $images)}}")' data-bs-toggle="modal" data-bs-target="#carousel-modal">
                                                    <img src="{{$images[0]}}" alt="" height="144px">
                                                </div>
                                                <div class="col-2 order_product_images" onclick='getUploads("{{implode(" ", $products_with_anime[2])}}")' data-bs-toggle="modal" data-bs-target="#carousel-upload-modal">
                                                    <img src="{{$products_with_anime[2][0]}}" alt="" height="144px">
                                                </div>
                                                <div class="col-4 order_content">
                                                    <h4>{{translate('Animated order')}}</h4>
                                                    <span>{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}</span>
                                                    @if($product_discount_withouth_expire != 0)
                                                        <span>{{translate('Discount')}}: <b style="color:red">{{$product_discount_withouth_expire}} %</b></span>
                                                    @endif
                                                    @if($products_with_anime[1])
                                                        <span>{{translate('Price')}}: <b>{{$products_with_anime[0]->price}}</b> {!! !empty($products_with_anime[0]->quantity)?translate('Quantity').": <b>". $products_with_anime[0]->quantity."</b>":'' !!}</span>
                                                    @endif
                                                    @if($products_with_anime[1])
                                                        <span>{{translate('Sum')}}: <b>{{$products_with_anime[1]}}</b></span>
                                                    @endif
                                                    @if(!empty($products_with_anime[0]->size))
                                                        <span>{{translate('Size')}}: <b>{{$products_with_anime[0]->size->name}}</b></span>
                                                    @endif
                                                    @if(!empty($products_with_anime[0]->color))
                                                        <span>{{translate('Color')}}: <b>{{$products_with_anime[0]->color->name}}</b></span>
                                                    @endif
                                                    @if(!empty($products_with_anime[0]->updated_at))
                                                        <span>{{translate('Ordered')}}: <b>{{$products_with_anime[0]->updated_at}}</b></span>
                                                    @endif
                                                </div>
                                                <div class="col-1 d-flex justify-content-around align-items-center">
                                                    @switch($products_with_anime[0]->status)
                                                        @case(\App\Constants::ORDER_DETAIL_ORDERED)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('New')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                        <div>
                                                            <span class="badge bg-success">{{translate('Performed')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Cancelled')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED_BY_SUPERADMIN):
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Performed by superadmin')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)
                                                        <div>
                                                            <span class="badge bg-info">{{translate('Finished')}}</span>
                                                        </div>
                                                        @break
                                                    @endswitch
                                                </div>
                                                <div class="function-column col-3">
                                                    <div class="d-flex justify-content-around">
                                                        @switch($products_with_anime[0]->status)
                                                            @case(\App\Constants::ORDER_DETAIL_ORDERED)
                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal"
                                                                    onclick='accepting_anime_order(
                                                                        "{{$products_with_anime[0]->quantity??''}}",
                                                                        "{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}",
                                                                        "{{!empty($products_with_anime[0]->size)?$products_with_anime[0]->size->name:''}}",
                                                                        "{{!empty($products_with_anime[0]->color)?$products_with_anime[0]->color->name:''}}",
                                                                        "{{isset($images[0])?$images[0]:''}}",
                                                                        "{{isset($images[1])?$images[1]:''}}",
                                                                        "{{route('perform_order_detail', $products_with_anime[0]->id)}}"
                                                                        )' data-url="">
                                                                <i class="fa fa-check"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products_with_anime[0]->id)}}")' data-url=""><i class="fa fa-times"></i></button>
                                                            @break
                                                            @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                            {{--                                                    <button type="button" class="btn btn-warning delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#waiting-to-perform-alert-modal" title="{{translate('Waiting for superadmin performing')}}"><i class="fa fa-question"></i></button>--}}
                                                            <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products_with_anime[0]->id)}}")' data-url=""><i class="fa fa-times"></i></button>
                                                            @break
                                                            @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal"
                                                                    onclick='accepting_anime_order(
                                                                        "{{$products_with_anime[0]->quantity??''}}",
                                                                        "{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}",
                                                                        "{{!empty($products_with_anime[0]->size)?$products_with_anime[0]->size->name:''}}",
                                                                        "{{!empty($products_with_anime[0]->color)?$products_with_anime[0]->color->name:''}}",
                                                                        "{{isset($images[0])?$images[0]:''}}",
                                                                        "{{isset($images[1])?$images[1]:''}}",
                                                                        "{{route('perform_order_detail', $products_with_anime[0]->id)}}"
                                                                        )' data-url="">
                                                                <i class="fa fa-check"></i>
                                                            </button>
                                                            @break
                                                            @case(\App\Constants::ORDER_DETAIL_PERFORMED_BY_SUPERADMIN)
                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-success-modal" title="{{translate('Performed by admin')}}"><i class="fa fa-ellipsis-h"></i></button>
                                                            @break
                                                            @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)
                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-by-recepient-success-modal" title="{{translate('Order accepted by recipient')}}"><i class="fa fa-ellipsis-h"></i></button>
                                                            @break
                                                        @endswitch
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @foreach($order['products'] as $products)
                                            @php
                                                if(!empty($products[0]->warehouse)){
                                                    $discount_withouth_expire = !empty($products[0]->warehouse->discount_withouth_expire)?$products[0]->warehouse->discount_withouth_expire->percent:0;
                                                    $product_discount_withouth_expire = !empty($products[0]->warehouse->product_discount_withouth_expire)?$products[0]->warehouse->product_discount_withouth_expire->percent:0;
                                                }else{
                                                    $discount_withouth_expire = 0;
                                                    $product_discount_withouth_expire = 0;
                                                }
                                                if(!empty($products[0]->warehouse) && $products[0]->warehouse->images){
                                                    $images_ = json_decode($products[0]->warehouse->images);
                                                    $images = [];
                                                    foreach ($images_ as $key => $image_){
                                                        $images[] = asset('storage/warehouses/'.$image_);
                                                    }
                                                }elseif(!empty($products[0]->warehouse->product) && $products[0]->warehouse->product->images){
                                                    $images_ = json_decode($products[0]->warehouse->product->images);
                                                    $images = [];
                                                    foreach ($images_ as $key => $image_){
                                                        $images[] = asset('storage/products/'.$image_);
                                                    }
                                                }else{
                                                    $images = [];
                                                }
                                            @endphp
                                            <hr>
                                            <div class="row">
                                                <div class="col-3 order_product_images" onclick='getImages("{{implode(" ", $images)}}")'  data-bs-toggle="modal" data-bs-target="#carousel-modal">
                                                    <img src="{{$images[0]}}" alt="" height="144px">
                                                </div>
                                                <div class="col-1"></div>
                                                <div class="col-4 order_content">
                                                    <h4>{{translate('Order')}}</h4>
                                                    @if(!empty($products[0]->warehouse) && $products[0]->warehouse->name)
                                                        <span><b>{{$products[0]->warehouse->name}}</b></span>
                                                    @elseif(!empty($products[0]->warehouse->product) && $products[0]->warehouse->product->name)
                                                        <span><b>{{$products[0]->warehouse->product->name}}</b></span>
                                                    @endif
                                                    @if($products[0]->price)
                                                        <span>{{translate('Price')}}: <b>{{$products[0]->price}}</b> {!! !empty($products[0]->quantity)?translate('Quantity').': '."<b>".$products[0]->quantity."</b>":'' !!}</span>
                                                    @endif
                                                    @if($products[1])
                                                        <span>{{translate('Sum')}}: <b>{{$products[1]}}</b></span>
                                                    @endif
                                                    @if($discount_withouth_expire != 0)
                                                        <span>{{translate('Discount')}}: <b style="color: red">{{(int)$discount_withouth_expire}} %</b></span>
                                                    @elseif($product_discount_withouth_expire != 0)
                                                        <span>{{translate('Discount')}}: <b style="color: red">{{(int)$product_discount_withouth_expire}} %</b></span>
                                                    @endif
                                                    @if(!empty($products[0]->size))
                                                        <span>{{translate('Size')}}: <b>{{$products[0]->size->name}}</b> {{translate('Color')}}: <b>{{$products[0]->color->name}}</b></span>
                                                    @endif
                                                    <span>{{translate('Ordered')}}: <b>{{$products[0]->updated_at}}</b></span>
                                                </div>
                                                <div class="col-1 d-flex justify-content-around align-items-center">
                                                    @switch($products[0]->status)
                                                        @case(\App\Constants::ORDER_DETAIL_ORDERED):
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('New')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                        <div>
                                                            <span class="badge bg-success">{{translate('Performed')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Cancelled')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED_BY_SUPERADMIN)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Performed by superadmin')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)
                                                        <div>
                                                            <span class="badge bg-info">{{translate('Finished')}}</span>
                                                        </div>
                                                        @break
                                                    @endswitch
                                                </div>
                                                <div class="function-column col-3">
                                                    <div class="d-flex justify-content-around">
                                                        @php
                                                            if(!empty($products[0]->warehouse) && $products[0]->warehouse->name){
                                                                $product_name = $products[0]->warehouse->name??'';
                                                            }else if(!empty($products[0]->warehouse->product) && $products[0]->warehouse->product->name){
                                                                $product_name = $products[0]->warehouse->product->name??'';
                                                            }
                                                        @endphp
                                                        @switch($products[0]->status)
                                                            @case(\App\Constants::ORDER_DETAIL_ORDERED)
                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                    onclick='accepting_order(
                                                                        "{{$products[0]->quantity}}",
                                                                        "{{$products[0]->warehouse->quantity - $products[0]->quantity }}",
                                                                        "{{!empty($products[0]->color)?$products[0]->color->name:''}}",
                                                                        "{{!empty($products[0]->size)?$products[0]->size->name:''}}",
                                                                        "{{$product_name}}",
                                                                        "{{isset($images[0])?$images[0]:''}}",
                                                                        "{{isset($images[1])?$images[1]:''}}",
                                                                        "{{route('perform_order_detail', $products[0]->id)}}"
                                                                        )'>
                                                                <i class="fa fa-check"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products[0]->id)}}")' data-url=""><i class="fa fa-times"></i></button>
                                                            @break
                                                            @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                            {{--                                                    <button type="button" class="btn btn-warning delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#waiting-to-perform-alert-modal" title="{{translate('Waiting for superadmin performing')}}"><i class="fa fa-question"></i></button>--}}
                                                            <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products[0]->id)}}")' data-url=""><i class="fa fa-times"></i></button>
                                                            @break
                                                            @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                    onclick='accepting_order(
                                                                        "{{$products[0]->quantity}}",
                                                                        "{{$products[0]->warehouse->quantity - $products[0]->quantity }}",
                                                                        "{{!empty($products[0]->color)?$products[0]->color->name:''}}",
                                                                        "{{!empty($products[0]->size)?$products[0]->size->name:''}}",
                                                                        "{{$product_name}}",
                                                                        "{{isset($images[0])?$images[0]:''}}",
                                                                        "{{isset($images[1])?$images[1]:''}}",
                                                                        "{{route('perform_order_detail', $products[0]->id)}}"
                                                                        )'>
                                                                <i class="fa fa-check"></i>
                                                            </button>
                                                            @break
                                                            @case(\App\Constants::ORDER_DETAIL_PERFORMED_BY_SUPERADMIN)
                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-success-modal" title="{{translate('Performed by admin')}}"><i class="fa fa-ellipsis-h"></i></button>
                                                            @break
                                                            @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)
                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-by-recepient-success-modal" title="{{translate('Order accepted by recipient')}}"><i class="fa fa-ellipsis-h"></i></button>
                                                            @break
                                                        @endswitch
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <br>
                        @endforeach
                    </div>
                    <div class="tab-pane" id="performed">
                        <h4 class="mt-0 header-title">{{translate('Performed orders list')}}</h4>
                        @php
                            $i=0
                        @endphp
                        @foreach($performedOrders as $order)
                            @php
                                $i++;
                                if(!empty($order['order']->user->personalInfo)){
                                    $first_name = isset($order['order']->user->personalInfo->first_name)?$order['order']->user->personalInfo->first_name.' ':'';
                                    $last_name = isset($order['order']->user->personalInfo->last_name)?$order['order']->user->personalInfo->last_name.' ':'';
                                    $middle_name = isset($order['order']->user->personalInfo->middle_name)?$order['order']->user->personalInfo->middle_name:'';
                                    $user_name = $first_name.''.$last_name.''.$middle_name;
                                }
                            @endphp
                            <div class="accordion custom-accordion" id="custom-accordion-one">
                                <div class="card mb-0">
                                    <div class="card-header" id="headingNine">
                                    <span class="m-0 position-relative" style="width: 100%">
                                        <a class="custom-accordion-title text-reset d-block"
                                           data-bs-toggle="collapse" href="#collapseNine{{$i}}"
                                           aria-expanded="true" aria-controls="collapseNine">
                                            <div class="row align-items-center">
                                                <div class="col-8 d-flex flex-column justify-content-center">
                                                    @if((int)$order['product_types'] < count($order['order']->orderDetail))
                                                        <h4 style="line-height: 2; font-size: 16px">
                                                            {{translate('There are ')}}
                                                            <b>{{count($order['order']->orderDetail)}}</b>
                                                            {{translate('items in order. these are selling for')}}
                                                            <b>{{$order['order']->all_price}}</b>
                                                            @if($order['order']->coupon_price)
                                                                {{translate('all coupon costed')}}
                                                                <b style="color: #10C469">{{$order['order']->coupon_price}}</b>
                                                            @endif
                                                        </h4>
                                                        <hr style="margin:4px">
                                                    @endif
                                                    <h4 style="line-height: 2; font-size: 16px">
                                                        @if($user_name){{$user_name}}@endif
                                                        <span style="color: orange">{{translate('Ordered')}}</span>
                                                        @if($order['product_types'] != 0)
                                                            <b>{{ $order['product_types'] }}</b>
                                                            {{translate('products are yours. you will sell for')}}
                                                            <b style="color: #10C469">{{$order['company_product_price']}}</b>
                                                            @if($order['order_coupon_price'] != 0)
                                                                {{translate('Your coupon is costed')}}
                                                                <b style="color: red">{{$order['order_coupon_price']}}</b>
                                                            @endif
                                                            @if($order['company_discount_price'] != 0)
                                                                {{translate('your discount is costed')}}
                                                                <b style="color: red">{{$order['company_discount_price']}}</b>
                                                            @endif
                                                        @endif
                                                        @if($order['order']->payment_method == \App\Constants::CASH_ON_DELIVERY)
                                                            <span class="badge bg-info">{{translate('cash on delivery')}}</span>
                                                        @elseif($order['order']->payment_method == \App\Constants::BANK_CARD)
                                                            <span class="badge bg-info">{{translate('bank card')}}</span>
                                                        @endif
                                                    </h4>
                                                    @if($order['performed_company_product_price'] != 0)
                                                        <hr style="margin:4px">
                                                        <h4 style="line-height: 2; font-size: 16px">
                                                            <span style="color: #10C469">{{translate('Performed')}}</span>
                                                            @if($order['performed_product_types'] != 0)
                                                                <b>{{ $order['performed_product_types'] }}</b>
                                                                {{translate('you are selling for')}}
                                                                <b style="color: #10C469">{{$order['performed_company_product_price']}}</b>
                                                                @if($order['performed_order_coupon_price'] != 0)
                                                                    {{translate('your coupon is costed')}}
                                                                    <b style="color: red">{{$order['performed_order_coupon_price']}}</b>
                                                                @endif
                                                                @if($order['performed_company_discount_price'] != 0)
                                                                    {{translate('your discount is costed')}}
                                                                    <b style="color: red">{{$order['performed_company_discount_price']}}</b>
                                                                @endif
                                                            @endif
                                                        </h4>
                                                    @elseif($order['order']->status == \App\Constants::CANCELLED)
                                                        <hr style="margin:4px">
                                                        <b style="line-height: 2; font-size: 16px; color: red">{{translate('You cancelled all products !')}}</b>
                                                    @endif
                                                </div>
                                                <div class="col-4 d-flex justify-content-between">
                                                        @if($order['order']->status)
                                                        @switch($order['order']->status)
                                                            @case(\App\Constants::ORDERED)
                                                            <span style="line-height: 1; font-size: 16px">
                                                                        <b>{{translate('ORDERED')}}</b>
                                                                    </span>
                                                            <span class="badge bg-danger">
                                                                        {{translate('New')}}
                                                                    </span>
                                                            @break
                                                            @case(\App\Constants::PERFORMED)
                                                            <span style="line-height: 1; font-size: 16px">
                                                                        <b>{{translate('PERFORMED')}}</b>
                                                                    </span>
                                                            <span class="badge bg-success">
                                                                        {{translate('In progress')}}
                                                                    </span>
                                                            @break
                                                            @case(\App\Constants::CANCELLED)
                                                            <span style="line-height: 1; font-size: 16px">
                                                                        <b>{{translate('CANCELLED')}}</b>
                                                                    </span>
                                                            <span class="badge bg-danger">
                                                                        {{translate('Cancelled')}}
                                                                    </span>
                                                            @break
                                                            @case(\App\Constants::ACCEPTED_BY_RECIPIENT)
                                                            <span style="line-height: 1; font-size: 16px">
                                                                        <b>{{translate('ACCEPTED BY RECIPIENT')}}</b>
                                                                    </span>
                                                            <span class="badge bg-info">>
                                                                        {{translate('Delivered')}}
                                                                    </span>
                                                            @break
                                                        @endswitch
                                                    @endif
                                                    {{--                                            @if($order['order_detail_is_ordered'] == true)--}}
                                                    {{--                                                <span class="badge bg-danger">{{translate('new')}}</span>--}}
                                                    {{--                                            @endif--}}
                                                    <span>
                                                        <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </span>
                                    </div>
                                    <div id="collapseNine{{$i}}" class="collapse fade"
                                         aria-labelledby="headingFour"
                                         data-bs-parent="#custom-accordion-one">
                                        @foreach($order['products_with_anime'] as $products_with_anime)
                                            @php
                                                $order_detail_image_front_exists = storage_path('app/public/warehouse/'.$products_with_anime[0]->image_front);
                                                if(file_exists($order_detail_image_front_exists)){
                                                    $order_detail_image_front = asset('storage/warehouse/'.$products_with_anime[0]->image_front);
                                                }else{
                                                    $order_detail_image_front = null;
                                                }
                                                $order_detail_image_back_exists = storage_path('app/public/warehouse/'.$products_with_anime[0]->image_back);
                                                if(file_exists($order_detail_image_back_exists)){
                                                    $order_detail_image_back = asset('storage/warehouse/'.$products_with_anime[0]->image_back);
                                                }else{
                                                    $order_detail_image_back = null;
                                                }
                                                if(!$order_detail_image_front && !$order_detail_image_back){
                                                    if($products_with_anime[0]->product->images){
                                                        $images_ = json_decode($products_with_anime[0]->product->images);
                                                        $images = [];
                                                        foreach ($images_ as $key => $image_){
                                                            if($key < 2){
                                                                $images[] = asset('storage/products/'.$image_);
                                                            }
                                                        }
                                                    }else{
                                                        $images = [];
                                                    }

                                                }else{
                                                    $images = [$order_detail_image_front??'no', $order_detail_image_back??'no'];
                                                }
                                                $product_discount_withouth_expire = !empty($products_with_anime[0]->product->discount_whithout_expire)?$products_with_anime[0]->product->discount_whithout_expire->percent:0;
                                            @endphp
                                            <hr>
                                            <div class="row">
                                                <div class="col-2 order_product_images" onclick='getImages("{{implode(" ", $images)}}")' data-bs-toggle="modal" data-bs-target="#carousel-modal">
                                                    <img src="{{$images[0]}}" alt="" height="144px">
                                                </div>
                                                <div class="col-2 order_product_images" onclick='getUploads("{{implode(" ", $products_with_anime[2])}}")' data-bs-toggle="modal" data-bs-target="#carousel-upload-modal">
                                                    <img src="{{$products_with_anime[2][0]}}" alt="" height="144px">
                                                </div>
                                                <div class="col-4 order_content">
                                                    <h4>{{translate('Animated order')}}</h4>
                                                    <span>{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}</span>
                                                    @if($product_discount_withouth_expire != 0)
                                                        <span>{{translate('Discount')}}: <b style="color:red">{{$product_discount_withouth_expire}} %</b></span>
                                                    @endif
                                                    @if($products_with_anime[1])
                                                        <span>{{translate('Price')}}: <b>{{$products_with_anime[0]->price}}</b> {!! !empty($products_with_anime[0]->quantity)?translate('Quantity').": <b>". $products_with_anime[0]->quantity."</b>":'' !!}</span>
                                                    @endif
                                                    @if($products_with_anime[1])
                                                        <span>{{translate('Sum')}}: <b>{{$products_with_anime[1]}}</b></span>
                                                    @endif
                                                    @if(!empty($products_with_anime[0]->size))
                                                        <span>{{translate('Size')}}: <b>{{$products_with_anime[0]->size->name}}</b></span>
                                                    @endif
                                                    @if(!empty($products_with_anime[0]->color))
                                                        <span>{{translate('Color')}}: <b>{{$products_with_anime[0]->color->name}}</b></span>
                                                    @endif
                                                    @if(!empty($products_with_anime[0]->updated_at))
                                                        <span>{{translate('Ordered')}}: <b>{{$products_with_anime[0]->updated_at}}</b></span>
                                                    @endif
                                                </div>
                                                <div class="col-1 d-flex justify-content-around align-items-center">
                                                    @switch($products_with_anime[0]->status)
                                                        @case(\App\Constants::ORDER_DETAIL_ORDERED)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('New')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                        <div>
                                                            <span class="badge bg-success">{{translate('Performed')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Cancelled')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED_BY_SUPERADMIN):
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Performed by superadmin')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)
                                                        <div>
                                                            <span class="badge bg-info">{{translate('Finished')}}</span>
                                                        </div>
                                                        @break
                                                    @endswitch
                                                </div>
                                                <div class="function-column col-3">
                                                    <div class="d-flex justify-content-around">
                                                        @switch($products_with_anime[0]->status)
                                                            @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                            <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products_with_anime[0]->id)}}")' data-url="">{{translate('Cancel')}}</button>
                                                            @break
                                                            @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                    onclick='accepting_anime_order(
                                                                        "{{$products_with_anime[0]->quantity??''}}",
                                                                        "{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}",
                                                                        "{{!empty($products_with_anime[0]->size)?$products_with_anime[0]->size->name:''}}",
                                                                        "{{!empty($products_with_anime[0]->color)?$products_with_anime[0]->color->name:''}}",
                                                                        "{{isset($images[0])?$images[0]:''}}",
                                                                        "{{isset($images[1])?$images[1]:''}}",
                                                                        "{{route('perform_order_detail', $products_with_anime[0]->id)}}")'>
                                                                {{translate('Perform')}}
                                                            </button>
                                                            @break
                                                        @endswitch
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @foreach($order['products'] as $products)
                                            @php
                                                if(!empty($products[0]->warehouse)){
                                                    $discount_withouth_expire = !empty($products[0]->warehouse->discount_withouth_expire)?$products[0]->warehouse->discount_withouth_expire->percent:0;
                                                    $product_discount_withouth_expire = !empty($products[0]->warehouse->product_discount_withouth_expire)?$products[0]->warehouse->product_discount_withouth_expire->percent:0;
                                                }else{
                                                    $discount_withouth_expire = 0;
                                                    $product_discount_withouth_expire = 0;
                                                }
                                                if(!empty($products[0]->warehouse) && $products[0]->warehouse->images){
                                                    $images_ = json_decode($products[0]->warehouse->images);
                                                    $images = [];
                                                    foreach ($images_ as $key => $image_){
                                                        $images[] = asset('storage/warehouses/'.$image_);
                                                    }
                                                }elseif(!empty($products[0]->warehouse->product) && $products[0]->warehouse->product->images){
                                                    $images_ = json_decode($products[0]->warehouse->product->images);
                                                    $images = [];
                                                    foreach ($images_ as $key => $image_){
                                                        $images[] = asset('storage/products/'.$image_);
                                                    }
                                                }else{
                                                    $images = [];
                                                }
                                            @endphp
                                            <hr>
                                            <div class="row">
                                                <div class="col-3 order_product_images" onclick='getImages("{{implode(" ", $images)}}")'  data-bs-toggle="modal" data-bs-target="#carousel-modal">
                                                    <img src="{{$images[0]}}" alt="" height="144px">
                                                </div>
                                                <div class="col-1"></div>
                                                <div class="col-4 order_content">
                                                    <h4>{{translate('Order')}}</h4>
                                                    @if(!empty($products[0]->warehouse) && $products[0]->warehouse->name)
                                                        <span><b>{{$products[0]->warehouse->name}}</b></span>
                                                    @elseif(!empty($products[0]->warehouse->product) && $products[0]->warehouse->product->name)
                                                        <span><b>{{$products[0]->warehouse->product->name}}</b></span>
                                                    @endif
                                                    @if($products[0]->price)
                                                        <span>{{translate('Price')}}: <b>{{$products[0]->price}}</b> {!! !empty($products[0]->quantity)?translate('Quantity').': '."<b>".$products[0]->quantity."</b>":'' !!}</span>
                                                    @endif
                                                    @if($products[1])
                                                        <span>{{translate('Sum')}}: <b>{{$products[1]}}</b></span>
                                                    @endif
                                                    @if($discount_withouth_expire != 0)
                                                        <span>{{translate('Discount')}}: <b style="color: red">{{(int)$discount_withouth_expire}} %</b></span>
                                                    @elseif($product_discount_withouth_expire != 0)
                                                        <span>{{translate('Discount')}}: <b style="color: red">{{(int)$product_discount_withouth_expire}} %</b></span>
                                                    @endif
                                                    @if(!empty($products[0]->size))
                                                        <span>{{translate('Size')}}: <b>{{$products[0]->size->name}}</b> {{translate('Color')}}: <b>{{$products[0]->color->name}}</b></span>
                                                    @endif
                                                    <span>{{translate('Ordered')}}: <b>{{$products[0]->updated_at}}</b></span>
                                                </div>
                                                <div class="col-1 d-flex justify-content-around align-items-center">
                                                    @switch($products[0]->status)
                                                        @case(\App\Constants::ORDER_DETAIL_ORDERED):
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('New')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                        <div>
                                                            <span class="badge bg-success">{{translate('Performed')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Cancelled')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED_BY_SUPERADMIN)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Performed by superadmin')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)
                                                        <div>
                                                            <span class="badge bg-info">{{translate('Finished')}}</span>
                                                        </div>
                                                        @break
                                                    @endswitch
                                                </div>
                                                <div class="function-column col-3">
                                                    <div class="d-flex justify-content-around">
                                                        @php
                                                            if(!empty($products[0]->warehouse) && $products[0]->warehouse->name){
                                                                $product_name = $products[0]->warehouse->name??'';
                                                            }else if(!empty($products[0]->warehouse->product) && $products[0]->warehouse->product->name){
                                                                $product_name = $products[0]->warehouse->product->name??'';
                                                            }
                                                        @endphp
                                                        @switch($products[0]->status)
                                                            @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                            <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products[0]->id)}}")' data-url="">{{translate('Cancel')}}</button>
                                                            @break
                                                            @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                    onclick='accepting_order(
                                                                        "{{$products[0]->quantity}}",
                                                                        "{{$products[0]->warehouse->quantity - $products[0]->quantity }}",
                                                                        "{{!empty($products[0]->color)?$products[0]->color->name:''}}",
                                                                        "{{!empty($products[0]->size)?$products[0]->size->name:''}}",
                                                                        "{{$product_name}}",
                                                                        "{{isset($images[0])?$images[0]:''}}",
                                                                        "{{isset($images[1])?$images[1]:''}}",
                                                                        "{{route('perform_order_detail', $products[0]->id)}}"
                                                                        )'>
                                                                {{translate('Perform')}}
                                                            </button>
                                                            @break
                                                        @endswitch
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <br>
                        @endforeach
                    </div>
                    <div class="tab-pane" id="cancelled">
                        <h4 class="mt-0 header-title">{{translate('Cancelled orders list')}}</h4>
                        @php
                            $i=0
                        @endphp
                        @foreach($cancelledOrders as $order)
                            @php
                                $i++;
                                if(!empty($order['order']->user->personalInfo)){
                                    $first_name = isset($order['order']->user->personalInfo->first_name)?$order['order']->user->personalInfo->first_name.' ':'';
                                    $last_name = isset($order['order']->user->personalInfo->last_name)?$order['order']->user->personalInfo->last_name.' ':'';
                                    $middle_name = isset($order['order']->user->personalInfo->middle_name)?$order['order']->user->personalInfo->middle_name:'';
                                    $user_name = $first_name.''.$last_name.''.$middle_name;
                                }
                            @endphp
                            <div class="accordion custom-accordion" id="custom-accordion-one">
                                <div class="card mb-0">
                                    <div class="card-header" id="headingNine">
                            <span class="m-0 position-relative" style="width: 100%">
                                <a class="custom-accordion-title text-reset d-block"
                                   data-bs-toggle="collapse" href="#collapseNine{{$i}}"
                                   aria-expanded="true" aria-controls="collapseNine">
                                    <div class="row align-items-center">
                                        <div class="col-8 d-flex flex-column justify-content-center">
                                            @if((int)$order['product_types'] < count($order['order']->orderDetail))
                                                <h4 style="line-height: 2; font-size: 16px">
                                                    {{translate('There are ')}}
                                                    <b>{{count($order['order']->orderDetail)}}</b>
                                                    {{translate('items in order. these are selling for')}}
                                                    <b>{{$order['order']->all_price}}</b>
                                                    @if($order['order']->coupon_price)
                                                        {{translate('all coupon costed')}}
                                                        <b style="color: #10C469">{{$order['order']->coupon_price}}</b>
                                                    @endif
                                                </h4>
                                                <hr style="margin:4px">
                                            @endif
                                            <h4 style="line-height: 2; font-size: 16px">
                                                @if($user_name){{$user_name}}@endif
                                                <span style="color: orange">{{translate('Ordered')}}</span>
                                                @if($order['product_types'] != 0)
                                                    <b>{{ $order['product_types'] }}</b>
                                                    {{translate('products are yours. you will sell for')}}
                                                    <b style="color: #10C469">{{$order['company_product_price']}}</b>
                                                    @if($order['order_coupon_price'] != 0)
                                                        {{translate('Your coupon is costed')}}
                                                        <b style="color: red">{{$order['order_coupon_price']}}</b>
                                                    @endif
                                                    @if($order['company_discount_price'] != 0)
                                                        {{translate('your discount is costed')}}
                                                        <b style="color: red">{{$order['company_discount_price']}}</b>
                                                    @endif
                                                @endif
                                                @if($order['order']->payment_method == \App\Constants::CASH_ON_DELIVERY)
                                                    <span class="badge bg-info">{{translate('cash on delivery')}}</span>
                                                @elseif($order['order']->payment_method == \App\Constants::BANK_CARD)
                                                    <span class="badge bg-info">{{translate('bank card')}}</span>
                                                @endif
                                            </h4>
                                            @if($order['performed_company_product_price'] != 0)
                                                <hr style="margin:4px">
                                                <h4 style="line-height: 2; font-size: 16px">
                                                    <span style="color: #10C469">{{translate('Performed')}}</span>
                                                    @if($order['performed_product_types'] != 0)
                                                        <b>{{ $order['performed_product_types'] }}</b>
                                                        {{translate('you are selling for')}}
                                                        <b style="color: #10C469">{{$order['performed_company_product_price']}}</b>
                                                        @if($order['performed_order_coupon_price'] != 0)
                                                            {{translate('your coupon is costed')}}
                                                            <b style="color: red">{{$order['performed_order_coupon_price']}}</b>
                                                        @endif
                                                        @if($order['performed_company_discount_price'] != 0)
                                                            {{translate('your discount is costed')}}
                                                            <b style="color: red">{{$order['performed_company_discount_price']}}</b>
                                                        @endif
                                                    @endif
                                                </h4>
                                            @elseif($order['order']->status == \App\Constants::CANCELLED)
                                                <hr style="margin:4px">
                                                <b style="line-height: 2; font-size: 16px; color: red">{{translate('You cancelled all products !')}}</b>
                                            @endif
                                        </div>
                                        <div class="col-4 d-flex justify-content-between">
                                                @if($order['order']->status)
                                                @switch($order['order']->status)
                                                    @case(\App\Constants::ORDERED)
                                                    <span style="line-height: 1; font-size: 16px">
                                                                <b>{{translate('ORDERED')}}</b>
                                                            </span>
                                                    <span class="badge bg-danger">
                                                                {{translate('New')}}
                                                            </span>
                                                    @break
                                                    @case(\App\Constants::PERFORMED)
                                                    <span style="line-height: 1; font-size: 16px">
                                                                <b>{{translate('PERFORMED')}}</b>
                                                            </span>
                                                    <span class="badge bg-success">
                                                                {{translate('In progress')}}
                                                            </span>
                                                    @break
                                                    @case(\App\Constants::CANCELLED)
                                                    <span style="line-height: 1; font-size: 16px">
                                                                <b>{{translate('CANCELLED')}}</b>
                                                            </span>
                                                    <span class="badge bg-danger">
                                                                {{translate('Cancelled')}}
                                                            </span>
                                                    @break
                                                    @case(\App\Constants::ACCEPTED_BY_RECIPIENT)
                                                    <span style="line-height: 1; font-size: 16px">
                                                                <b>{{translate('ACCEPTED BY RECIPIENT')}}</b>
                                                            </span>
                                                    <span class="badge bg-info">>
                                                                {{translate('Delivered')}}
                                                            </span>
                                                    @break
                                                @endswitch
                                            @endif
                                            {{--                                            @if($order['order_detail_is_ordered'] == true)--}}
                                            {{--                                                <span class="badge bg-danger">{{translate('new')}}</span>--}}
                                            {{--                                            @endif--}}
                                            <span>
                                                <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </span>
                                    </div>
                                    <div id="collapseNine{{$i}}" class="collapse fade"
                                         aria-labelledby="headingFour"
                                         data-bs-parent="#custom-accordion-one">
                                        @foreach($order['products_with_anime'] as $products_with_anime)
                                            @php
                                                $order_detail_image_front_exists = storage_path('app/public/warehouse/'.$products_with_anime[0]->image_front);
                                                if(file_exists($order_detail_image_front_exists)){
                                                    $order_detail_image_front = asset('storage/warehouse/'.$products_with_anime[0]->image_front);
                                                }else{
                                                    $order_detail_image_front = null;
                                                }
                                                $order_detail_image_back_exists = storage_path('app/public/warehouse/'.$products_with_anime[0]->image_back);
                                                if(file_exists($order_detail_image_back_exists)){
                                                    $order_detail_image_back = asset('storage/warehouse/'.$products_with_anime[0]->image_back);
                                                }else{
                                                    $order_detail_image_back = null;
                                                }
                                                if(!$order_detail_image_front && !$order_detail_image_back){
                                                    if($products_with_anime[0]->product->images){
                                                        $images_ = json_decode($products_with_anime[0]->product->images);
                                                        $images = [];
                                                        foreach ($images_ as $key => $image_){
                                                            if($key < 2){
                                                                $images[] = asset('storage/products/'.$image_);
                                                            }
                                                        }
                                                    }else{
                                                        $images = [];
                                                    }

                                                }else{
                                                    $images = [$order_detail_image_front??'no', $order_detail_image_back??'no'];
                                                }
                                                $product_discount_withouth_expire = !empty($products_with_anime[0]->product->discount_whithout_expire)?$products_with_anime[0]->product->discount_whithout_expire->percent:0;
                                            @endphp
                                            <hr>
                                            <div class="row">
                                                <div class="col-2 order_product_images" onclick='getImages("{{implode(" ", $images)}}")' data-bs-toggle="modal" data-bs-target="#carousel-modal">
                                                    <img src="{{$images[0]}}" alt="" height="144px">
                                                </div>
                                                <div class="col-2 order_product_images" onclick='getUploads("{{implode(" ", $products_with_anime[2])}}")' data-bs-toggle="modal" data-bs-target="#carousel-upload-modal">
                                                    <img src="{{$products_with_anime[2][0]}}" alt="" height="144px">
                                                </div>
                                                <div class="col-4 order_content">
                                                    <h4>{{translate('Animated order')}}</h4>
                                                    <span>{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}</span>
                                                    @if($product_discount_withouth_expire != 0)
                                                        <span>{{translate('Discount')}}: <b style="color:red">{{$product_discount_withouth_expire}} %</b></span>
                                                    @endif
                                                    @if($products_with_anime[1])
                                                        <span>{{translate('Price')}}: <b>{{$products_with_anime[0]->price}}</b> {!! !empty($products_with_anime[0]->quantity)?translate('Quantity').": <b>". $products_with_anime[0]->quantity."</b>":'' !!}</span>
                                                    @endif
                                                    @if($products_with_anime[1])
                                                        <span>{{translate('Sum')}}: <b>{{$products_with_anime[1]}}</b></span>
                                                    @endif
                                                    @if(!empty($products_with_anime[0]->size))
                                                        <span>{{translate('Size')}}: <b>{{$products_with_anime[0]->size->name}}</b></span>
                                                    @endif
                                                    @if(!empty($products_with_anime[0]->color))
                                                        <span>{{translate('Color')}}: <b>{{$products_with_anime[0]->color->name}}</b></span>
                                                    @endif
                                                    @if(!empty($products_with_anime[0]->updated_at))
                                                        <span>{{translate('Ordered')}}: <b>{{$products_with_anime[0]->updated_at}}</b></span>
                                                    @endif
                                                </div>
                                                <div class="col-1 d-flex justify-content-around align-items-center">
                                                    @switch($products_with_anime[0]->status)
                                                        @case(\App\Constants::ORDER_DETAIL_ORDERED)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('New')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                        <div>
                                                            <span class="badge bg-success">{{translate('Performed')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Cancelled')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED_BY_SUPERADMIN):
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Performed by superadmin')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)
                                                        <div>
                                                            <span class="badge bg-info">{{translate('Finished')}}</span>
                                                        </div>
                                                        @break
                                                    @endswitch
                                                </div>
                                                <div class="function-column col-3">
                                                    <div class="d-flex justify-content-around">
                                                        <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                onclick='accepting_anime_order(
                                                                    "{{$products_with_anime[0]->quantity??''}}",
                                                                    "{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}",
                                                                    "{{!empty($products_with_anime[0]->size)?$products_with_anime[0]->size->name:''}}",
                                                                    "{{!empty($products_with_anime[0]->color)?$products_with_anime[0]->color->name:''}}",
                                                                    "{{isset($images[0])?$images[0]:''}}",
                                                                    "{{isset($images[1])?$images[1]:''}}",
                                                                    "{{route('perform_order_detail', $products_with_anime[0]->id)}}")' >
                                                            {{translate('Perform')}}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @foreach($order['products'] as $products)
                                            @php
                                                if(!empty($products[0]->warehouse)){
                                                    $discount_withouth_expire = !empty($products[0]->warehouse->discount_withouth_expire)?$products[0]->warehouse->discount_withouth_expire->percent:0;
                                                    $product_discount_withouth_expire = !empty($products[0]->warehouse->product_discount_withouth_expire)?$products[0]->warehouse->product_discount_withouth_expire->percent:0;
                                                }else{
                                                    $discount_withouth_expire = 0;
                                                    $product_discount_withouth_expire = 0;
                                                }
                                                if(!empty($products[0]->warehouse) && $products[0]->warehouse->images){
                                                    $images_ = json_decode($products[0]->warehouse->images);
                                                    $images = [];
                                                    foreach ($images_ as $key => $image_){
                                                        $images[] = asset('storage/warehouses/'.$image_);
                                                    }
                                                }elseif(!empty($products[0]->warehouse->product) && $products[0]->warehouse->product->images){
                                                    $images_ = json_decode($products[0]->warehouse->product->images);
                                                    $images = [];
                                                    foreach ($images_ as $key => $image_){
                                                        $images[] = asset('storage/products/'.$image_);
                                                    }
                                                }else{
                                                    $images = [];
                                                }
                                            @endphp
                                            <hr>
                                            <div class="row">
                                                <div class="col-3 order_product_images" onclick='getImages("{{implode(" ", $images)}}")'  data-bs-toggle="modal" data-bs-target="#carousel-modal">
                                                    <img src="{{$images[0]}}" alt="" height="144px">
                                                </div>
                                                <div class="col-1"></div>
                                                <div class="col-4 order_content">
                                                    <h4>{{translate('Order')}}</h4>
                                                    @if(!empty($products[0]->warehouse) && $products[0]->warehouse->name)
                                                        <span><b>{{$products[0]->warehouse->name}}</b></span>
                                                    @elseif(!empty($products[0]->warehouse->product) && $products[0]->warehouse->product->name)
                                                        <span><b>{{$products[0]->warehouse->product->name}}</b></span>
                                                    @endif
                                                    @if($products[0]->price)
                                                        <span>{{translate('Price')}}: <b>{{$products[0]->price}}</b> {!! !empty($products[0]->quantity)?translate('Quantity').': '."<b>".$products[0]->quantity."</b>":'' !!}</span>
                                                    @endif
                                                    @if($products[1])
                                                        <span>{{translate('Sum')}}: <b>{{$products[1]}}</b></span>
                                                    @endif
                                                    @if($discount_withouth_expire != 0)
                                                        <span>{{translate('Discount')}}: <b style="color: red">{{(int)$discount_withouth_expire}} %</b></span>
                                                    @elseif($product_discount_withouth_expire != 0)
                                                        <span>{{translate('Discount')}}: <b style="color: red">{{(int)$product_discount_withouth_expire}} %</b></span>
                                                    @endif
                                                    @if(!empty($products[0]->size))
                                                        <span>{{translate('Size')}}: <b>{{$products[0]->size->name}}</b> {{translate('Color')}}: <b>{{$products[0]->color->name}}</b></span>
                                                    @endif
                                                    <span>{{translate('Ordered')}}: <b>{{$products[0]->updated_at}}</b></span>
                                                </div>
                                                <div class="col-1 d-flex justify-content-around align-items-center">
                                                    @switch($products[0]->status)
                                                        @case(\App\Constants::ORDER_DETAIL_ORDERED):
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('New')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                        <div>
                                                            <span class="badge bg-success">{{translate('Performed')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Cancelled')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED_BY_SUPERADMIN)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Performed by superadmin')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)
                                                        <div>
                                                            <span class="badge bg-info">{{translate('Finished')}}</span>
                                                        </div>
                                                        @break
                                                    @endswitch
                                                </div>
                                                <div class="function-column col-3">
                                                    <div class="d-flex justify-content-around">
                                                        @php
                                                            if(!empty($products[0]->warehouse) && $products[0]->warehouse->name){
                                                                $product_name = $products[0]->warehouse->name??'';
                                                            }else if(!empty($products[0]->warehouse->product) && $products[0]->warehouse->product->name){
                                                                $product_name = $products[0]->warehouse->product->name??'';
                                                            }
                                                        @endphp
                                                        <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                onclick='accepting_order(
                                                                    "{{$products[0]->quantity}}",
                                                                    "{{$products[0]->warehouse->quantity - $products[0]->quantity }}",
                                                                    "{{!empty($products[0]->color)?$products[0]->color->name:''}}",
                                                                    "{{!empty($products[0]->size)?$products[0]->size->name:''}}",
                                                                    "{{$product_name}}",
                                                                    "{{isset($images[0])?$images[0]:''}}",
                                                                    "{{isset($images[1])?$images[1]:''}}",
                                                                    "{{route('perform_order_detail', $products[0]->id)}}"
                                                                    )'>
                                                            {{translate('Perform')}}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <br>
                        @endforeach
                    </div>
                    <div class="tab-pane" id="accepted_by_recepient">
                        <h4 class="mt-0 header-title">{{translate('Accepted by recipient orders list')}}</h4>
                        @php
                            $i=0
                        @endphp
                        @foreach($acceptedByRecipientOrders as $order)
                            @php
                                $i++;
                                if(!empty($order['order']->user->personalInfo)){
                                    $first_name = isset($order['order']->user->personalInfo->first_name)?$order['order']->user->personalInfo->first_name.' ':'';
                                    $last_name = isset($order['order']->user->personalInfo->last_name)?$order['order']->user->personalInfo->last_name.' ':'';
                                    $middle_name = isset($order['order']->user->personalInfo->middle_name)?$order['order']->user->personalInfo->middle_name:'';
                                    $user_name = $first_name.''.$last_name.''.$middle_name;
                                }
                            @endphp
                            <div class="accordion custom-accordion" id="custom-accordion-one">
                                <div class="card mb-0">
                                    <div class="card-header" id="headingNine">
                            <span class="m-0 position-relative" style="width: 100%">
                                <a class="custom-accordion-title text-reset d-block"
                                   data-bs-toggle="collapse" href="#collapseNine{{$i}}"
                                   aria-expanded="true" aria-controls="collapseNine">
                                    <div class="row align-items-center">
                                        <div class="col-8 d-flex flex-column justify-content-center">
                                            @if((int)$order['product_types'] < count($order['order']->orderDetail))
                                                <h4 style="line-height: 2; font-size: 16px">
                                                    {{translate('There are ')}}
                                                    <b>{{count($order['order']->orderDetail)}}</b>
                                                    {{translate('items in order. these are selling for')}}
                                                    <b>{{$order['order']->all_price}}</b>
                                                    @if($order['order']->coupon_price)
                                                        {{translate('all coupon costed')}}
                                                        <b style="color: #10C469">{{$order['order']->coupon_price}}</b>
                                                    @endif
                                                </h4>
                                                <hr style="margin:4px">
                                            @endif
                                            <h4 style="line-height: 2; font-size: 16px">
                                                @if($user_name){{$user_name}}@endif
                                                <span style="color: orange">{{translate('Ordered')}}</span>
                                                @if($order['product_types'] != 0)
                                                    <b>{{ $order['product_types'] }}</b>
                                                    {{translate('products are yours. you will sell for')}}
                                                    <b style="color: #10C469">{{$order['company_product_price']}}</b>
                                                    @if($order['order_coupon_price'] != 0)
                                                        {{translate('Your coupon is costed')}}
                                                        <b style="color: red">{{$order['order_coupon_price']}}</b>
                                                    @endif
                                                    @if($order['company_discount_price'] != 0)
                                                        {{translate('your discount is costed')}}
                                                        <b style="color: red">{{$order['company_discount_price']}}</b>
                                                    @endif
                                                @endif
                                                @if($order['order']->payment_method == \App\Constants::CASH_ON_DELIVERY)
                                                    <span class="badge bg-info">{{translate('cash on delivery')}}</span>
                                                @elseif($order['order']->payment_method == \App\Constants::BANK_CARD)
                                                    <span class="badge bg-info">{{translate('bank card')}}</span>
                                                @endif
                                            </h4>
                                            @if($order['performed_company_product_price'] != 0)
                                                <hr style="margin:4px">
                                                <h4 style="line-height: 2; font-size: 16px">
                                                    <span style="color: #10C469">{{translate('Performed')}}</span>
                                                    @if($order['performed_product_types'] != 0)
                                                        <b>{{ $order['performed_product_types'] }}</b>
                                                        {{translate('you are selling for')}}
                                                        <b style="color: #10C469">{{$order['performed_company_product_price']}}</b>
                                                        @if($order['performed_order_coupon_price'] != 0)
                                                            {{translate('your coupon is costed')}}
                                                            <b style="color: red">{{$order['performed_order_coupon_price']}}</b>
                                                        @endif
                                                        @if($order['performed_company_discount_price'] != 0)
                                                            {{translate('your discount is costed')}}
                                                            <b style="color: red">{{$order['performed_company_discount_price']}}</b>
                                                        @endif
                                                    @endif
                                                </h4>
                                            @elseif($order['order']->status == \App\Constants::CANCELLED)
                                                <hr style="margin:4px">
                                                <b style="line-height: 2; font-size: 16px; color: red">{{translate('You cancelled all products !')}}</b>
                                            @endif
                                        </div>
                                        <div class="col-4 d-flex justify-content-between">
                                                @if($order['order']->status)
                                                @switch($order['order']->status)
                                                    @case(\App\Constants::ORDERED)
                                                    <span style="line-height: 1; font-size: 16px">
                                                                <b>{{translate('ORDERED')}}</b>
                                                            </span>
                                                    <span class="badge bg-danger">
                                                                {{translate('New')}}
                                                            </span>
                                                    @break
                                                    @case(\App\Constants::PERFORMED)
                                                    <span style="line-height: 1; font-size: 16px">
                                                                <b>{{translate('PERFORMED')}}</b>
                                                            </span>
                                                    <span class="badge bg-success">
                                                                {{translate('In progress')}}
                                                            </span>
                                                    @break
                                                    @case(\App\Constants::CANCELLED)
                                                    <span style="line-height: 1; font-size: 16px">
                                                                <b>{{translate('CANCELLED')}}</b>
                                                            </span>
                                                    <span class="badge bg-danger">
                                                                {{translate('Cancelled')}}
                                                            </span>
                                                    @break
                                                    @case(\App\Constants::ACCEPTED_BY_RECIPIENT)
                                                    <span style="line-height: 1; font-size: 16px">
                                                                <b>{{translate('ACCEPTED BY RECIPIENT')}}</b>
                                                            </span>
                                                    <span class="badge bg-info">>
                                                                {{translate('Delivered')}}
                                                            </span>
                                                    @break
                                                @endswitch
                                            @endif
                                            {{--                                            @if($order['order_detail_is_ordered'] == true)--}}
                                            {{--                                                <span class="badge bg-danger">{{translate('new')}}</span>--}}
                                            {{--                                            @endif--}}
                                            <span>
                                                <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </span>
                                    </div>
                                    <div id="collapseNine{{$i}}" class="collapse fade"
                                         aria-labelledby="headingFour"
                                         data-bs-parent="#custom-accordion-one">
                                        @foreach($order['products_with_anime'] as $products_with_anime)
                                            @php
                                                $order_detail_image_front_exists = storage_path('app/public/warehouse/'.$products_with_anime[0]->image_front);
                                                if(file_exists($order_detail_image_front_exists)){
                                                    $order_detail_image_front = asset('storage/warehouse/'.$products_with_anime[0]->image_front);
                                                }else{
                                                    $order_detail_image_front = null;
                                                }
                                                $order_detail_image_back_exists = storage_path('app/public/warehouse/'.$products_with_anime[0]->image_back);
                                                if(file_exists($order_detail_image_back_exists)){
                                                    $order_detail_image_back = asset('storage/warehouse/'.$products_with_anime[0]->image_back);
                                                }else{
                                                    $order_detail_image_back = null;
                                                }
                                                if(!$order_detail_image_front && !$order_detail_image_back){
                                                    if($products_with_anime[0]->product->images){
                                                        $images_ = json_decode($products_with_anime[0]->product->images);
                                                        $images = [];
                                                        foreach ($images_ as $key => $image_){
                                                            if($key < 2){
                                                                $images[] = asset('storage/products/'.$image_);
                                                            }
                                                        }
                                                    }else{
                                                        $images = [];
                                                    }

                                                }else{
                                                    $images = [$order_detail_image_front??'no', $order_detail_image_back??'no'];
                                                }
                                                $product_discount_withouth_expire = !empty($products_with_anime[0]->product->discount_whithout_expire)?$products_with_anime[0]->product->discount_whithout_expire->percent:0;
                                            @endphp
                                            <hr>
                                            <div class="row">
                                                <div class="col-2 order_product_images" onclick='getImages("{{implode(" ", $images)}}")' data-bs-toggle="modal" data-bs-target="#carousel-modal">
                                                    <img src="{{$images[0]}}" alt="" height="144px">
                                                </div>
                                                <div class="col-2 order_product_images" onclick='getUploads("{{implode(" ", $products_with_anime[2])}}")' data-bs-toggle="modal" data-bs-target="#carousel-upload-modal">
                                                    <img src="{{$products_with_anime[2][0]}}" alt="" height="144px">
                                                </div>
                                                <div class="col-4 order_content">
                                                    <h4>{{translate('Animated order')}}</h4>
                                                    <span>{{!empty($products_with_anime[0]->product)?$products_with_anime[0]->product->name:''}}</span>
                                                    @if($product_discount_withouth_expire != 0)
                                                        <span>{{translate('Discount')}}: <b style="color:red">{{$product_discount_withouth_expire}} %</b></span>
                                                    @endif
                                                    @if($products_with_anime[1])
                                                        <span>{{translate('Price')}}: <b>{{$products_with_anime[0]->price}}</b> {!! !empty($products_with_anime[0]->quantity)?translate('Quantity').": <b>". $products_with_anime[0]->quantity."</b>":'' !!}</span>
                                                    @endif
                                                    @if($products_with_anime[1])
                                                        <span>{{translate('Sum')}}: <b>{{$products_with_anime[1]}}</b></span>
                                                    @endif
                                                    @if(!empty($products_with_anime[0]->size))
                                                        <span>{{translate('Size')}}: <b>{{$products_with_anime[0]->size->name}}</b></span>
                                                    @endif
                                                    @if(!empty($products_with_anime[0]->color))
                                                        <span>{{translate('Color')}}: <b>{{$products_with_anime[0]->color->name}}</b></span>
                                                    @endif
                                                    @if(!empty($products_with_anime[0]->updated_at))
                                                        <span>{{translate('Ordered')}}: <b>{{$products_with_anime[0]->updated_at}}</b></span>
                                                    @endif
                                                </div>
                                                <div class="col-1 d-flex justify-content-around align-items-center">
                                                    @switch($products_with_anime[0]->status)
                                                        @case(\App\Constants::ORDER_DETAIL_ORDERED)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('New')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                        <div>
                                                            <span class="badge bg-success">{{translate('Performed')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Cancelled')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED_BY_SUPERADMIN):
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Performed by superadmin')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)
                                                        <div>
                                                            <span class="badge bg-info">{{translate('Finished')}}</span>
                                                        </div>
                                                        @break
                                                    @endswitch
                                                </div>
                                                <div class="function-column col-3">
                                                    <div class="d-flex justify-content-around">

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @foreach($order['products'] as $products)
                                            @php
                                                if(!empty($products[0]->warehouse)){
                                                    $discount_withouth_expire = !empty($products[0]->warehouse->discount_withouth_expire)?$products[0]->warehouse->discount_withouth_expire->percent:0;
                                                    $product_discount_withouth_expire = !empty($products[0]->warehouse->product_discount_withouth_expire)?$products[0]->warehouse->product_discount_withouth_expire->percent:0;
                                                }else{
                                                    $discount_withouth_expire = 0;
                                                    $product_discount_withouth_expire = 0;
                                                }
                                                if(!empty($products[0]->warehouse) && $products[0]->warehouse->images){
                                                    $images_ = json_decode($products[0]->warehouse->images);
                                                    $images = [];
                                                    foreach ($images_ as $key => $image_){
                                                        $images[] = asset('storage/warehouses/'.$image_);
                                                    }
                                                }elseif(!empty($products[0]->warehouse->product) && $products[0]->warehouse->product->images){
                                                    $images_ = json_decode($products[0]->warehouse->product->images);
                                                    $images = [];
                                                    foreach ($images_ as $key => $image_){
                                                        $images[] = asset('storage/products/'.$image_);
                                                    }
                                                }else{
                                                    $images = [];
                                                }
                                            @endphp
                                            <hr>
                                            <div class="row">
                                                <div class="col-3 order_product_images" onclick='getImages("{{implode(" ", $images)}}")'  data-bs-toggle="modal" data-bs-target="#carousel-modal">
                                                    <img src="{{$images[0]}}" alt="" height="144px">
                                                </div>
                                                <div class="col-1"></div>
                                                <div class="col-4 order_content">
                                                    <h4>{{translate('Order')}}</h4>
                                                    @if(!empty($products[0]->warehouse) && $products[0]->warehouse->name)
                                                        <span><b>{{$products[0]->warehouse->name}}</b></span>
                                                    @elseif(!empty($products[0]->warehouse->product) && $products[0]->warehouse->product->name)
                                                        <span><b>{{$products[0]->warehouse->product->name}}</b></span>
                                                    @endif
                                                    @if($products[0]->price)
                                                        <span>{{translate('Price')}}: <b>{{$products[0]->price}}</b> {!! !empty($products[0]->quantity)?translate('Quantity').': '."<b>".$products[0]->quantity."</b>":'' !!}</span>
                                                    @endif
                                                    @if($products[1])
                                                        <span>{{translate('Sum')}}: <b>{{$products[1]}}</b></span>
                                                    @endif
                                                    @if($discount_withouth_expire != 0)
                                                        <span>{{translate('Discount')}}: <b style="color: red">{{(int)$discount_withouth_expire}} %</b></span>
                                                    @elseif($product_discount_withouth_expire != 0)
                                                        <span>{{translate('Discount')}}: <b style="color: red">{{(int)$product_discount_withouth_expire}} %</b></span>
                                                    @endif
                                                    @if(!empty($products[0]->size))
                                                        <span>{{translate('Size')}}: <b>{{$products[0]->size->name}}</b> {{translate('Color')}}: <b>{{$products[0]->color->name}}</b></span>
                                                    @endif
                                                    <span>{{translate('Ordered')}}: <b>{{$products[0]->updated_at}}</b></span>
                                                </div>
                                                <div class="col-1 d-flex justify-content-around align-items-center">
                                                    @switch($products[0]->status)
                                                        @case(\App\Constants::ORDER_DETAIL_ORDERED):
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('New')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                        <div>
                                                            <span class="badge bg-success">{{translate('Performed')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Cancelled')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_PERFORMED_BY_SUPERADMIN)
                                                        <div>
                                                            <span class="badge bg-danger">{{translate('Performed by superadmin')}}</span>
                                                        </div>
                                                        @break
                                                        @case(\App\Constants::ORDER_DETAIL_ACCEPTED_BY_RECIPIENT)
                                                        <div>
                                                            <span class="badge bg-info">{{translate('Finished')}}</span>
                                                        </div>
                                                        @break
                                                    @endswitch
                                                </div>
                                                <div class="function-column col-3">
                                                    <div class="d-flex justify-content-around">

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        <span class="badge bg-warning">
            <h2>{{translate('No orders')}}</h2>
        </span>
    @endif
    <script>
        let product_name = document.getElementById('product_name')
        let order_size = document.getElementById('order_size')
        let order_color = document.getElementById('order_color')
        let remaining_quantity = document.getElementById('remaining_quantity')
        let order_quantity = document.getElementById('order_quantity')
        let products_images = document.getElementById('products_images')
        let carousel_product_images = document.getElementById('carousel_product_images')
        let carousel_product_upload_images = document.getElementById('carousel_product_upload_images')
        let product_image = document.getElementById('product_image')
        let cancell_order = document.getElementById('cancell_order')
        let perform_order = document.getElementById('perform_order')
        function accepting_order(quantity, remaining_quantity_, color_name, size_name, product_name_, image, image_1, url){
            product_name.innerHTML = ""
            order_size.innerHTML = ""
            order_color.innerHTML = ""
            remaining_quantity.innerHTML = ""
            order_quantity.innerHTML = ""
            product_image.innerHTML = ""
            if(product_name_ != ""){
                product_name.innerHTML = "<h5>{{translate('Product name')}} "+product_name_+"</h5>"
            }
            if(size_name != ""){
                order_size.innerHTML = "<span>{{translate('size')}} <b>"+size_name+"</b></span>"
            }
            if(color_name != ""){
                order_color.innerHTML = "<span>{{translate('Order color')}} <b>"+color_name+"</b></span>"
            }
            if(remaining_quantity_ != ""){
                remaining_quantity.innerHTML = "<span>{{translate('Remaining in warehouse')}} <b>"+remaining_quantity_+"</b></span>"
            }
            if(quantity != ""){
                order_quantity.innerHTML = "<span>{{translate('Order quantity')}} <b>"+quantity+"</b></span>"
            }
            if(image != "" && image_1 != ""){
                product_image.innerHTML = "<img height='64px' src='"+image+"'>" +
                    "<img height='64px' src='"+image_1+"'>"
            }

            perform_order.setAttribute("action", url)
        }
        function accepting_anime_order(quantity, product_name_, size_name, color_name, images_0, images_1, url){
            product_name.innerHTML = ""
            order_size.innerHTML = ""
            order_color.innerHTML = ""
            remaining_quantity.innerHTML = ""
            order_quantity.innerHTML = ""
            product_image.innerHTML = ""
            if(product_name_ != ""){
                product_name.innerHTML = "<h5>{{translate('Product name')}} "+product_name_+"</h5>"
            }
            if(size_name != ""){
                order_size.innerHTML = "<span>{{translate('size')}} <b>"+size_name+"</b></span>"
            }
            if(color_name != ""){
                order_color.innerHTML = "<span>{{translate('Order color')}} <b>"+color_name+"</b></span>"
            }
            if(quantity != ""){
                order_quantity.innerHTML = "<span>{{translate('Order quantity')}} <b>"+quantity+"</b></span>"
            }
            if(images_0 != "" && images_1 != ""){
                product_image.innerHTML = "<img height='64px' src='"+images_0+"'>" +
                    "<img height='64px' src='"+images_1+"'>"
            }

            perform_order.setAttribute("action", url)
        }
        function cancelling_order(order_detail_id){
            cancell_order.setAttribute("action", order_detail_id)
        }
        function getImages(images) {
            let all_images = images.split(' ')
            let images_content = ''
            for(let i=0; i<all_images.length; i++){
                if(i == 0){
                    images_content = images_content +
                        `<div class="carousel-item active">
                        <img class="d-block img-fluid" src="${all_images[i]}" alt="First slide">
                    </div>`
                }else{
                    images_content = images_content +
                        `<div class="carousel-item">
                        <img class="d-block img-fluid" src="${all_images[i]}" alt="First slide">
                    </div>`
                }
            }
            carousel_product_images.innerHTML = images_content
        }
        function getUploads(images) {
            let all_uploads = images.split(' ')
            let uploads_content = ''
            for(let i=0; i<all_uploads.length; i++){
                if(i == 0){
                    uploads_content = uploads_content +
                        `<div class="carousel-item active">
                        <img class="d-block img-fluid" src="${all_uploads[i]}" alt="First slide">
                    </div>`
                }else{
                    uploads_content = uploads_content +
                        `<div class="carousel-item">
                        <img class="d-block img-fluid" src="${all_uploads[i]}" alt="First slide">
                    </div>`
                }
            }
            carousel_product_upload_images.innerHTML = uploads_content
        }
    </script>
@endsection
