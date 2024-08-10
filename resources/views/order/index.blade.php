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
            color:#98A6AD;
        }
        .color_order{
            color:#98A6AD;
        }
        .white_text{
            color:white
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
        .order_content{
            text-align: start;
        }
        .order_number{
            font-family: Inter;
            font-weight: 700;
            font-size:13px;
            line-height:20px;
            color: black !important;
        }
        .order_created{
            font-family: Inter;
            font-weight: 500;
            font-size:13px;
            line-height: 18px;
            color: black !important;
            opacity: 0.8;
        }
        .order_created_date{
            font-family: Inter;
            font-weight: 500;
            font-size:12px;
            line-height: 18px;
            color: black !important;
            opacity: 0.6;
        }
        .order_payment{
            font-family: Inter;
            font-weight: 700;
            font-size:11px;
            line-height: 18px;
            border-radius: 6px;
            padding: 4px 8px;
        }
        .status{
            font-family: Inter;
            font-weight: 500;
            font-size:13px;
            line-height: 18px;
            color: black !important;
            margin-right: 8px;
        }
        .order_status{
            font-family: Inter;
            font-weight: 700;
            font-size:11px !important;
            line-height: 18px;
            color: black !important;
        }
        .order_hr_05{
            color: #CDCDCF;
            height: 0.5px !important;
            opacity: 0.4;
            margin: 10px 0px;
        }
        .order_content_header{
            font-family: Inter;
            opacity: 0.7;
            font-weight: 500;
            font-size:13px;
            line-height: 16px;
            text-align: start;
            margin-bottom: 6px;
            color: black !important;
        }
        .order_cost{
            font-family: Inter;
            opacity: 0.7;
            font-weight: 500;
            font-size:10px;
            line-height: 16px;
            text-align: start;
            margin-bottom: 6px;
            color: black !important;
        }
        .order_content_item{
            font-family: Inter;
            font-weight: 800;
            font-size:13px;
            line-height: 18px;
            text-align: start;
            color: black !important;
        }
        .order_product_quantity{
            font-family: Inter;
            font-weight: 500;
            font-size:13px;
            line-height: 19px;
            color: black !important;
        }
        .custom-accordion{
            border-bottom: solid 1px;
            color: #CDCDCF;
        }
        .bg_light{
            background-color: white !important;
            color: black !important;
            font-weight: 700;
            font-size: 11px;
        }
        .products-content{
            width: auto;
            overflow: auto;
            margin: 14px 0px;
            background-color: white;
            padding: 14px 0px;
            border-radius: 8px;
            color: black !important;
        }
        .btn-success{
            background-color: #0EC568 !important;
        }
        .btn-default{
            color:red !important;
            background-color: #F7F7F7;
            font-weight: 700;
            border-radius: 6px !important;
        }
        .btn-info{
            border-radius: 6px !important;
        }
        .images-content{
            display: flex;
            width: 100%;
            overflow:auto;
        }
        .images-content img{
            margin-right: 14px;
        }
        .accordion_arrow{
            font-size: 24px;
        }
        .bg_warning{
            background-color: #F8EAB9 !important;
            color: black !important;
            /*padding:8px 8px;*/
            border-radius: 6px;
            font-size: 13px;
            width: min-content;
        }
        .bg_danger{
            background-color: #FFE4E4 !important;
            color: black !important;
            /*padding:8px 8px;*/
            border-radius: 6px;
            font-size: 13px;
            width: min-content;
        }
        .bg_info{
            background-color: #CFF1FF !important;
            color: black !important;
            /*padding:8px 8px;*/
            border-radius: 6px;
            font-size: 13px;
            width: min-content;
        }
        .table_body tr td{
            outline: 1px #3A4250 !important;
        }
        .modal-content{
            width: auto !important;
            margin-top: 20%;
        }
        .bg-warning{
            background-color: #DDB732 !important;
        }
        .card-header {
            padding: 1.005rem 1.5rem 0rem 1.5rem
        }
        .user-info-button{
            text-decoration-line: underline;
            text-decoration-style: solid;
        }
        .client_data{
            border-radius: 20px;
        }
        .client-title{
            font-family: Inter;
            font-weight: 500;
            font-size:13px;
            line-height: 20px;
            color: black !important;
            opacity: 0.6;
            margin-right: 7px;
        }
        .client-info{
            padding: 8px 16px;
        }
        .client-data{
            font-family: Inter;
            font-weight: 500;
            font-size:16px;
            line-height: 20px;
            color: black !important;
        }
        .img-fluid{
            height:400px !important;
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
    @if(!empty($all_orders['orderedOrders']) || !empty($all_orders['performedOrders']) || !empty($all_orders['cancelledOrders'])
        || !empty($all_orders['deliveredOrders']) || !empty($all_orders['readyForPickup']) || !empty($all_orders['acceptedByRecipientOrders']))
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
        <div id="order-delivered-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-success"></i>
                            <h4 class="mt-2">{{ translate('Is this order ready to deliver ?')}}</h4>
                        </div>
                        <form class="d-flex justify-content-center" action="" method="POST" id="order_delivered">
                            @csrf
                            @method('POST')
                            <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal" style="margin-right:4px"> {{ translate('No')}}</button>
                            <button type="submit" class="btn btn-success my-2"> {{ translate('Yes')}} </button>
                        </form>
                    </div>
                </div>

            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="ready-for-pickup-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-success"></i>
                            <h4 class="mt-2">{{ translate('Is this order ready to pick up ?')}}</h4>
                        </div>
                        <form class="d-flex justify-content-center" action="" method="POST" id="ready_for_pick_up">
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
        <div id="cancell-order-delivered-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-success"></i>
                            <h4 class="mt-2">{{ translate('Are you going to cancell to deliver order?')}}</h4>
                        </div>
                        <form class="d-flex justify-content-center" action="" method="POST" id="cancell_order_delivered">
                            @csrf
                            @method('POST')
                            <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal" style="margin-right:4px"> {{ translate('No')}}</button>
                            <button type="submit" class="btn btn-success my-2"> {{ translate('Yes')}} </button>
                        </form>
                    </div>
                </div>

            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="delete_order_detail-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-success"></i>
                            <h4 class="mt-2">{{ translate('Are you going to delete this product from order?')}}</h4>
                        </div>
                        <form class="d-flex justify-content-center" action="" method="POST" id="delete_order_detail">
                            @csrf
                            @method('POST')
                            <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal" style="margin-right:4px"> {{ translate('No')}}</button>
                            <button type="submit" class="btn btn-success my-2"> {{ translate('Yes')}} </button>
                        </form>
                    </div>
                </div>

            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="cancell-ready-for-pickup-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-success"></i>
                            <h4 class="mt-2">{{ translate('Are you going to cancell to pick up order?')}}</h4>
                        </div>
                        <form class="d-flex justify-content-center" action="" method="POST" id="cancell_ready_for_pick_up">
                            @csrf
                            @method('POST')
                            <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal" style="margin-right:4px"> {{ translate('No')}}</button>
                            <button type="submit" class="btn btn-success my-2"> {{ translate('Yes')}} </button>
                        </form>
                    </div>
                </div>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="images-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog d-flex align-items-center">
                <div class="modal-content" style="background-color: transparent">
                    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner" id="product_image_content">

                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- /.modal -->
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
        <div id="user_info_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content client_data">
                    <div class="modal-body" style="background-color: white; border-radius: 16px; box-shadow: 2px 2px 12px">
                        <div class="d-flex justify-content-between align-items-start">
                            <h4 class="mt-2" style="color:black !important">{{ translate('Client data')}}</h4>
                            <span class="mdi mdi-close" data-bs-dismiss="modal" style="font-size: 18px"></span>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="d-flex client-info">
                                <span class="client-title">{{translate('Order number')}}:</span>
                                <span class="client-data" id="user_order_number"></span>
                            </div>
                            <div class="d-flex client-info">
                                <span class="client-title">{{translate('Full name')}}:</span>
                                <span class="client-data" id="user_full_name"></span>
                            </div>
                            <div class="d-flex client-info">
                                <span class="client-title">{{translate('Date of Birth')}}:</span>
                                <span class="client-data" id="user_birth_date"></span>
                            </div>
                            <div class="d-flex client-info">
                                <span class="client-title">{{translate('Sex')}}:</span>
                                <span class="client-data" id="user_gender"></span>
                            </div>
                            <div class="d-flex client-info">
                                <span class="client-title">{{translate('Order number')}}:</span>
                                <span class="client-data" id="user_phone_number"></span>
                            </div>
                            <div class="d-flex client-info">
                                <span class="client-title">{{translate('Email')}}:</span>
                                <span class="client-data" id="user_email"></span>
                            </div>
                            <div class="d-flex client-info">
                                <span class="client-title">{{translate('Address')}}:</span>
                                <span class="client-data" id="user_address"></span>

                            </div>
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
                        <a href="#order__delivered" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            {{translate('Delivered')}}
                            @if(count($all_orders['deliveredOrders']) > 0)
                                <span class="badge bg-danger"> {{count($all_orders['deliveredOrders'])}}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#ready_for_pick" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            {{translate('Ready for pickup')}}
                            @if(count($all_orders['readyForPickup']) > 0)
                                <span class="badge bg-danger"> {{count($all_orders['readyForPickup'])}}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#accepted_by_recepient" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            {{translate('Accepted by recepient')}}
                            @if(count($all_orders['acceptedByRecipientOrders']) > 0)
                                <span class="badge bg-danger"> {{count($all_orders['acceptedByRecipientOrders'])<26?count($all_orders['acceptedByRecipientOrders']):'+26'}}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#cancelled" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            {{translate('Cancelled')}}
                            @if(count($all_orders['cancelledOrders']) > 0)
                                <span class="badge bg-danger"> {{count($all_orders['cancelledOrders'])<26?count($all_orders['cancelledOrders']):'+26'}}</span>
                            @endif
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    @php
                        $i=0;
                    @endphp
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
                                            @if(count($all_orders['cancelledOrders'])>25)
                                                <a href="{{route('company_order.finished_all_orders')}}">{{translate('All cancelled orders')}}</a>
                                            @endif
                                        </th>
                                    </tr>
                                    </thead>
                                    @break
                            @case("deliveredOrders")
                            <div class="tab-pane" id="order__delivered">
                                <table class="table table-striped table-bordered dt-responsive">
                                    <thead>
                                    <tr>
                                        <th class="d-flex justify-content-between width_auto">
                                            <h4 class="mt-0 header-title">{{translate('Delivered orders list')}}</h4>
                                        </th>
                                    </tr>
                                    </thead>
                                    @break
                            @case("readyForPickup")
                            <div class="tab-pane" id="ready_for_pick">
                                <table class="table table-striped table-bordered dt-responsive">
                                    <thead>
                                    <tr>
                                        <th class="d-flex justify-content-between width_auto">
                                            <h4 class="mt-0 header-title">{{translate('Pickup orders list')}}</h4>
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
                                            @if(count($all_orders['acceptedByRecipientOrders'])>25)
                                                <a href="{{route('company_order.finished_all_orders')}}">{{translate('All cancelled orders')}}</a>
                                            @endif
                                        </th>
                                    </tr>
                                    </thead>
                                    @break
                                @endswitch
                                <tbody class="table_body">
                                @foreach($all_order as $order)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="accordion custom-accordion">
                                                <div class="card mb-0">
                                                    <div class="card-header" id="headingNine" style="background-color: #F7F7F7">
                                                        @if($order['order'])
                                                            <span class="m-0 position-relative" style="width: 100%">
                                                                <div class="text-reset d-block">
                                                                    <div class="row text-start d-flex align-items-center">
                                                                        <div class="col-3">
                                                                            <span class="order_number">{{translate('Order number')}} {{$order['order']->code}}</span>
                                                                        </div>
                                                                        <div class="col-5">
                                                                            <span class="order_created">{{translate('The order was created in: ')}} <span class="order_created_date">{{$order['order']->updated_at}}</span></span>
                                                                        </div>
                                                                        <div class="col-2">
                                                                            @switch($order['order']->payment_method)
                                                                                @case(\App\Constants::CASH)
                                                                                <span class="badge bg-warning order_payment">
                                                                                        {{translate('Payment upon receipt')}}
                                                                                    </span>
                                                                                @break
                                                                                @case(\App\Constants::ONLINE)
                                                                                <span class="badge bg-info order_payment">
                                                                                        {{translate('Online payment')}}
                                                                                    </span>
                                                                                @break
                                                                            @endswitch
                                                                        </div>
                                                                        <div class="col-2 text-end">
                                                                            <span class="status">{{translate('Status:')}}</span>
                                                                            @switch($order['order']->status)
                                                                                @case(\App\Constants::ORDERED)
                                                                                <span class="badge bg_light order_status">{{translate('New order')}}</span>
                                                                                @break
                                                                                @case(\App\Constants::PERFORMED)
                                                                                <span class="badge bg_warning order_status">{{translate('Performed')}}</span>
                                                                                @break
                                                                                @case(\App\Constants::CANCELLED)
                                                                                <span class="badge bg_danger order_status">{{translate('Cancelled')}}</span>
                                                                                @break
                                                                                @case(\App\Constants::ORDER_DELIVERED)
                                                                                <span class="badge bg_info order_status">{{translate('Delivered')}}</span>
                                                                                @break
                                                                                @case(\App\Constants::READY_FOR_PICKUP)
                                                                                <span class="badge bg_info order_status">{{translate('Ready for pickup')}}</span>
                                                                                @break
                                                                                @case(\App\Constants::ACCEPTED_BY_RECIPIENT)
                                                                                <span class="badge bg_success order_status">{{translate('Accepted by recipient')}}</span>
                                                                                @break
                                                                            @endswitch
                                                                        </div>
                                                                    </div>
                                                                    <hr class="order_hr_05">
                                                                    <div class="row text-start">
                                                                        <div class="col-3">
                                                                            <div class="d-flex flex-column">
                                                                                <span class="order_content_header">{{translate('Customer')}}</span>
                                                                                <a type="button" class="order_content_item user-info-button" data-bs-toggle="modal" data-bs-target="#user_info_modal" data-url="" onclick='getOrderData(
                                                                                    "{{$order['order']->code??''}}",
                                                                                    "{{$order['user_info']['user_name']}}",
                                                                                    "{{$order['user_info']['birth_date']??''}}",
                                                                                    "{{$order['user_info']['gender']??''}}",
                                                                                    "{{$order['user_info']['phone_number']}}",
                                                                                    "{{$order['user_info']['email']}}",
                                                                                    "{{$order['address']['name']??''}}",
                                                                                    )'>{{$order['user_name']}}</a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-3">
                                                                            <div class="d-flex flex-column">
                                                                                <span class="order_content_header">{{translate('Address of the recipient:')}}</span>
                                                                                @if(!empty($order['address']))
                                                                                    <form action="{{route('order.address')}}" method="POST">
                                                                                        @csrf
                                                                                        @method('POST')
                                                                                        <input type="hidden" name="latitude" value="{{$order['order']->address->latitude??''}}">
                                                                                        <input type="hidden" name="longitude" value="{{$order['order']->address->longitude??''}}">
                                                                                        <span style="font-size:12px; opacity: 0.84; color: grey">{{translate('Address')}}
                                                                                            <span class="order_content_item">
                                                                                                @if($order['address']['name'])
                                                                                                    @if(strlen($order['address']['name'])>44)
                                                                                                        {{substr($order['address']['name'], 0, 44)}} ...
                                                                                                    @else
                                                                                                        {{$order['address']['name']}}
                                                                                                    @endif
                                                                                                @endif
                                                                                            </span>
                                                                                            <button type="submit" class="address_modal_link"><i class="mdi mdi-map-marker-outline"></i></button>
                                                                                        </span>
                                                                                    </form>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        @if($order['performed_company_product_price'] > 0)
                                                                            @if($order['performed_product_types'] > 0)
                                                                                <div class="col-2">
                                                                                    <div class="d-flex flex-column">
                                                                                        <span class="order_content_header">{{translate('Order price:')}}</span>
                                                                                        <span class="order_content_item">
                                                                                            {{$order['performed_company_product_price'] + $order['performed_order_coupon_price'] + $order['performed_company_discount_price']}}
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                                @if($order['performed_company_discount_price']>0)
                                                                                    <div class="col-2">
                                                                                        <div class="d-flex flex-column">
                                                                                            <span class="order_content_header">{{translate('Discount:')}}</span>
                                                                                            <span class="order_content_item">
                                                                                                {{$order['performed_company_discount_price']}}
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                                @if($order['performed_order_coupon_price'] > 0)
                                                                                    <span class="order_content_item">{{translate('Promo code:')}}
                                                                                        @if($order['performed_order_coupon_price'] > 0)
                                                                                            <span class="order_content_header">{{$order['performed_order_coupon_price']}}</span>
                                                                                        @endif
                                                                                    </span>
                                                                                @endif
                                                                                <div class="col-2">
                                                                                    <div class="d-flex flex-column">
                                                                                        <span class="order_content_header">{{translate('Total amount:')}}</span>
                                                                                        <span class="order_content_item">{{$order['performed_company_product_price']}}</span>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @elseif($order['company_product_price']>0)
                                                                            <div class="col-2">
                                                                                <div class="d-flex flex-column">
                                                                                    <span class="order_content_header">{{translate('Order price:')}}</span>
                                                                                    <span class="order_content_item">{{$order['company_product_price'] + $order['order_coupon_price'] + $order['company_discount_price']}}</span>
                                                                                </div>
                                                                            </div>
                                                                            @if($order['company_discount_price']>0)
                                                                                <div class="col-2">
                                                                                    <div class="d-flex flex-column">
                                                                                        <span class="order_content_header">{{translate('Discount:')}}</span>
                                                                                        <span class="order_content_item">
                                                                                            {{$order['company_discount_price']}}
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                            @if($order['order_coupon_price'] > 0)
                                                                                <span class="order_content_item">{{translate('Promo code:')}}
                                                                                    <span class="order_content_header">{{$order['order_coupon_price']}}</span>
                                                                                </span>
                                                                            @endif
                                                                            <div class="col-2">
                                                                                <div class="d-flex flex-column">
                                                                                    <span class="order_content_header">{{translate('Total amount:')}}</span>
                                                                                    <span class="order_content_item">{{$order['company_product_price']}}</span>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <hr class="order_hr_05">
                                                                    <div id="collapseNine{{$i}}" class="collapse fade product_card_"
                                                                         aria-labelledby="headingFour"
                                                                         data-bs-parent="#custom-accordion-one">
                                                                        @foreach($order['products'] as $products)
                                                                            @php
                                                                                $product_name = $products[0]->name??'';
                                                                                $product_costs = (int)$products[0]->quantity*(int)$products[0]->price;
                                                                            @endphp
                                                                            <div class="row products-content">
                                                                                <div class="col-7 d-flex align-items-center">
                                                                                    <div class="images-content">
                                                                                        @foreach($products['images'] as $product_images)
                                                                                            <img onclick="showImage('{{$product_images}}')" data-bs-toggle="modal" data-bs-target="#images-modal" src="{{$product_images?$product_images:asset('icon/no_photo.jpg')}}" alt="" height="94px">
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-3">
                                                                                    <div class="d-flex flex-column text-start" style="width: 100%">
                                                                                        <span class="order_number">{{$product_name??''}}</span>
                                                                                        @if(!empty($products[0]->size))
                                                                                            <span class="order_content_item">{{translate('Size:')}} <span class="order_content_header">{{translate($products[0]->size->name)}}</span></span>
                                                                                        @endif
                                                                                        @if(!empty($products[0]->color))
                                                                                            <span class="order_content_item">{{translate('Color:')}} <span class="order_content_header">{{translate($products[0]->color->name)}}</span></span>
                                                                                        @endif
                                                                                        <span class="order_content_item">{{translate('Quantity:')}} <span class="order_content_header">{{(int)$products[0]->quantity}}</span></span>
                                                                                        @if((int)$products[0]->discount_price > 0)
                                                                                            @if($products['discount_withouth_expire'] > 0)
                                                                                                <span class="order_content_item">{{translate('Discount:')}} <span class="order_content_header">{{(int)$products['discount_withouth_expire']}} %</span></span>
                                                                                            @endif
                                                                                        @endif
                                                                                        <span class="order_content_item">{{translate('Price:')}} <span class="order_content_header">{{$products[1]}}</span> @if($products[1]<$product_costs)<del class="order_cost">{{$product_costs}}</del>@endif</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-2 d-flex align-items-center">
                                                                                    <div class="d-flex flex-column justify-content-around" style="height:80px">
                                                                                        @switch($key_order)
                                                                                            @case("orderedOrders")
                                                                                            @switch($products[0]->status)
                                                                                                @case(\App\Constants::ORDER_DETAIL_ORDERED)
                                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                                                        onclick='accepting_order(
                                                                                                            "{{(int)$products[0]->quantity}}",
                                                                                                            "{{(int)$products[0]->warehouse_product->count + (int)$products[0]->quantity  }}",
                                                                                                            "{{!empty($products[0]->color)?$products[0]->color->name:''}}",
                                                                                                            "{{!empty($products[0]->size)?$products[0]->size->name:''}}",
                                                                                                            "{{$product_name}}",
                                                                                                            "{{isset($products['images'][0])?$products['images'][0]:''}}",
                                                                                                            "{{isset($products['images'][1])?$products['images'][1]:''}}",
                                                                                                            "{{route('perform_order_detail', $products[0]->id)}}"
                                                                                                            )'>
                                                                                                        {{translate('Accept')}}
                                                                                                    </button>
                                                                                                <button type="button" class="btn btn-default delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products[0]->id)}}")' data-url="">{{translate('Cancell')}}</button>
                                                                                                @break
                                                                                                @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                                                                <div class="text-end">
                                                                                                    <span class="badge bg_warning">{{translate('Performed')}}</span>
                                                                                                </div>
                                                                                                <button type="button" class="btn btn-default delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products[0]->id)}}")' data-url="">{{translate('Cancell')}}</button>
                                                                                                @break
                                                                                                @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                                                                <div class="text-end">
                                                                                                    <span class="badge bg_danger">{{translate('Cancelled')}}</span>
                                                                                                </div>
                                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                                                        onclick='accepting_order(
                                                                                                            "{{(int)$products[0]->quantity}}",
                                                                                                            "{{(int)$products[0]->warehouse_product->count}}",
                                                                                                            "{{!empty($products[0]->color)?$products[0]->color->name:''}}",
                                                                                                            "{{!empty($products[0]->size)?$products[0]->size->name:''}}",
                                                                                                            "{{$product_name}}",
                                                                                                            "{{isset($products['images'][0])?$products['images'][0]:''}}",
                                                                                                            "{{isset($products['images'][1])?$products['images'][1]:''}}",
                                                                                                            "{{route('perform_order_detail', $products[0]->id)}}"
                                                                                                            )'>
                                                                                                    {{translate('Accept')}}
                                                                                                </button>
                                                                                                @break
                                                                                            @endswitch
                                                                                            @break
                                                                                            @case("performedOrders")
                                                                                            @switch($products[0]->status)
                                                                                                @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                                                                @if(!empty($order['address']))
                                                                                                    @if($order['address']['status'] == 'deliver')
                                                                                                        <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#order-delivered-modal" onclick='order_delivered("{{route('order_delivered', $order['order']->id)}}")' data-url="">{{translate('Delivered')}}</button>
                                                                                                    @elseif($order['address']['status'] == 'pick_up')
                                                                                                        <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#ready-for-pickup-modal" onclick='ready_for_pick_up("{{route('ready_for_pickup', $order['order']->id)}}")' data-url="">{{translate('Ready for pickup')}}</button>
                                                                                                    @endif
                                                                                                @endif
                                                                                                <button type="button" class="btn btn-default delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-order-alert-modal" onclick='cancelling_order("{{route('cancell_order_detail', $products[0]->id)}}")' data-url="">{{translate('Cancell')}}</button>
                                                                                                @break
                                                                                                @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                                                                <div class="text-end">
                                                                                                    <span class="badge bg_danger">{{translate('Cancelled')}}</span>
                                                                                                </div>
                                                                                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                                                        onclick='accepting_order(
                                                                                                            "{{(int)$products[0]->quantity}}",
                                                                                                            "{{(int)$products[0]->warehouse_product->count}}",
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
                                                                                            @break
                                                                                            @case("cancelledOrders")
                                                                                            <div class="text-end">
                                                                                                <span class="badge bg_danger">{{translate('Cancelled')}}</span>
                                                                                            </div>
                                                                                            <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""
                                                                                                    onclick='accepting_order(
                                                                                                        "{{(int)$products[0]->quantity}}",
                                                                                                        "{{(int)$products[0]->warehouse_product->count}}",
                                                                                                        "{{!empty($products[0]->color)?$products[0]->color->name:''}}",
                                                                                                        "{{!empty($products[0]->size)?$products[0]->size->name:''}}",
                                                                                                        "{{$product_name}}",
                                                                                                        "{{isset($products['images'][0])?$products['images'][0]:''}}",
                                                                                                        "{{isset($products['images'][1])?$products['images'][1]:''}}",
                                                                                                        "{{route('perform_order_detail', $products[0]->id)}}"
                                                                                                        )'>
                                                                                                    {{translate('Accept')}}
                                                                                                </button>
                                                                                            @break
                                                                                            @case("deliveredOrders")
                                                                                            @switch($products[0]->status)
                                                                                                @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                                                                <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-by-recipient-modal" onclick='accepted_by_recipient("{{route('accepted_by_recipient', $order['order']->id)}}")' data-url="">{{translate('Accepted')}}</button>
                                                                                                <button type="button" class="btn btn-default delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#cancell-order-delivered-modal" onclick='cancell_order_delivered("{{route('cancell_order_delivered', $order['order']->id)}}")' data-url="">{{translate('Cancell')}}</button>
                                                                                                @break
                                                                                                @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                                                                <div class="text-end">
                                                                                                           <span class="badge bg_danger">{{translate('Cancelled')}}</span>
                                                                                                        </div>
                                                                                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#delete_order_detail-modal" onclick='delete_order_detail("{{route('delete_order_detail', $products[0]->id)}}")' data-url="">{{translate('Delete')}}</button>
                                                                                                @break
                                                                                            @endswitch
                                                                                            @break
                                                                                            @case("readyForPickup")
                                                                                            @switch($products[0]->status)
                                                                                                @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                                                                <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#accepted-by-recipient-modal" onclick='accepted_by_recipient("{{route('accepted_by_recipient', $order['order']->id)}}")' data-url="">{{translate('Accepted')}}</button>
                                                                                                <button type="button" class="btn btn-default delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#cancell-ready-for-pickup-modal" onclick='cancell_ready_for_pick_up("{{route('cancell_ready_for_pick_up', $order['order']->id)}}")' data-url="">{{translate('Cancell')}}</button>
                                                                                                @break
                                                                                                @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                                                                <div class="text-end">
                                                                                                       <span class="badge bg_danger">{{translate('Cancelled')}}</span>
                                                                                                    </div>
                                                                                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#delete_order_detail-modal" onclick='delete_order_detail("{{route('delete_order_detail', $products[0]->id)}}")' data-url="">{{translate('Delete')}}</button>
                                                                                                @break
                                                                                            @endswitch
                                                                                            @break
                                                                                            @case("acceptedByRecipientOrders")
                                                                                            @switch($products[0]->status)
                                                                                                @case(\App\Constants::ORDER_DETAIL_PERFORMED)
                                                                                                <div class="text-end">
                                                                                                   <span class="badge bg_success">{{translate('Accepted by recepient')}}</span>
                                                                                                </div>
                                                                                                <div class="d-flex justify-content-around">
                                                                                                   @if(!empty($order['address']))
                                                                                                        @if($order['address']['status'] == 'deliver')
                                                                                                            <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#cancell-accepted-by-recipient-modal" onclick='cancell_accepted_by_recipient("{{route('cancell_accepted_by_recipient', $order['order']->id)}}")' data-url="">{{translate('Delivered')}}</button>
                                                                                                        @elseif($order['address']['status'] == 'pick_up')
                                                                                                            <button type="button" class="btn btn-info btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#cancell-accepted-by-recipient-modal" onclick='cancell_accepted_by_recipient("{{route('cancell_accepted_by_recipient', $order['order']->id)}}")' data-url="">{{translate('Ready for pickup')}}</button>
                                                                                                        @endif
                                                                                                    @endif
                                                                                                </div>
                                                                                                @break
                                                                                                @case(\App\Constants::ORDER_DETAIL_CANCELLED)
                                                                                                <div class="text-end">
                                                                                                           <span class="badge bg_danger">{{translate('Cancelled')}}</span>
                                                                                                        </div>
                                                                                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#delete_order_detail-modal" onclick='delete_order_detail("{{route('delete_order_detail', $products[0]->id)}}")' data-url="">{{translate('Delete')}}</button>
                                                                                                @break
                                                                                                @break
                                                                                            @endswitch
                                                                                            @break
                                                                                        @endswitch
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <div class="row text-start">
                                                                        <a class="d-flex justify-content-between align-items-center
                                                                        custom-accordion-title text-reset d-block pb-2"
                                                                           data-bs-toggle="collapse" href="#collapseNine{{$i}}"
                                                                           aria-expanded="true" aria-controls="collapseNine">
                                                                            <span class="order_product_quantity">{{$order['products_quantity']}} {{translate('products')}}</span>
                                                                            <span>
                                                                                <i class="mdi mdi-chevron-down accordion_arrow"></i>
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
    let remaining_in_warehouse_text = "{{translate('remained in warehouse')}}"
    let out_of_stock_text = "{{translate('Out of stock')}}"

    let error = "{{session('error')}}"
    if(error != "" && error != null && error != undefined){
        $(document).ready(function(){
            toastr.warning(error)
        });
    }
</script>
@endsection
