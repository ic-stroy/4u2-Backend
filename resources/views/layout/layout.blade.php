<!DOCTYPE html>
<html lang="en">
@php
    $current_user = \Illuminate\Support\Facades\Auth::user();
@endphp
<head>
    <meta charset="utf-8" />
    <title>{{ '4u2' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

    <link href="{{ asset('assets/libs/toastr/build/toastr.min.css') }}" type="text/css" rel="stylesheet"/>
    <!-- third party css -->
    <link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style"/>
      {{-- Main css --}}
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('assets/libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- icons -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

</head>
<style>
#dropdownMenuButton{
    z-index: 1;
}

</style>
<!-- body start -->

<body class="loading" data-layout-color="light" data-layout-mode="default" data-layout-size="fluid" id="body_layout"
    data-topbar-color="light" data-leftbar-position="fixed" data-leftbar-color="light" data-leftbar-size='default'
    data-sidebar-user='true'>

    <!-- Begin page -->
    <div id="wrapper">


        <!-- Topbar Start -->
        <div class="navbar-custom">
                <ul class="list-unstyled topnav-menu float-end mb-0">

                    <li class="d-none d-lg-block">
                        <form class="app-search">
                            <div class="app-search-box">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search..." id="top-search">
                                    <button class="btn input-group-text" type="submit">
                                        <i class="fe-search"></i>
                                    </button>
                                </div>
                                <div class="dropdown-menu dropdown-lg" id="search-dropdown">
                                    <!-- item-->
                                    <div class="dropdown-header noti-title">
                                        <h5 class="text-overflow mb-2">{{__('Found 22 results')}}</h5>
                                    </div>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="fe-home me-1"></i>
                                        <span>{{__('Analytics Report')}}</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="fe-aperture me-1"></i>
                                        <span>{{__('How can I help you?')}}</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="fe-settings me-1"></i>
                                        <span>{{__('User profile settings')}}</span>
                                    </a>

                                    <!-- item-->
                                    <div class="dropdown-header noti-title">
                                        <h6 class="text-overflow mb-2 text-uppercase">{{__('Users')}}</h6>
                                    </div>

                                    <div class="notification-list">
                                        <!-- item-->
                                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                                            <div class="d-flex align-items-start">
                                                <img class="d-flex me-2 rounded-circle" src="{{ asset('assets/images/user/user-2.jpg') }}"
                                                     alt="Generic placeholder image" height="32">
                                                <div class="w-100">
                                                    <h5 class="m-0 font-14">Erwin E. Brown</h5>
                                                    <span class="font-12 mb-0">{{__('UI Designer')}}</span>
                                                </div>
                                            </div>
                                        </a>

                                        <!-- item-->
                                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                                            <div class="d-flex align-items-start">
                                                <img class="d-flex me-2 rounded-circle" src="{{ asset('assets/images/user/user-5.jpg') }}"
                                                     alt="Generic placeholder image" height="32">
                                                <div class="w-100">
                                                    <h5 class="m-0 font-14">Jacob Deo</h5>
                                                    <span class="font-12 mb-0">{{__('Developer')}}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </li>
                    <li class="">
                        <div>

                            <div class="align-items-stretch d-flex dropdown" id="lang-change">
                                <a class="buttonUzbDropDownHeader dropdown-toggle" type="button" id="dropdownMenuButton" role="button"
                                   data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" href="javascript:void(0);">
{{--                                    @switch($locale)--}}
{{--                                        @case('uz')--}}
{{--                                        <img class="notifRegion2" id="selected_language"--}}
{{--                                             src="{{ asset('/assets/images/language/region.png') }}" alt="region">--}}
{{--                                        @break--}}

{{--                                        @case('en')--}}
{{--                                        <img class="notifRegion2" id="selected_language"--}}
{{--                                             src="{{ asset('/assets/images/language/GB.png') }}" alt="region">--}}
{{--                                        @break--}}

{{--                                        @case('ru')--}}
{{--                                        <img class="notifRegion2" id="selected_language"--}}
{{--                                             src="{{ asset('/assets/images/language/RU.png') }}" alt="region">--}}
{{--                                        @break--}}
{{--                                    @endswitch--}}
                                </a>
                                <div id="language_flag" class="language_flag display-none"
                                     style="border: none; background-color: transparent;" aria-labelledby="dropdownMenuButton">
                                    <div class="up-arrow"></div>
                                    <div class="dropdownMenyApplyUzbFlag">
{{--                                            <a href="javascript:void(0)" data-flag=""--}}
{{--                                               class="dropdown-item dropdown-item dropdownLanguageItem @if ($locale == $language->code??'') active @endif" >--}}
{{--                                                @switch($language->code)--}}
{{--                                                    @case('uz')--}}
{{--                                                    <img class="dropdownRegionBayroq" id="lang_uz" style="margin-right: 8px;" src="{{asset('/assets/images/language/region.png')}}" alt="region">--}}
{{--                                                    {{ $language->name??'' }}--}}
{{--                                                    @break--}}

{{--                                                    @case('ru')--}}
{{--                                                    <img class="dropdownRegionBayroq" id="lang_ru" style="margin-right: 8px;"--}}
{{--                                                         src="{{ asset('/assets/images/language/RU.png') }}" alt="region">--}}
{{--                                                    {{ $language->name??'' }}--}}
{{--                                                    @break--}}

{{--                                                    @case('en')--}}
{{--                                                    <img class="dropdownRegionBayroq" id="lang_en" style="margin-right: 8px;"--}}
{{--                                                         src="{{ asset('/assets/images/language/GB.png') }}" alt="region">--}}
{{--                                                    {{ $language->name??'' }}--}}
{{--                                                    @break--}}
{{--                                                @endswitch--}}
{{--                                            </a>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="dropdown d-inline-block d-lg-none">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-bs-toggle="dropdown"
                           href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="fe-search noti-icon"></i>
                        </a>
                        <div class="dropdown-menu dropdown-lg dropdown-menu-end p-0">
                            <form class="p-3">
                                <input type="text" class="form-control" placeholder="Search ..."
                                       aria-label="Recipient's username">
                            </form>
                        </div>
                    </li>

                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown"
                           href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="fe-bell noti-icon"></i>
                            <span class="badge bg-danger rounded-circle noti-icon-badge">9</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-lg">

                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5 class="m-0">
                                    <span class="float-end">
                                        <a href="" class="text-dark">
                                            <small>{{__('Clear All')}}</small>
                                        </a>
                                    </span>{{__('Notification')}}
                                </h5>
                            </div>

                            <div class="noti-scroll" data-simplebar>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item active">
                                    <div class="notify-icon">
                                        <img src="{{ asset('assets/images/user/user-1.jpg') }}" class="img-fluid rounded-circle"
                                             alt="" />
                                    </div>
                                    <p class="notify-details">Cristina Pride</p>
                                    <p class="text-muted mb-0 user-msg">
                                        <small>{{__('Hi, How are you? What about our next meeting')}}</small>
                                    </p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-primary">
                                        <i class="mdi mdi-comment-account-outline"></i>
                                    </div>
                                    <p class="notify-details">{{__('Caleb Flakelar commented on Admin')}}
                                        <small class="text-muted">{{__('1 min ago')}}</small>
                                    </p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon">
                                        <img src="{{ asset('assets/images/user/user-4.jpg') }}" class="img-fluid rounded-circle"
                                             alt="" />
                                    </div>
                                    <p class="notify-details">Karen Robinson</p>
                                    <p class="text-muted mb-0 user-msg">
                                        <small>{{ __('Wow ! this admin looks good and awesome design')}}</small>
                                    </p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-warning">
                                        <i class="mdi mdi-account-plus"></i>
                                    </div>
                                    <p class="notify-details">{{ __('New user registered.')}}
                                        <small class="text-muted">{{ __('5 hours ago')}}</small>
                                    </p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-info">
                                        <i class="mdi mdi-comment-account-outline"></i>
                                    </div>
                                    <p class="notify-details">{{ __('Caleb Flakelar commented on Admin')}}
                                        <small class="text-muted">{{ __('4 days ago')}}</small>
                                    </p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-secondary">
                                        <i class="mdi mdi-heart"></i>
                                    </div>
                                    <p class="notify-details">Carlos Crouch {{ __('liked')}}
                                        <b>Admin</b>
                                        <small class="text-muted">{{ __('13 days ago')}}</small>
                                    </p>
                                </a>
                            </div>

                            <!-- All-->
                            <a href="javascript:void(0);"
                               class="dropdown-item text-center text-primary notify-item notify-all">
                                {{ __('View all')}}
                                <i class="fe-arrow-right"></i>
                            </a>

                        </div>
                    </li>

                    <li class="dropdown notification-list">
                        <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
                            <i class="fe-settings noti-icon"></i>
                        </a>
                    </li>

                </ul>

                <ul class="list-unstyled topnav-menu topnav-menu-left mb-0" style="margin-top: -150px">
                    <li>
                        <button class="button-menu-mobile disable-btn waves-effect">
                            <i class="fe-menu"></i>
                        </button>
                    </li>

                    <li>
                        <h4 class="page-title-main">@yield('title')</h4>
                    </li>

                </ul>
                <div class="clearfix"></div>

        </div>
        <!-- end Topbar -->
        <!-- ========== Left Sidebar Start ========== -->
        <div class="left-side-menu" style="overflow: scroll; z-index: 1004; margin-top: -70px">

            <div class="h-100">

                <!-- User box -->
                <div class="user-box text-center">
{{--                    @if(isset($current_user->personalInfo))--}}
{{--                        @php--}}
{{--                        if(!isset($current_user->personalInfo->avatar)){--}}
{{--                            $current_user->personalInfo->avatar = 'no';--}}
{{--                        }--}}
{{--                            $sms_avatar = storage_path('app/public/user/'.$current_user->personalInfo->avatar);--}}
{{--                        @endphp--}}
{{--                        @if(file_exists($sms_avatar))--}}
{{--                            <img class="rounded-circle img-thumbnail avatar-md" src="{{asset('storage/user/'.$current_user->personalInfo->avatar)}}" alt="">--}}
{{--                        @else--}}
                            <img class="rounded-circle img-thumbnail avatar-md" src="{{asset('assets/images/man.jpg')}}" alt="">
{{--                        @endif--}}
{{--                    @endif--}}
                    <div class="dropdown">
                        <a href="#" class="user-name dropdown-toggle h5 mt-2 mb-1 d-block"
                            data-bs-toggle="dropdown" aria-expanded="false">
{{--                            @if(isset($current_user) && isset($current_user->personalInfo))--}}
{{--                                {{$current_user?$current_user->personalInfo->first_name:''}} {{$current_user?$current_user->personalInfo->last_name:''}}--}}
{{--                            @endif--}}
                        </a>
                        <div class="dropdown-menu user-pro-dropdown">

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-user me-1"></i>
                                <span>{{ __('My Account')}}</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-settings me-1"></i>
                                <span>{{__('Settings')}}</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-lock me-1"></i>
                                <span>Lock Screen</span>
                            </a>

                            <!-- item-->
{{--                            <form action="{{route('logout')}}" method="POST">--}}
{{--                                @csrf--}}
{{--                                @method("POST")--}}
{{--                                <button class="dropdown-item notify-item" type="submit">--}}
{{--                                    <i class="fe-log-out me-1"></i>--}}
{{--                                    <span>{{__('Logout')}}</span>--}}
{{--                                </button>--}}
{{--                            </form>--}}
                        </div>
                    </div>

                    {{-- <p class="text-muted left-user-info">{{$current_user->role->name??''}}</p> --}}

                    <ul class="list-inline">
                        <li class="list-inline-item dropdown notification-list topbar-dropdown">
                            <a class="nav-link dropdown-toggle nav-user d-flex me-0 waves-effect waves-light mt-8"
                               data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                               aria-expanded="false">
                                <i class="mdi mdi-cog"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-center profile-dropdown ">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">{{ __('Welcome !')}}</h6>
                                </div>

                                <!-- item-->
{{--                                <a href="{{route('account')}}" class="dropdown-item notify-item">--}}
{{--                                    <i class="fe-user"></i>--}}
{{--                                    <span>{{__('My Account')}}</span>--}}
{{--                                </a>--}}

                                <!-- item-->
                                <a href="auth-lock-screen.html" class="dropdown-item notify-item">
                                    <i class="fe-lock"></i>
                                    <span>{{ __('Lock Screen')}}</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <!-- item-->
{{--                                <form action="{{route('logout')}}" method="POST">--}}
{{--                                    @csrf--}}
{{--                                    @method("POST")--}}
{{--                                    <button class="dropdown-item notify-item" type="submit">--}}
{{--                                        <i class="fe-log-out"></i>--}}
{{--                                        <span>{{__('Logout')}}</span>--}}
{{--                                    </button>--}}
{{--                                </form>--}}
                            </div>
                        </li>
                        <li class="list-inline-item">
                            <button  type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#logout-alert-modal" data-url="{{ route('logout') }}" style="border: 0px; background-color: transparent; color: #98a6ad">
                                <i class="mdi mdi-power" style="color: red"></i>
                            </button>
                        </li>
                    </ul>
                </div>
                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <ul id="side-menu">
                        <li>
                            <a href="{{route('dashboard')}}">
                            <i class="mdi mdi-home-outline"></i>
                                 <span class="badge bg-success rounded-pill float-end">9+</span>
                                <span> {{__('Home')}} </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('order.index') }}">
                                <i class="mdi mdi-order-bool-ascending"></i>
                                <!-- <span class="badge bg-success rounded-pill float-end">9+</span> -->
                                {{-- <span> Order </span> --}}
                                <span> {{ __('Order') }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.index') }}">
                                <i class="mdi mdi-account-star-outline"></i>
                                <span> {{ __('Users') }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('color.index') }}">
                                <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                <span> {{ __('Color') }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('size.index') }}">
                                <i class="mdi mdi-account-outline"></i>
                                <span> {{ __('Sizes') }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('product.category') }}">
                                <i class="mdi mdi-basket-outline"></i>
                                <span> {{ __('Products') }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('characterizedProducts.category') }}">
                                <i class="mdi mdi-shopping-outline"></i>
                                <span> {{ __('Characterized products') }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('coupons.index') }}">
                                <i class="mdi mdi-gift-outline"></i>
                                <span> {{ __('Coupon') }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="#categoryMenus" data-bs-toggle="collapse">
                                <i class="mdi mdi-format-list-bulleted"></i>
                                <span> {{ __('Category') }} </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="categoryMenus">
                                <ul class="nav-second-level">
                                    <li>
                                        <a href="{{ route('category.index') }}">
                                            <span> {{ __('Category') }} </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('subcategory.category') }}">
                                            <span> {{ __('Sub Category') }} </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('subsubcategory.category') }}">
                                            <span> {{ __('Sub Sub Category') }} </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>

                </div>
                <!-- End Sidebar -->

                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <br>
            <div class="content">
                <!-- Start Content-->
                <div class="container-fluid">
                    {{-- <div class="card">
                        <div class="card-body"> --}}

                            @yield('content')

                        {{-- </div>
                    </div> --}}
                </div>
                <!-- container-fluid -->
            </div>

        </div> <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                            {{-- &copy; Adminto theme by <a href="">Coderthemes</a> --}}
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>
        <div id="logout-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="dripicons-warning h1 text-warning"></i>
                            <h4 class="mt-2">{{__('Logout')}}</h4>
                            <p class="mt-3">{{__('Confirm to logout')}}</p>
                            <div class="d-flex justify-content-around">
                                <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal">{{__('No')}}</button>
                                <form action="{{route('logout')}}" method="POST">
                                    @csrf
                                    @method("POST")
                                    <button type="submit" class="btn btn-warning my-2" data-bs-dismiss="modal">
                                        {{__('Yes')}}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->
    <div class="right-bar">
        <div data-simplebar class="h-100">
            <div class="rightbar-title">
                <a href="javascript:void(0);" class="right-bar-toggle float-end">
                    <i class="mdi mdi-close"></i>
                </a>
                <h4 class="font-16 m-0 text-white">{{ __('Theme Customizer')}}</h4>
            </div>
            <!-- Tab panes -->
            <div class="tab-content pt-0">

                <div class="tab-pane active" id="settings-tab" role="tabpanel">

                    <div class="p-3">
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">{{ __('Color Scheme')}}</h6>
                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="layout-color" value="light"
                                id="light-mode-check" />
                            <label class="form-check-label" for="light-mode-check">{{ __('Light Mode')}}</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="layout-color" value="dark"
                                id="dark-mode-check" checked/>
                            <label class="form-check-label" for="dark-mode-check">{{ __('Dark Mode')}}</label>
                        </div>

                        <!-- Width -->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">{{ __('Width')}}</h6>
                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="layout-size" value="fluid"
                                id="fluid" checked />
                            <label class="form-check-label" for="fluid-check">{{ __('Fluid')}}</label>
                        </div>
                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="layout-size" value="boxed"
                                id="boxed" />
                            <label class="form-check-label" for="boxed-check">{{ __('Boxed')}}</label>
                        </div>

                        <!-- Menu positions -->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">{{ __('Menus (Leftsidebar and Topbar) Positon')}}</h6>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-position" value="fixed"
                                id="fixed-check" checked />
                            <label class="form-check-label" for="fixed-check">{{ __('Fixed')}}</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-position"
                                value="scrollable" id="scrollable-check" />
                            <label class="form-check-label" for="scrollable-check">{{ __('Scrollable')}}</label>
                        </div>

                        <!-- Left Sidebar-->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">{{ __('Left Sidebar Color')}}</h6>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-color" value="light"
                                id="light" />
                            <label class="form-check-label" for="light-check">{{ __('Light')}}</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-color" value="dark"
                                id="dark" checked />
                            <label class="form-check-label" for="dark-check">{{ __('Dark')}}</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-color" value="brand"
                                id="brand" />
                            <label class="form-check-label" for="brand-check">{{ __('Brand')}}</label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" name="leftbar-color" value="gradient"
                                id="gradient" />
                            <label class="form-check-label" for="gradient-check">{{ __('Gradient')}}</label>
                        </div>

                        <!-- size -->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">{{ __('Left Sidebar Size')}}</h6>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-size" value="default"
                                id="default-size-check" checked />
                            <label class="form-check-label" for="default-size-check">{{ __('Default')}}</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-size" value="condensed"
                                id="condensed-check" />
                            <label class="form-check-label" for="condensed-check">{{ __('Condensed')}} <small>{{ __('(Extra Small size)')}}</small></label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="leftbar-size" value="compact"
                                id="compact-check" />
                            <label class="form-check-label" for="compact-check">{{ __('Compact')}} <small>{{ __('(Small size)')}}</small></label>
                        </div>
                        <!-- Topbar -->
                        <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">{{ __('Topbar')}}</h6>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="topbar-color" value="dark"
                                id="darktopbar-check" checked />
                            <label class="form-check-label" for="darktopbar-check">{{ __('Dark')}}</label>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input type="checkbox" class="form-check-input" name="topbar-color" value="light"
                                id="lighttopbar-check" />
                            <label class="form-check-label" for="lighttopbar-check">{{ __('Light')}}</label>
                        </div>

                        <div class="d-grid mt-4">
                            <button class="btn btn-primary" id="resetBtn">{{ __('Reset to Default')}}</button>
                            {{-- <a href="https://1.envato.market/admintoadmin" class="btn btn-danger mt-3" target="_blank"><i class="mdi mdi-basket me-1"></i> Purchase Now</a> --}}
                        </div>

                    </div>

                </div>
            </div>

        </div> <!-- end slimscroll-menu-->
    </div>
    <!-- /Right-bar -->


    <!-- Warning Alert Modal FOR DELETE -->
    <div id="warning-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <i class="dripicons-warning h1 text-warning"></i>
                        <h4 class="mt-2">{{ __('Are you sure delete this data')}}</h4>
                        <form style="display: inline-block;" action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal"> {{ __('No')}}</button>
                            {{-- <button type="button" class="btn btn-danger my-2" data-bs-dismiss="modal">{{ __('No') }}</button> --}}
                            <button type="submit" class="btn btn-success my-2"> {{ __('Yes')}} </button>
                            {{-- <button type="submit" class="btn btn-warning my-2">{{ __('Yes') }}</button> --}}
                        </form>
                        {{-- <button type="button" class="btn btn-warning my-2" data-bs-dismiss="modal">Continue</button> --}}
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- Standard modal content -->
    <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary">{{ __('Save changes') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- Standard modal content -->
    <div id="standard-modal-admin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary">{{ __('Save changes') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/libs/toastr/build/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>

    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-pickers.init.js') }}"></script>

    <script src="{{ asset('assets/libs/selectize/js/standalone/selectize.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>

    <script src="{{asset('assets/libs/dropzone/min/dropzone.min.js')}}"></script>
    <script src="{{asset('assets/libs/dropify/js/dropify.min.js')}}"></script>
    <!-- Init js-->
    <script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>
    <!-- knob plugin -->
    <script src="{{ asset('assets/libs/jquery-knob/jquery.knob.min.js') }}"></script>

    <!--Morris Chart-->
    <script src="{{ asset('assets/libs/morris.js06/morris.min.js') }}"></script>
    <script src="{{ asset('assets/libs/raphael/raphael.min.js') }}"></script>

    <!-- Dashboar init js-->
{{--    <script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>--}}
    <script>

//light mode or dark mode
        let light_mode = document.getElementById('light-mode-check')
        let dark_mode = document.getElementById('dark-mode-check')
        let body_layout = document.getElementById('body_layout')
        light_mode.addEventListener('click', function (){
            localStorage.setItem('layout_local', 'light')
        })
        dark_mode.addEventListener('click', function (){
            localStorage.setItem('layout_local', 'dark')
        })
        if(localStorage.getItem('layout_local') == undefined || localStorage.getItem('layout_local') == null){
            body_layout.setAttribute('data-layout-color', 'default')
        }else{
            body_layout.setAttribute('data-layout-color', localStorage.getItem('layout_local'))
        }

//fluid or boxed
        let fluid = document.getElementById('fluid')
        let boxed = document.getElementById('boxed')
        fluid.addEventListener('click', function (){
            localStorage.setItem('fluid_or_boxed', 'fluid')
        })
        boxed.addEventListener('click', function (){
            localStorage.setItem('fluid_or_boxed', 'boxed')
        })
        if(localStorage.getItem('fluid_or_boxed') == undefined || localStorage.getItem('fluid_or_boxed') == null){
            body_layout.setAttribute('data-layout-size', 'fluid')
        }else{
            body_layout.setAttribute('data-layout-size', localStorage.getItem('fluid_or_boxed'))
        }

//fixed or scrollable
        let fixed_check = document.getElementById('fixed-check')
        let scrollable_check = document.getElementById('scrollable-check')
        fixed_check.addEventListener('click', function (){
            localStorage.setItem('fixed_or_scrollable', 'fixed')
        })
        scrollable_check.addEventListener('click', function (){
            localStorage.setItem('fixed_or_scrollable', 'scrollable')
        })
        if(localStorage.getItem('fixed_or_scrollable') == undefined || localStorage.getItem('fixed_or_scrollable') == null){
            body_layout.setAttribute('data-leftbar-positione', 'fixed')
        }else{
            body_layout.setAttribute('data-leftbar-position', localStorage.getItem('fixed_or_scrollable'))
        }

//fixed or scrollable
        let light = document.getElementById('light')
        let dark = document.getElementById('dark')
        let brand = document.getElementById('brand')
        let gradient = document.getElementById('gradient')
        light.addEventListener('click', function (){
            localStorage.setItem('leftbar_color', 'light')
        })
        dark.addEventListener('click', function (){
            localStorage.setItem('leftbar_color', 'dark')
        })
        brand.addEventListener('click', function (){
            localStorage.setItem('leftbar_color', 'brand')
        })
        gradient.addEventListener('click', function (){
            localStorage.setItem('leftbar_color', 'gradient')
        })
        if(localStorage.getItem('leftbar_color') == undefined || localStorage.getItem('leftbar_color') == null){
            body_layout.setAttribute('data-leftbar-color', 'light')
        }else{
            body_layout.setAttribute('data-leftbar-color', localStorage.getItem('leftbar_color'))
        }

//fixed or scrollable
        let default_size_check = document.getElementById('default-size-check')
        let condensed_check = document.getElementById('condensed-check')
        let compact_check = document.getElementById('compact-check')
        default_size_check.addEventListener('click', function (){
            localStorage.setItem('leftbar_size', 'default')
        })
        condensed_check.addEventListener('click', function (){
            localStorage.setItem('leftbar_size', 'condensed')
        })
        compact_check.addEventListener('click', function (){
            localStorage.setItem('leftbar_size', 'compact')
        })
        if(localStorage.getItem('leftbar_size') == undefined || localStorage.getItem('leftbar_size') == null){
            body_layout.setAttribute('data-leftbar-size', 'default')
        }else{
            body_layout.setAttribute('data-leftbar-size', localStorage.getItem('leftbar_size'))
        }

//Topbar color
        let darktopbar_check = document.getElementById('darktopbar-check')
        let lighttopbar_check = document.getElementById('lighttopbar-check')
        darktopbar_check.addEventListener('click', function (){
            localStorage.setItem('topbar_color', 'dark')
        })
        lighttopbar_check.addEventListener('click', function (){
            localStorage.setItem('topbar_color', 'light')
        })
        if(localStorage.getItem('topbar_color') == undefined || localStorage.getItem('topbar_color') == null){
            body_layout.setAttribute('data-topbar-color', 'light')
        }else{
            body_layout.setAttribute('data-topbar-color', localStorage.getItem('topbar_color'))
        }

// Reset to default
        let resetBtn = document.getElementById('resetBtn')
        resetBtn.addEventListener('click', function (){
            if(localStorage.getItem('topbar_color') != undefined || localStorage.getItem('topbar_color') != null){
                localStorage.removeItem('topbar_color')
            }
            if(localStorage.getItem('leftbar_size') != undefined || localStorage.getItem('leftbar_size') != null){
                localStorage.removeItem('leftbar_size')
            }
            if(localStorage.getItem('leftbar_color') != undefined || localStorage.getItem('leftbar_color') != null){
                localStorage.removeItem('leftbar_color')
            }
            if(localStorage.getItem('fixed_or_scrollable') != undefined || localStorage.getItem('fixed_or_scrollable') != null){
                localStorage.removeItem('fixed_or_scrollable')
            }
            if(localStorage.getItem('fluid_or_boxed') != undefined || localStorage.getItem('fluid_or_boxed') != null){
                localStorage.removeItem('fluid_or_boxed')
            }
            if(localStorage.getItem('layout_local') != undefined || localStorage.getItem('layout_local') != null){
                localStorage.removeItem('layout_local')
            }
            location.reload();
        })

    </script>

    <!-- Datatables init -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>

    <!-- App js-->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    <script>
        $(document).on('click', '.delete-datas', function(e) {
            console.log('good')
            var url = $(this).attr('data-url')
            $('#warning-alert-modal').find('form').attr('action', url)
        })

        $(document).ready(function() {
            $('.basic-datepicker').flatpickr();

            // $('#passport_expired_date').datepicker({
            //     format: 'yyyy-mm-dd',
            //     autoclose: true
            // });
        })

        $(document).ready(function() {
            let sessionSuccess ="{{session('status')}}";
            if(sessionSuccess){
                toastr.success(sessionSuccess)
            }
            {{--let language = '{{ $locale }}'--}}
            let uz = `{{ asset('/assets/images/language/region.png') }}`
            let ru = `{{ asset('/assets/images/language/RU.png') }}`
            let en = `{{ asset('/assets/images/language/GB.png') }}`

            if ($('#lang-change').length > 0) {
                $('#lang-change .dropdownMenyApplyUzbFlag a').each(function() {
                    $(this).on('click', function(e) {
                        e.preventDefault();
                        var $this = $(this);
                        var locale = $this.data('flag');
                        switch (locale) {
                            case 'uz':
                                $('#selected_language').attr('src', uz)
                                break;
                            case 'en':
                                $('#selected_language').attr('src', en)
                                break;
                            case 'ru':
                                $('#selected_language').attr('src', ru)
                                break;
                        }
                        {{--$.post('{{ route('language.change') }}', {--}}
                        {{--    _token: '{{ csrf_token() }}',--}}
                        {{--    locale: locale--}}
                        {{--}, function(data) {--}}
                        {{--    location.reload();--}}
                        {{--});--}}

                    });
                });
            }
        })

        $(document).on('click', '.delete-order', function(e) {
            var url = $(this).attr('data-url')
            $('#warning-alert-modal').find('form').attr('action', url)
        })

        let dropdownMenuButton = document.getElementById('dropdownMenuButton')
        let language_flag = document.getElementById('language_flag')
        // let wrapper = document.getElementById('wrapper')
        // wrapper.addEventListener('click', function() {
        //     if (language_flag.classList.contains('display-none')) {
        //         language_flag.classList.add('display-none')
        //     }
        // });
        dropdownMenuButton.addEventListener('click', function() {
            if (language_flag.classList.contains('display-none')) {
                language_flag.classList.remove('display-none')
            } else {
                language_flag.classList.add('display-none')
            }
        });
    </script>
</body>

</html>
