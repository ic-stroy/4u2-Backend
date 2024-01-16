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
                        <div class="dropdown float-end">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Another action</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Something else</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Separated link</a>
                            </div>
                        </div>

                        <h4 class="header-title mt-0 mb-4">{{__('Offers on hold')}}</h4>
                        <div class="widget-chart-1">
                            <div class="widget-chart-box-1 float-start" dir="ltr">
                                <input data-plugin="knob" data-width="70" data-height="70" data-fgColor="#FF6C37"
                                       data-bgColor="#F9B9B9" value="54"
                                       data-skin="tron" data-angleOffset="180" data-readOnly=true
                                       data-thickness=".15"/>
                            </div>
                            <div class="widget-detail-1 text-end">
                                <h2 class="fw-normal pt-2 mb-1"> 4 </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end col -->

            <div class="col-xl-3 col-md-6">
                <div class="card common_statistics">
                    <div class="card-body">
                        <div class="dropdown float-end">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Another action</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Something else</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Separated link</a>
                            </div>
                        </div>

                        <h4 class="header-title mt-0 mb-3">{{__('Orders active')}}</h4>
                        <div class="widget-box-2">
                            <div class="widget-detail-2 text-end">
                                <span class="badge bg-success rounded-pill float-start mt-3">14% <i class="mdi mdi-trending-up"></i> </span>
                                <h2 class="fw-normal mb-1"> 2 </h2>
                            </div>
                            <div class="progress progress-bar-alt-success progress-sm">
                                <div class="progress-bar bg-success" role="progressbar"
                                     aria-valuenow="24" aria-valuemin="0" aria-valuemax="100"
                                     style="width: 34%">
                                    <span class="visually-hidden">34% Complete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end col -->


            <div class="col-xl-3 col-md-6">
                <div class="card common_statistics">
                    <div class="card-body">
                        <div class="dropdown float-end">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Another action</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Something else</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Separated link</a>
                            </div>
                        </div>

                        <h4 class="header-title mt-0 mb-4">{{__('Completed orders')}}</h4>

                        <div class="widget-chart-1">
                            <div class="widget-chart-box-1 float-start" dir="ltr">
                                <input data-plugin="knob" data-width="70" data-height="70" data-fgColor="#00ADD7"
                                       data-bgColor="#85C9E8" value="23"
                                       data-skin="tron" data-angleOffset="180" data-readOnly=true
                                       data-thickness=".15"/>
                            </div>
                            <div class="widget-detail-1 text-end">
                                <h2 class="fw-normal pt-2 mb-1"> 1 </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end col -->

            <div class="col-xl-3 col-md-6">
                <div class="card common_statistics">
                    <div class="card-body">
                        <div class="dropdown float-end">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Another action</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Something else</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Separated link</a>
                            </div>
                        </div>

                        <h4 class="header-title mt-0 mb-3">{{__('Cancelled orders')}}</h4>

                        <div class="widget-box-2">
                            <div class="widget-detail-2 text-end">
                                <span class="badge rounded-pill float-start mt-3" style="background-color: #FF0000">15 % <i class="mdi mdi-trending-up"></i> </span>
                                <h2 class="fw-normal mb-1"> 4 </h2>
                            </div>
                            <div class="progress progress-sm" style="background-color: #FF7F7F;">
                                <div class="progress-bar" role="progressbar"
                                     aria-valuenow="28%" aria-valuemin="0" aria-valuemax="100"
                                     style="width: 52%; background-color: #FF0000;">
                                    <span class="visually-hidden">48% Complete</span>
                                </div>
                            </div>
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
                        <div class="dropdown float-end">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Another action</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Something else</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Separated link</a>
                            </div>
                        </div>

                        <h4 class="header-title mt-0">{{__('Orders by status')}}</h4>
                        <div class="widget-chart text-center">
                            <div id="morris-donut-example" dir="ltr" style="height: 245px;" class="morris-chart"></div>
                            <ul class="list-inline chart-detail-list mb-0">
                                <li class="list-inline-item">
                                    <h5 style="color: #FF6C37;"><i class="fa fa-circle me-1"></i>{{__('Offers on hold')}}</h5>
                                </li>
                                <li class="list-inline-item">
                                    <h5 style="color: #10C469;"><i class="fa fa-circle me-1"></i>{{__('Orders active')}}</h5>
                                </li>
                            </ul>
                            <ul class="list-inline chart-detail-list mb-0">
                                <li class="list-inline-item">
                                    <h5 style="color: #00ADD7;"><i class="fa fa-circle me-1"></i>{{__('Completed orders')}}</h5>
                                </li>
                                <li class="list-inline-item">
                                    <h5 style="color: #FF0000;"><i class="fa fa-circle me-1"></i>{{__('Cancelled orders')}}</h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!-- end col -->


            <div class="col-xl-8">
                <div class="card" style="height: 100%;">
                    <div class="card-body" style="display: flex; flex-direction: column; justify-content: center">
                        <div class="dropdown" style="display: flex; justify-content: end">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Another action</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Something else</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Separated link</a>
                            </div>
                        </div>
                        <h4 class="header-title mt-0">{{__('Orders by status')}}</h4>
                        <div id="morris-line-example" dir="ltr" style="height: 280px;" class="morris-chart"></div>
                    </div>
                </div>
            </div><!-- end col -->
        </div>
        <!-- end row -->


    {{--        <div class="row" style="margin-bottom: 26px">--}}
    {{--            <div class="col-xl-3 col-md-6">--}}
    {{--                <div class="card stuffs_menu">--}}
    {{--                    <div class="card-body widget-user">--}}
    {{--                        <div class="d-flex align-items-center">--}}
    {{--                            <div class="flex-shrink-0 avatar-lg me-3">--}}
    {{--                                <img src="assets/images/user/user-3.jpg" class="img-fluid rounded-circle" alt="user">--}}
    {{--                            </div>--}}
    {{--                            <div class="flex-grow-1 overflow-hidden">--}}
    {{--                                <h5 class="mt-0 mb-1">Chadengle</h5>--}}
    {{--                                <p class="text-muted mb-2 font-13 text-truncate">coderthemes@gmail.com</p>--}}
    {{--                                <small class="text-warning"><b>Admin</b></small>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}

    {{--            </div><!-- end col -->--}}

    {{--            <div class="col-xl-3 col-md-6">--}}
    {{--                <div class="card stuffs_menu">--}}
    {{--                    <div class="card-body widget-user">--}}
    {{--                        <div class="d-flex align-items-center">--}}
    {{--                            <div class="flex-shrink-0 avatar-lg me-3">--}}
    {{--                                <img src="assets/images/user/user-2.jpg" class="img-fluid rounded-circle" alt="user">--}}
    {{--                            </div>--}}
    {{--                            <div class="flex-grow-1 overflow-hidden">--}}
    {{--                                <h5 class="mt-0 mb-1"> Michael Zenaty</h5>--}}
    {{--                                <p class="text-muted mb-2 font-13 text-truncate">coderthemes@gmail.com</p>--}}
    {{--                                <small class="text-pink"><b>Support Lead</b></small>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}

    {{--            </div><!-- end col -->--}}

    {{--            <div class="col-xl-3 col-md-6">--}}
    {{--                <div class="card stuffs_menu">--}}
    {{--                    <div class="card-body widget-user">--}}
    {{--                        <div class="d-flex align-items-center">--}}
    {{--                            <div class="flex-shrink-0 avatar-lg me-3">--}}
    {{--                                <img src="assets/images/user/user-1.jpg" class="img-fluid rounded-circle" alt="user">--}}
    {{--                            </div>--}}
    {{--                            <div class="flex-grow-1 overflow-hidden">--}}
    {{--                                <h5 class="mt-0 mb-1">Stillnotdavid</h5>--}}
    {{--                                <p class="text-muted mb-2 font-13 text-truncate">coderthemes@gmail.com</p>--}}
    {{--                                <small class="text-success"><b>Designer</b></small>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}

    {{--            </div><!-- end col -->--}}

    {{--            <div class="col-xl-3 col-md-6">--}}
    {{--                <div class="card stuffs_menu">--}}
    {{--                    <div class="card-body widget-user">--}}
    {{--                        <div class="d-flex align-items-center">--}}
    {{--                            <div class="flex-shrink-0 avatar-lg me-3">--}}
    {{--                                <img src="assets/images/user/user-10.jpg" class="img-fluid rounded-circle" alt="user">--}}
    {{--                            </div>--}}
    {{--                            <div class="flex-grow-1 overflow-hidden">--}}
    {{--                                <h5 class="mt-0 mb-1">Tomaslau</h5>--}}
    {{--                                <p class="text-muted mb-2 font-13 text-truncate">coderthemes@gmail.com</p>--}}
    {{--                                <small class="text-info"><b>Developer</b></small>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}

    {{--            </div><!-- end col -->--}}

    {{--        </div>--}}
    <!-- end row -->


    {{--        <div class="row" style="margin-bottom: 26px">--}}
    {{--            <div class="col-xl-4">--}}
    {{--                <div class="card">--}}
    {{--                    <div class="card-body">--}}
    {{--                        <div class="dropdown float-end">--}}
    {{--                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">--}}
    {{--                                <i class="mdi mdi-dots-vertical"></i>--}}
    {{--                            </a>--}}
    {{--                            <div class="dropdown-menu dropdown-menu-end">--}}
    {{--                                <!-- item-->--}}
    {{--                                <a href="javascript:void(0);" class="dropdown-item">Action</a>--}}
    {{--                                <!-- item-->--}}
    {{--                                <a href="javascript:void(0);" class="dropdown-item">Another action</a>--}}
    {{--                                <!-- item-->--}}
    {{--                                <a href="javascript:void(0);" class="dropdown-item">Something else</a>--}}
    {{--                                <!-- item-->--}}
    {{--                                <a href="javascript:void(0);" class="dropdown-item">Separated link</a>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}

    {{--                        <h4 class="header-title mb-3">Inbox</h4>--}}

    {{--                        <div class="inbox-widget">--}}

    {{--                            <div class="inbox-item">--}}
    {{--                                <a href="#">--}}
    {{--                                    <div class="inbox-item-img"><img src="assets/images/user/user-1.jpg" class="rounded-circle" alt=""></div>--}}
    {{--                                    <h5 class="inbox-item-author mt-0 mb-1">Chadengle</h5>--}}
    {{--                                    <p class="inbox-item-text">Hey! there I'm available...</p>--}}
    {{--                                    <p class="inbox-item-date">13:40 PM</p>--}}
    {{--                                </a>--}}
    {{--                            </div>--}}

    {{--                            <div class="inbox-item">--}}
    {{--                                <a href="#">--}}
    {{--                                    <div class="inbox-item-img"><img src="assets/images/user/user-2.jpg" class="rounded-circle" alt=""></div>--}}
    {{--                                    <h5 class="inbox-item-author mt-0 mb-1">Tomaslau</h5>--}}
    {{--                                    <p class="inbox-item-text">I've finished it! See you so...</p>--}}
    {{--                                    <p class="inbox-item-date">13:34 PM</p>--}}
    {{--                                </a>--}}
    {{--                            </div>--}}

    {{--                            <div class="inbox-item">--}}
    {{--                                <a href="#">--}}
    {{--                                    <div class="inbox-item-img"><img src="assets/images/user/user-3.jpg" class="rounded-circle" alt=""></div>--}}
    {{--                                    <h5 class="inbox-item-author mt-0 mb-1">Stillnotdavid</h5>--}}
    {{--                                    <p class="inbox-item-text">This theme is awesome!</p>--}}
    {{--                                    <p class="inbox-item-date">13:17 PM</p>--}}
    {{--                                </a>--}}
    {{--                            </div>--}}

    {{--                            <div class="inbox-item">--}}
    {{--                                <a href="#">--}}
    {{--                                    <div class="inbox-item-img"><img src="assets/images/user/user-4.jpg" class="rounded-circle" alt=""></div>--}}
    {{--                                    <h5 class="inbox-item-author mt-0 mb-1">Kurafire</h5>--}}
    {{--                                    <p class="inbox-item-text">Nice to meet you</p>--}}
    {{--                                    <p class="inbox-item-date">12:20 PM</p>--}}
    {{--                                </a>--}}
    {{--                            </div>--}}

    {{--                            <div class="inbox-item">--}}
    {{--                                <a href="#">--}}
    {{--                                    <div class="inbox-item-img"><img src="assets/images/user/user-5.jpg" class="rounded-circle" alt=""></div>--}}
    {{--                                    <h5 class="inbox-item-author mt-0 mb-1">Shahedk</h5>--}}
    {{--                                    <p class="inbox-item-text">Hey! there I'm available...</p>--}}
    {{--                                    <p class="inbox-item-date">10:15 AM</p>--}}
    {{--                                </a>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}

    {{--            </div><!-- end col -->--}}

    {{--            <div class="col-xl-8">--}}
    {{--                <div class="card">--}}
    {{--                    <div class="card-body">--}}
    {{--                        <div class="dropdown float-end">--}}
    {{--                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">--}}
    {{--                                <i class="mdi mdi-dots-vertical"></i>--}}
    {{--                            </a>--}}
    {{--                            <div class="dropdown-menu dropdown-menu-end">--}}
    {{--                                <!-- item-->--}}
    {{--                                <a href="javascript:void(0);" class="dropdown-item">Action</a>--}}
    {{--                                <!-- item-->--}}
    {{--                                <a href="javascript:void(0);" class="dropdown-item">Another action</a>--}}
    {{--                                <!-- item-->--}}
    {{--                                <a href="javascript:void(0);" class="dropdown-item">Something else</a>--}}
    {{--                                <!-- item-->--}}
    {{--                                <a href="javascript:void(0);" class="dropdown-item">Separated link</a>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}

    {{--                        <h4 class="header-title mt-0 mb-3">Latest Projects</h4>--}}

    {{--                        <div class="table-responsive">--}}
    {{--                            <table class="table table-hover mb-0">--}}
    {{--                                <thead>--}}
    {{--                                <tr>--}}
    {{--                                    <th>#</th>--}}
    {{--                                    <th>Project Name</th>--}}
    {{--                                    <th>Start Date</th>--}}
    {{--                                    <th>Due Date</th>--}}
    {{--                                    <th>Status</th>--}}
    {{--                                    <th>Assign</th>--}}
    {{--                                </tr>--}}
    {{--                                </thead>--}}
    {{--                                <tbody>--}}
    {{--                                <tr>--}}
    {{--                                    <td>1</td>--}}
    {{--                                    <td>Adminto Admin v1</td>--}}
    {{--                                    <td>01/01/2017</td>--}}
    {{--                                    <td>26/04/2017</td>--}}
    {{--                                    <td><span class="badge bg-danger">Released</span></td>--}}
    {{--                                    <td>Coderthemes</td>--}}
    {{--                                </tr>--}}
    {{--                                <tr>--}}
    {{--                                    <td>2</td>--}}
    {{--                                    <td>Adminto Frontend v1</td>--}}
    {{--                                    <td>01/01/2017</td>--}}
    {{--                                    <td>26/04/2017</td>--}}
    {{--                                    <td><span class="badge bg-success">Released</span></td>--}}
    {{--                                    <td>Adminto admin</td>--}}
    {{--                                </tr>--}}
    {{--                                <tr>--}}
    {{--                                    <td>3</td>--}}
    {{--                                    <td>Adminto Admin v1.1</td>--}}
    {{--                                    <td>01/05/2017</td>--}}
    {{--                                    <td>10/05/2017</td>--}}
    {{--                                    <td><span class="badge bg-pink">Pending</span></td>--}}
    {{--                                    <td>Coderthemes</td>--}}
    {{--                                </tr>--}}
    {{--                                <tr>--}}
    {{--                                    <td>4</td>--}}
    {{--                                    <td>Adminto Frontend v1.1</td>--}}
    {{--                                    <td>01/01/2017</td>--}}
    {{--                                    <td>31/05/2017</td>--}}
    {{--                                    <td><span class="badge bg-purple">Work in Progress</span>--}}
    {{--                                    </td>--}}
    {{--                                    <td>Adminto admin</td>--}}
    {{--                                </tr>--}}
    {{--                                <tr>--}}
    {{--                                    <td>5</td>--}}
    {{--                                    <td>Adminto Admin v1.3</td>--}}
    {{--                                    <td>01/01/2017</td>--}}
    {{--                                    <td>31/05/2017</td>--}}
    {{--                                    <td><span class="badge bg-warning">Coming soon</span></td>--}}
    {{--                                    <td>Coderthemes</td>--}}
    {{--                                </tr>--}}
    {{--                                <tr>--}}
    {{--                                    <td>6</td>--}}
    {{--                                    <td>Adminto Admin v1.3</td>--}}
    {{--                                    <td>01/01/2017</td>--}}
    {{--                                    <td>31/05/2017</td>--}}
    {{--                                    <td><span class="badge bg-primary">Coming soon</span></td>--}}
    {{--                                    <td>Adminto admin</td>--}}
    {{--                                </tr>--}}

    {{--                                </tbody>--}}
    {{--                            </table>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}

    {{--            </div><!-- end col -->--}}

    {{--        </div>--}}
    <!-- end row -->
    </div>

@endsection
