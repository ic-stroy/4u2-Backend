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
            text-align: start;
            color:rgb(0, 0, 0, 0.7);
        }
        .color_order{
            color:rgb(0, 0, 0, 0.7);
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
        .order_product_images{
            display: flex;
            justify-content: center;
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
        .table.dataTable.nowrap th, table.dataTable.nowrap td{
            white-space: normal !important;
        }
        .custom-accordion .card{
            border:0px !important;
        }
        .table-striped{
            width:100% !important;
        }
        .card{
            margin: 0px !important;
        }
        .table.dataTable tbody td.focus, table.dataTable tbody th.focus{
            color: rgb(0, 0, 0, 0.54) !important;
        }
        .width_auto{
            width: auto !important;
        }
        .hr_no_margin{
            margin: 0px !important;
        }
        .address_modal_link{
            transition:0.4s;
            border:0px;
            border-radius: 4px;
            font-size: 20px;
            color: red;
        }
        .address_modal_link:hover{
            transform:scale(1.24);
            background-color:lightblue;
        }
    </style>
    @if(!empty($all_orders['orderedOrders']) || !empty($all_orders['performedOrders']) || !empty($all_orders['cancelledOrders']) || !empty($all_orders['acceptedByRecipientOrders']))
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
                            <div id="order_url"></div>
                        </div>
                        <div class="card-footer">
                            <form class="d-flex justify-content-between" action="" method="POST" id="perform_order_">
                                @csrf
                                @method('POST')
                                <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal"> {{ translate('No')}}</button>
                                <button type="submit" class="btn btn-success my-2" id="perform_order_button"> {{ translate('Yes')}} </button>
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
                            @if(count($all_orders['orderedOrders']) > 0)
                                <span class="badge bg-danger"> {{translate('new')}} {{count($all_orders['orderedOrders'])}}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#performed" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            {{translate('Performed')}}
                            @if(count($all_orders['performedOrders']) > 0)
                                <span class="badge bg-danger"> {{count($all_orders['performedOrders'])}}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#cancelled" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            {{translate('Cancelled')}}
                            @if(count($all_orders['cancelledOrders']) > 0)
                                <span class="badge bg-danger"> {{count($all_orders['cancelledOrders'])<101?count($all_orders['cancelledOrders']):'+101'}}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#accepted_by_recepient" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            {{translate('Accepted by recepient')}}
                            @if(count($all_orders['acceptedByRecipientOrders']) > 0)
                                <span class="badge bg-danger"> {{count($all_orders['acceptedByRecipientOrders'])<101?count($all_orders['acceptedByRecipientOrders']):'+101'}}</span>
                            @endif
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    @foreach($all_orders as $key_order => $all_order)
                        @switch($key_order)
                            @case("orderedOrders")
                            <div class="tab-pane show active" id="ordered">
                                <table id="datatable" class="table table-striped table-bordered dt-responsive">
                                    <thead>
                                    <tr>
                                        <th>
                                            <h4 class="mt-0 header-title">{{translate('Ordered orders list')}}</h4>
                                        </th>
                                    </tr>
                                    </thead>
                                    @break
                            @case("performedOrders")
                            <div class="tab-pane" id="performed">
                                <table id="selection-datatable" class="table table-striped table-bordered dt-responsive">
                                    <thead>
                                    <tr>
                                        <th>
                                            <h4 class="mt-0 header-title">{{translate('Performed orders list')}}</h4>
                                        </th>
                                    </tr>
                                    </thead>
                                    @break
                            @case("cancelledOrders")
                            <div class="tab-pane" id="cancelled">
                                <table id="key-table" class="table table-striped table-bordered dt-responsive">
                                    <thead>
                                    <tr>
                                        <th class="d-flex justify-content-between width_auto">
                                            <h4 class="mt-0 header-title">{{translate('Cancelled orders list')}}</h4>
                                            @if(count($all_orders['cancelledOrders'])>100)
                                                <a href="{{route('order.finished_all_orders')}}">{{translate('All cancelled orders')}}</a>
                                            @endif
                                        </th>
                                    </tr>
                                    </thead>
                                    @break
                            @case("acceptedByRecipientOrders")
                            <div class="tab-pane" id="accepted_by_recepient">
                                <table id="responsive-datatable" class="table table-striped table-bordered dt-responsive">
                                    <thead>
                                    <tr>
                                        <th class="d-flex justify-content-between width_auto">
                                            <h4 class="mt-0 header-title">{{translate('Accepted recepient orders list')}}</h4>
                                            @if(count($all_orders['acceptedByRecipientOrders'])>100)
                                                <a href="{{route('order.finished_all_orders')}}">{{translate('All cancelled orders')}}</a>
                                            @endif
                                        </th>
                                    </tr>
                                    </thead>
                                    @break
                                @endswitch
                                @php
                                    $i=0
                                @endphp
                                <tbody class="table_body">
                                @foreach($all_order as $order)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="accordion custom-accordion">
                                                <div class="card mb-0">
                                                    <div class="card-header" id="headingNine">
                                                        <span class="m-0 position-relative" style="width: 100%">
                                                            <span class="custom-accordion-title text-reset d-block"
                                                               data-bs-toggle="collapse" href="#collapseNine{{$i}}"
                                                               aria-expanded="true" aria-controls="collapseNine">
                                                                <div class="row align-items-center">
                                                                    <div class="col-8 d-flex flex-column justify-content-center text-start">
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
                                                                        @if($order['order'])
                                                                            <span style="font-size:12px; opacity: 0.84">{{translate('Created at')}}
                                                                                <span style="font-size:12px; opacity:0.54">{{$order['order']->created_at}}</span>
                                                                            </span>
                                                                            @if(!empty($order['order']->address))
                                                                                <form action="{{route('order.address')}}" method="POST">
                                                                                    @csrf
                                                                                    @method('POST')
                                                                                    <input type="hidden" name="latitude" value="{{$order['order']->address->latitude}}">
                                                                                    <input type="hidden" name="longitude" value="{{$order['order']->address->longitude}}">
                                                                                    <span style="font-size:12px; opacity: 0.84">{{translate('Address')}}
                                                                                        <span style="font-size:12px; opacity: 0.64">
                                                                                            @if(!empty($order['order']->address->cities))
                                                                                                @if(!empty($order['order']->address->cities->region))
                                                                                                    {{$order['order']->address->cities->region->name}}
                                                                                                @endif
                                                                                                {{$order['order']->address->cities->name}}
                                                                                            @endif
                                                                                            {{$order['order']->address->name}}
                                                                                        </span>
                                                                                        <button type="submit" class="address_modal_link"><i class="mdi mdi-map-marker-outline"></i></button>
                                                                                    </span>
                                                                                </form>
                                                                            @endif
                                                                        @endif
                                                                        @if($order['performed_company_product_price'] != 0)
                                                                            <hr>
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
                                                                            <span style="font-size:12px; opacity:0.54">{{translate('Updated at')}} {{$order['order']->updated_at}}</span>
                                                                        @elseif($order['order']->status == \App\Constants::CANCELLED)
                                                                            <hr style="margin: 4px">
                                                                            <b style="line-height: 2; font-size: 16px; color: red">{{translate('You cancelled all products !')}}</b>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-4 d-flex justify-content-between color_order">
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
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <div id="collapseNine{{$i}}" class="collapse fade"
                                                         aria-labelledby="headingFour"
                                                         data-bs-parent="#custom-accordion-one">
                                                        @foreach($order['products'] as $products)
                                                            @php
                                                                if(!empty($products[0]->warehouse_product) && $products[0]->warehouse_product->name){
                                                                    $product_name = $products[0]->warehouse_product->name??'';
                                                                }else if(!empty($products[0]->warehouse_product->product) && $products[0]->warehouse_product->product->name){
                                                                    $product_name = $products[0]->warehouse_product->product->name??'';
                                                                }
                                                            @endphp
                                                            <hr class="hr_no_margin">
                                                            <div class="row" style="margin:20px 0px">
                                                                <div class="col-3 order_product_images">
                                                                    <img onclick='getImages("{{implode(" ", $products['images'])}}")' data-bs-toggle="modal" data-bs-target="#carousel-modal" src="{{!empty($products['images'])?$products['images'][0]:asset('icon/no_photo.jpg')}}" alt="" height="144px">
                                                                </div>
                                                                <div class="col-1"></div>
                                                                <div class="col-4 order_content">
                                                                    <h4>{{translate('Order')}}</h4>
                                                                    @if(!empty($products[0]->warehouse_product) && $products[0]->warehouse_product->name)
                                                                        <span><b>{{$products[0]->warehouse_product->name}}</b></span>
                                                                    @elseif(!empty($products[0]->warehouse_product->product) && $products[0]->warehouse_product->product->name)
                                                                        <span><b>{{$products[0]->warehouse_product->product->name}}</b></span>
                                                                    @endif
                                                                    @if($products[0]->price)
                                                                        <span>{{translate('Price')}}: <b>{{$products[0]->price}}</b> {!! !empty($products[0]->quantity)?translate('Quantity').': '."<b>".$products[0]->quantity."</b>":'' !!}</span>
                                                                    @endif
                                                                    @if($products[1])
                                                                        <span>{{translate('Sum')}}: <b>{{$products[1]}}</b></span>
                                                                    @endif
                                                                    @if($products['discount_withouth_expire'] != 0)
                                                                        <span>{{translate('Discount')}}: <b style="color: red">{{(int)$products['discount_withouth_expire']}} %</b></span>
                                                                    @elseif($products['discount_withouth_expire'] != 0)
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
                                                                    @switch($key_order)
                                                                        @case("orderedOrders")
                                                                        <div class="d-flex justify-content-around">
                                                                            @switch($products[0]->status)
                                                                                @case(\App\Constants::ORDER_DETAIL_ORDERED)
                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                                    onclick='accepting_order(
                                                                                        "{{$products[0]->quantity}}",
                                                                                        "{{$products[0]->warehouse_product->count - $products[0]->quantity }}",
                                                                                        "{{!empty($products[0]->color)?$products[0]->color->name:''}}",
                                                                                        "{{!empty($products[0]->size)?$products[0]->size->name:''}}",
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
                                                                                        "{{$products[0]->warehouse_product->count - $products[0]->quantity }}",
                                                                                        "{{!empty($products[0]->color)?$products[0]->color->name:''}}",
                                                                                        "{{!empty($products[0]->size)?$products[0]->size->name:''}}",
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
                                                                        @break
                                                                        @case("performedOrders")
                                                                        <div class="d-flex justify-content-around">
                                                                            @switch($products[0]->status)
                                                                                @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                                                <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-by-recipient-modal" onclick='accepted_by_recipient("{{route('accepted_by_recipient', $order['order']->id)}}")' data-url=""><i class="fa fa-check"></i></button>
                                                                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products[0]->id)}}")' data-url="">{{translate('Cancel')}}</button>
                                                                                @break
                                                                                @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                                    onclick='accepting_order(
                                                                                        "{{$products[0]->quantity}}",
                                                                                        "{{$products[0]->warehouse_product->count - $products[0]->quantity }}",
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
                                                                        @break
                                                                        @case("cancelledOrders")
                                                                        <div class="d-flex justify-content-around">
                                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                                onclick='accepting_order(
                                                                                    "{{$products[0]->quantity}}",
                                                                                    "{{$products[0]->warehouse_product->count - $products[0]->quantity }}",
                                                                                    "{{!empty($products[0]->color)?$products[0]->color->name:''}}",
                                                                                    "{{!empty($products[0]->size)?$products[0]->size->name:''}}",
                                                                                    "{{$product_name}}",
                                                                                    "{{isset($products['images'][0])?$products['images'][0]:''}}",
                                                                                    "{{isset($products['images'][1])?$products['images'][1]:''}}",
                                                                                    "{{route('perform_order_detail', $products[0]->id)}}"
                                                                                    )'>
                                                                            {{translate('Perform')}}
                                                                            </button>
                                                                        </div>
                                                                        @break
                                                                        @case("acceptedByRecipientOrders")
                                                                        <div class="d-flex justify-content-around">
                                                                            <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#cancell-accepted-by-recipient-modal" onclick='cancell_accepted_by_recipient("{{route('cancell_accepted_by_recipient', $order['order']->id)}}")' data-url=""><i class="fa fa-arrow-left"></i></button>
                                                                        </div>
                                                                        @break
                                                                    @endswitch
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                        </tbody>
                        @switch($key_order)
                            @case("orderedOrders")
                            </div>
                        </table>
                        @break
                        @case("performedOrders")
                            </div>
                        </table>
                        @break
                        @case("cancelledOrders")
                            </div>
                        </table>
                        @break
                        @case("acceptedByRecipientOrders")
                            </div>
                        </table>
                        @break
                    @endswitch
                </div>
                @endforeach
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
        let remaining_in_warehouse_text = "{{translate('Remaining in warehouse')}}"
        let out_of_stock_text = "{{translate('Out of stock')}}"

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
    <script src="https://api-maps.yandex.ru/2.1/?apikey=ваш API-ключ&lang=ru_RU"></script>
    <script>
        function select_latitude_longitude(latitude, longitude){
            let center = [latitude, longitude]
            function init() {
                let map = new ymaps.Map('map', {
                    center: center,
                    zoom: 17
                });

                let placemark = new ymaps.Placemark(center, {}, {});

                map.controls.remove('geolocationControl'); // удаляем геолокацию
                map.controls.remove('searchControl'); // удаляем поиск
                map.controls.remove('trafficControl'); // удаляем контроль трафика
                // map.controls.remove('typeSelector'); // удаляем тип
                map.controls.remove('fullscreenControl'); // удаляем кнопку перехода в полноэкранный режим
                map.controls.remove('zoomControl'); // удаляем контрол зуммирования
                map.controls.remove('rulerControl'); // удаляем контрол правил
                // map.behaviors.disable(['scrollZoom']); // отключаем скролл карты (опционально)

                map.geoObjects.add(placemark);
            }
            ymaps.ready(init);
        }

    </script>
@endsection
