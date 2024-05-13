@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <style>
        .common_statistics{
            height: 210px
        }
        .common_statistics >.card-body{
            padding: 30px;
        }
        .stuffs_menu{
            height: 150px;
        }
    </style>
    <div class="container-fluid">
        <div class="row" style="margin-bottom: 26px">
            <div class="col-xl-3 col-md-6">
                <div class="card common_statistics">
                    <div class="card-body">
                        <h4 class="header-title mt-0 mb-4">{{translate('Orders status ordered')}}</h4>
                        <div>
                            <h1>{{$ordered_orders}}</h1>
                        </div>
                        <div>
                            <span>{{translate('Active')}}: 14</span> <br>
                            <span>{{translate('Not active')}}: 24</span>
                        </div>
                    </div>
                </div>
            </div><!-- end col -->

            <div class="col-xl-3 col-md-6">
                <div class="card common_statistics">
                    <div class="card-body">
                        <h4 class="header-title mt-0 mb-4">{{translate('Orders status performed')}}</h4>
                        <div>
                            <h1>{{$performed_orders}}</h1>
                        </div>
                        <div>
                            <span>{{translate('Active')}}: 14</span> <br>
                            <span>{{translate('Not active')}}: 24</span>
                        </div>
                    </div>
                </div>
            </div><!-- end col -->


            <div class="col-xl-3 col-md-6">
                <div class="card common_statistics">
                    <div class="card-body">
                        <h4 class="header-title mt-0 mb-4">{{translate('Orders status cancelled')}}</h4>
                        <div>
                            <h1>{{$cancelled_orders}}</h1>
                        </div>
                        <div>
                            <span>{{translate('Active')}}: 14</span> <br>
                            <span>{{translate('Not active')}}: 24</span>
                        </div>
                    </div>
                </div>
            </div><!-- end col -->

            <div class="col-xl-3 col-md-6">
                <div class="card common_statistics">
                    <div class="card-body">
                        <h4 class="header-title mt-0 mb-4">{{translate('Orders status accepted')}}</h4>
                        <div>
                            <h1>{{$accepted_orders}}</h1>
                        </div>
                        <div>
                            <span>{{translate('Active')}}: 14</span> <br>
                            <span>{{translate('Not active')}}: 24</span>
                        </div>
                    </div>
                </div>

            </div><!-- end col -->
        </div>
        <!-- end row -->

        <div class="row" style="margin-bottom: 26px">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mt-0">{{translate('Orders by status')}}</h4>
                        <div class="widget-chart text-center">
                            <div id="morris-donut-example" dir="ltr" style="height: 245px;" class="morris-chart"></div>
                            <ul class="list-inline chart-detail-list mb-0">
                                <li class="list-inline-item">
                                    <h5 style="color: #FF6C37;"><i class="fa fa-circle me-1"></i>{{translate('Offers on hold')}}</h5>
                                </li>
                                <li class="list-inline-item">
                                    <h5 style="color: #10C469;"><i class="fa fa-circle me-1"></i>{{translate('Orders active')}}</h5>
                                </li>
                            </ul>
                            <ul class="list-inline chart-detail-list mb-0">
                                <li class="list-inline-item">
                                    <h5 style="color: #00ADD7;"><i class="fa fa-circle me-1"></i>{{translate('Completed orders')}}</h5>
                                </li>
                                <li class="list-inline-item">
                                    <h5 style="color: #FF0000;"><i class="fa fa-circle me-1"></i>{{translate('Cancelled orders')}}</h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!-- end col -->


            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mt-0">{{translate('Orders by status')}}</h4>
                        <div class="widget-chart text-center">
                            <div id="morris-donut-example-1" dir="ltr" style="height: 245px;" class="morris-chart"></div>
                            <ul class="list-inline chart-detail-list mb-0">
                                <li class="list-inline-item">
                                    <h5 style="color: #FF6C37;"><i class="fa fa-circle me-1"></i>{{translate('Offers on hold')}}</h5>
                                </li>
                                <li class="list-inline-item">
                                    <h5 style="color: #10C469;"><i class="fa fa-circle me-1"></i>{{translate('Orders active')}}</h5>
                                </li>
                            </ul>
                            <ul class="list-inline chart-detail-list mb-0">
                                <li class="list-inline-item">
                                    <h5 style="color: #00ADD7;"><i class="fa fa-circle me-1"></i>{{translate('Completed orders')}}</h5>
                                </li>
                                <li class="list-inline-item">
                                    <h5 style="color: #FF0000;"><i class="fa fa-circle me-1"></i>{{translate('Cancelled orders')}}</h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!-- end col -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mt-0">{{translate('Orders by status')}}</h4>
                        <div class="widget-chart text-center">
                            <div id="morris-donut-example-2" dir="ltr" style="height: 245px;" class="morris-chart"></div>
                            <ul class="list-inline chart-detail-list mb-0">
                                <li class="list-inline-item">
                                    <h5 style="color: #FF6C37;"><i class="fa fa-circle me-1"></i>{{translate('Offers on hold')}}</h5>
                                </li>
                                <li class="list-inline-item">
                                    <h5 style="color: #10C469;"><i class="fa fa-circle me-1"></i>{{translate('Orders active')}}</h5>
                                </li>
                            </ul>
                            <ul class="list-inline chart-detail-list mb-0">
                                <li class="list-inline-item">
                                    <h5 style="color: #00ADD7;"><i class="fa fa-circle me-1"></i>{{translate('Completed orders')}}</h5>
                                </li>
                                <li class="list-inline-item">
                                    <h5 style="color: #FF0000;"><i class="fa fa-circle me-1"></i>{{translate('Cancelled orders')}}</h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div>

@endsection
