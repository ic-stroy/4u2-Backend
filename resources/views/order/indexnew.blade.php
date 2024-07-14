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
        .color_text{
            width:24px;
            height: 24px;
            border-radius:50%;
            border:solid 1px green
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
        <div id="accepted-by-recipient-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-success"></i>
                            <h4 class="mt-2">{{ translate('This order accepted by user ?')}}</h4>
                        </div>
                        <form class="d-flex justify-content-center" action="" method="POST" id="accepted_by_recipient_order">
                            @csrf
                            @method('POST')
                            <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal" style="margin-right:4px"> {{ translate('No')}}</button>
                            <button type="submit" class="btn btn-success my-2"> {{ translate('Yes')}} </button>
                        </form>
                    </div>
                </div>

            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="cancell-accepted-by-recipient-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-success"></i>
                            <h4 class="mt-2">{{ translate('This order not accepted by user ?')}}</h4>
                        </div>
                        <form class="d-flex justify-content-center" action="" method="POST" id="cancell_accepted_by_recipient_order">
                            @csrf
                            @method('POST')
                            <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal" style="margin-right:4px"> {{ translate('No')}}</button>
                            <button type="submit" class="btn btn-success my-2"> {{ translate('Yes')}} </button>
                        </form>
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
                    <div class="d-none" id="carousel-upload-modal-order">
                    <span class="badge bg-warning">
                        <h2>{{translate('No orders')}}</h2>
                    </span>
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
                        </div>
                        <form class="d-flex justify-content-center" action="" method="POST" id="cancell_order">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal" style="margin-right:4px"> {{ translate('No')}}</button>
                            <button type="submit" class="btn btn-warning my-2"> {{ translate('Yes')}} </button>
                        </form>
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
                                                    @if(count($order['order']->orderDetail)>0)
                                                        <h4 style="line-height: 2; font-size: 16px">
                                                            {{translate('There are ')}}
                                                            <b>{{count($order['order']->orderDetail)}}</b>
                                                            {{translate('items in order.')}}
                                                        </h4>
                                                        <hr style="margin:4px">
                                                        <h4 style="line-height: 2; font-size: 16px">
                                                        @if($order['user_name']){{$order['user_name']}}@endif
                                                            <span style="color: orange">{{translate('Ordered')}}</span>
                                                            <b style="color: #10C469">{{$order['company_product_price']}}</b>
                                                        @if($order['order_coupon_price'] != 0)
                                                                {{translate('Your coupon is costed')}}
                                                                <b style="color: red">{{$order['order_coupon_price']}}</b>
                                                            @endif
                                                            @if($order['company_discount_price'] != 0)
                                                                {{translate('your discount is costed')}}
                                                                <b style="color: red">{{$order['company_discount_price']}}</b>
                                                            @endif
                                                            @if($order['order']->payment_method == \App\Constants::CASH)
                                                                <span class="badge bg-info">{{translate('cash on delivery')}}</span>
                                                            @elseif($order['order']->payment_method == \App\Constants::ONLINE)
                                                                <span class="badge bg-info">{{translate('bank card')}}</span>
                                                            @endif
                                                        </h4>
                                                    @endif
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
                                                            <span style="line-height: 1; font-size: 16px" class="badge bg-info">
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
                                        @foreach($order['products'] as $products)
                                            <hr>
                                            <div class="row">
                                                <div class="col-3 order_product_images" onclick='getImages("{{implode(" ", $products['images'])}}")'  data-bs-toggle="modal" data-bs-target="#carousel-modal">
                                                    <img src="{{!empty($products['images'])?$products['images'][0]:asset('icon/no_photo.jpg')}}" alt="" height="144px">
                                                </div>
                                                <div class="col-1"></div>
                                                <div class="col-4 order_content">
                                                    <h4>{{translate('Order')}}</h4>
                                                    @if($products[0]->warehouse && $products[0]->warehouse->name)
                                                        <span><b>{{$products[0]->warehouse->name}}</b></span>
                                                    @elseif($products[0]->warehouse->product && $products[0]->warehouse->product->name)
                                                        <span><b>{{$products[0]->warehouse->product->name}}</b></span>
                                                    @endif
                                                    @if($products[0]->price)
                                                        <span>{{translate('Price')}}: <b>{{$products[0]->price}}</b> {!! $products[0]->quantity?translate('Quantity').': '."<b>".$products[0]->quantity."</b>":'' !!}</span>
                                                    @endif
                                                    @if($products[1])
                                                        <span>{{translate('Sum')}}: <b>{{$products[1]}}</b></span>
                                                    @endif
                                                    @if($products['discount_withouth_expire'] != 0)
                                                        <span>{{translate('Discount')}}: <b style="color: red">{{(int)$products['discount_withouth_expire']}} %</b></span>
                                                    @endif
                                                    @if($products[0]->size)
                                                        <span>{{translate('Size')}}: <b>{{$products[0]->size->name}}</b> {{translate('Color')}}: <b>{{$products[0]->color->name}}</b></span>
                                                    @endif
                                                    <span>{{translate('Ordered')}}: <b>{{$products[0]->updated_at}}</b></span>
                                                </div>
                                                <div class="col-1 d-flex justify-content-around align-items-center">
                                                    @switch($products[0]->status)
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
                                                            if($products[0]->warehouse && $products[0]->warehouse->name){
                                                                $product_name = $products[0]->warehouse->name??'';
                                                            }else if($products[0]->warehouse->product && $products[0]->warehouse->product->name){
                                                                $product_name = $products[0]->warehouse->product->name??'';
                                                            }
                                                        @endphp
                                                        @switch($products[0]->status)
                                                            @case(\App\Constants::ORDER_DETAIL_ORDERED)
                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                    onclick='accepting_order(
                                                                        "{{$products[0]->quantity}}",
                                                                        "{{$products[0]->warehouse->quantity - $products[0]->quantity }}",
                                                                        "{{$products[0]->color?$products[0]->color->name:''}}",
                                                                        "{{$products[0]->size?$products[0]->size->name:''}}",
                                                                        "{{$product_name}}",
                                                                        "{{isset($products['images'][0])?$products['images'][0]:''}}",
                                                                        "{{isset($products['images'][1])?$products['images'][1]:''}}",
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
                                                                        "{{$products[0]->color?$products[0]->color->name:''}}",
                                                                        "{{$products[0]->size?$products[0]->size->name:''}}",
                                                                        "{{$product_name}}",
                                                                        "{{isset($products['images'][0])?$products['images'][0]:''}}",
                                                                        "{{isset($products['images'][1])?$products['images'][1]:''}}",
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
                                                            {{translate('items in order')}}
                                                        </h4>
                                                        <hr style="margin:4px">
                                                    @endif
                                                    <h4 style="line-height: 2; font-size: 16px">
                                                        @if($order['user_name']){{$order['user_name']}}@endif
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
                                                        @if($order['order']->payment_method == \App\Constants::CASH)
                                                            <span class="badge bg-info">{{translate('cash on delivery')}}</span>
                                                        @elseif($order['order']->payment_method == \App\Constants::ONLINE)
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
                                                            <span style="line-height: 1; font-size: 16px" class="badge bg-info">
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
                                        @foreach($order['products'] as $products)
                                            <hr>
                                            <div class="row">
                                                <div class="col-3 order_product_images" onclick='getImages("{{implode(" ", $products['images'])}}")'  data-bs-toggle="modal" data-bs-target="#carousel-modal">
                                                    <img src="{{!empty($products['images'])?$products['images'][0]:asset('icon/no_photo.jpg')}}" alt="" height="144px">
                                                </div>
                                                <div class="col-1"></div>
                                                <div class="col-4 order_content">
                                                    <h4>{{translate('Order')}}</h4>
                                                    @if($products[0]->warehouse && $products[0]->warehouse->name)
                                                        <span><b>{{$products[0]->warehouse->name}}</b></span>
                                                    @elseif($products[0]->warehouse->product && $products[0]->warehouse->product->name)
                                                        <span><b>{{$products[0]->warehouse->product->name}}</b></span>
                                                    @endif
                                                    @if($products[0]->price)
                                                        <span>{{translate('Price')}}: <b>{{$products[0]->price}}</b> {!! $products[0]->quantity?translate('Quantity').': '."<b>".$products[0]->quantity."</b>":'' !!}</span>
                                                    @endif
                                                    @if($products[1])
                                                        <span>{{translate('Sum')}}: <b>{{$products[1]}}</b></span>
                                                    @endif
                                                    @if($products['discount_withouth_expire'] != 0)
                                                        <span>{{translate('Discount')}}: <b style="color: red">{{(int)$products['discount_withouth_expire']}} %</b></span>
                                                    @endif
                                                    @if($products[0]->size)
                                                        <span>{{translate('Size')}}: <b>{{$products[0]->size->name}}</b> {{translate('Color')}}: <b>{{$products[0]->color->name}}</b></span>
                                                    @endif
                                                    <span>{{translate('Ordered')}}: <b>{{$products[0]->updated_at}}</b></span>
                                                </div>
                                                <div class="col-1 d-flex justify-content-around align-items-center">
                                                    @switch($products[0]->status)
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
                                                            if($products[0]->warehouse && $products[0]->warehouse->name){
                                                                $product_name = $products[0]->warehouse->name??'';
                                                            }else if($products[0]->warehouse->product && $products[0]->warehouse->product->name){
                                                                $product_name = $products[0]->warehouse->product->name??'';
                                                            }
                                                        @endphp
                                                        @switch($products[0]->status)
                                                            @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                            <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-by-recipient-modal" onclick='accepted_by_recipient("{{route('accepted_by_recipient', $order['order']->id)}}")' data-url=""><i class="fa fa-check"></i></button>
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
                                                                        "{{isset($products['images'][0])?$products['images'][0]:''}}",
                                                                        "{{isset($products['images'][1])?$products['images'][1]:''}}",
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
                                                    {{translate('items in order.')}}
                                                </h4>
                                                <hr style="margin:4px">
                                            @endif
                                            <h4 style="line-height: 2; font-size: 16px">
                                                @if($order['user_name']){{$order['user_name']}}@endif
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
                                                @if($order['order']->payment_method == \App\Constants::CASH)
                                                    <span class="badge bg-info">{{translate('cash on delivery')}}</span>
                                                @elseif($order['order']->payment_method == \App\Constants::ONLINE)
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
                                                    <span style="line-height: 1; font-size: 16px" class="badge bg-info">
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
                                        @foreach($order['products'] as $products)
                                            <hr>
                                            <div class="row">
                                                <div class="col-3 order_product_images" onclick='getImages("{{implode(" ", $products['images'])}}")'  data-bs-toggle="modal" data-bs-target="#carousel-modal">
                                                    <img src="{{!empty($products['images'])?$products['images'][0]:asset('icon/no_photo.jpg')}}" alt="" height="144px">
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
                                                    @if($products['discount_withouth_expire'] != 0)
                                                        <span>{{translate('Discount')}}: <b style="color: red">{{(int)$products['discount_withouth_expire']}} %</b></span>
                                                    @endif
                                                    @if(!empty($products[0]->size))
                                                        <span>{{translate('Size')}}: <b>{{$products[0]->size->name}}</b> {{translate('Color')}}: <b>{{$products[0]->color->name}}</b></span>
                                                    @endif
                                                    <span>{{translate('Ordered')}}: <b>{{$products[0]->updated_at}}</b></span>
                                                </div>
                                                <div class="col-1 d-flex justify-content-around align-items-center">
                                                    @switch($products[0]->status)
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
                                                                    "{{$products[0]->color?$products[0]->color->name:''}}",
                                                                    "{{$products[0]->size?$products[0]->size->name:''}}",
                                                                    "{{$product_name}}",
                                                                    "{{isset($products['images'][0])?$products['images'][0]:''}}",
                                                                    "{{isset($products['images'][1])?$products['images'][1]:''}}",
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
                                                    {{translate('items in order.')}}
                                                </h4>
                                                <hr style="margin:4px">
                                            @endif
                                            <h4 style="line-height: 2; font-size: 16px">
                                                @if($order['user_name']){{$order['user_name']}}@endif
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
                                                @if($order['order']->payment_method == \App\Constants::CASH)
                                                    <span class="badge bg-info">{{translate('cash on delivery')}}</span>
                                                @elseif($order['order']->payment_method == \App\Constants::ONLINE)
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
                                                            <b>{{translate('ACCEPTED')}}</b>
                                                        </span>
                                                    <span style="line-height: 1; font-size: 16px" class="badge bg-info">
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
                                        @foreach($order['products'] as $products)
                                            <hr>
                                            <div class="row">
                                                <div class="col-3 order_product_images" onclick='getImages("{{implode(" ", $products['images'])}}")'  data-bs-toggle="modal" data-bs-target="#carousel-modal">
                                                    <img src="{{!empty($products['images'])?$products['images'][0]:asset('icon/no_photo.jpg')}}" alt="" height="144px">
                                                </div>
                                                <div class="col-1"></div>
                                                <div class="col-4 order_content">
                                                    <h4>{{translate('Order')}}</h4>
                                                    @if($products[0]->warehouse && $products[0]->warehouse->name)
                                                        <span><b>{{$products[0]->warehouse->name}}</b></span>
                                                    @elseif($products[0]->warehouse->product && $products[0]->warehouse->product->name)
                                                        <span><b>{{$products[0]->warehouse->product->name}}</b></span>
                                                    @endif
                                                    @if($products[0]->price)
                                                        <span>{{translate('Price')}}: <b>{{$products[0]->price}}</b> {!! $products[0]->quantity?translate('Quantity').': '."<b>".$products[0]->quantity."</b>":'' !!}</span>
                                                    @endif
                                                    @if($products[1])
                                                        <span>{{translate('Sum')}}: <b>{{$products[1]}}</b></span>
                                                    @endif
                                                    @if($products['discount_withouth_expire'] != 0)
                                                        <span>{{translate('Discount')}}: <b style="color: red">{{(int)$products['discount_withouth_expire']}} %</b></span>
                                                    @endif
                                                    @if($products[0]->size)
                                                        <span>{{translate('Size')}}: <b>{{$products[0]->size->name}}</b> {{translate('Color')}}: <b>{{$products[0]->color->name}}</b></span>
                                                    @endif
                                                    <span>{{translate('Ordered')}}: <b>{{$products[0]->updated_at}}</b></span>
                                                </div>
                                                <div class="col-1 d-flex justify-content-around align-items-center">
                                                    @switch($products[0]->status)
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
                                                        <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#cancell-accepted-by-recipient-modal" onclick='cancell_accepted_by_recipient("{{route('cancell_accepted_by_recipient', $order['order']->id)}}")' data-url=""><i class="fa fa-arrow-left"></i></button>
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
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('assets/js/companyOrder.js')}}"></script>
    <script>
        let product_name_text = "{{translate('Product name')}}"
        let size_text = "{{translate('size')}}"
        let order_color_text = "{{translate('Order color')}}"
        let order_quantity_text = "{{translate('Order quantity')}}"
        let remaining_in_warehouse_text = "{{translate('remained in warehouse')}}"

        let error_status = "{{session('error')}}"
        let performed_status = "{{session('performed')}}"
        if(error_status != "" && error_status != null && error_status != undefined){
            $(document).ready(function(){
                toastr.warning(error_status)
            });
        }
        if(performed_status != "" && performed_status != null && performed_status != undefined){
            $(document).ready(function(){
                toastr.success(performed_status)
            });
        }
    </script>
@endsection
