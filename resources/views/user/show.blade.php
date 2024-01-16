@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <style>
        .hide_pagination{
            position: absolute;
            z-index:4;
            background-color: white;
            width: 270px;
            height: 80px;
            margin-top: -80px
        }
    </style>
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('User informations')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('user.create')}}">{{__('Create')}}</a>
            </div>
            <div class="account">
                <div class="profile_box">
                    <div class="d-flex align-items-start">
                        <div>
                            @if(isset($model))
                                @php
                                    $sms_avatar = storage_path('app/public/user/'.$model->avatar??'no');
                                @endphp
                                @php
                                if(isset($model->birth_date)){
                                    $birth_date_array = explode(' ', $model->birth_date);
                                    $now_time = strtotime('now');
                                    $birth_time = strtotime($model->birth_date);
                                    $month = date('m', ($now_time));
                                    $day = date('d', ($now_time));
                                    $birth_month = date('m', ($birth_time));
                                    $birth_date = date('d', ($birth_time));
                                    $year = date('Y', ($now_time));
                                    $birth_year = date('Y', ($birth_time));
                                    $year_old = 0;
                                    if($year > $birth_year){
                                        $year_old = $year - $birth_year - 1;
                                        if($month > $birth_month){
                                            $year_old = $year_old +1;
                                        }elseif($month == $birth_month){
                                            if($day >= $birth_date){
                                                $year_old = $year_old +1;
                                            }
                                        }
                                    }
                                }
                                @endphp
                                @if(file_exists($sms_avatar))
                                    <img class="user_photo_2" src="{{asset('storage/user/'.$model->avatar)}}" alt="">
                                @else
                                    <img class="user_photo_2" src="{{asset('assets/images/man.jpg')}}" alt="">
                                @endif
                            @endif
                        </div>
                        <div id="color_black" style="margin-left: 30px;">
                            <h3 >@if(isset($model)){{$model->first_name.' '.$model->last_name.' '.$model->middle_name}}@endif</h3>
                            <p>{{__('Role').': '}}<b>{{$model->is_admin == 1?'Admin':'User' }}</b></p>
                            <p>{{__('Phone').': '}}<b>@if(isset($model)){{$model->phone_number??''}}@endif</b></p>
                            <p>{{__('Age').': '}}<b>{{$year_old??''}}</b></p>
                        </div>
                    </div>

                    <div class="profile_box_content">
                        <div style="width: auto;">
                            <div class="d-flex justify-content-between" style="align-items: center">
                                <h3 class="text_name">{{__('Email')}}:</h3>
                                <div class="text_value">
                                    {{$model->email??''}}
                                </div>
                            </div>

                            <div class="d-flex justify-content-between" style="margin-top: 20px; align-items: center">
                                <h3 class="text_name">{{__('Role')}}:</h3>
                                <div class="text_value">
                                    {{$model->is_admin == 1?'Admin':'User' }}
                                </div>
                            </div>

                            <div class="d-flex justify-content-between" style="margin-top: 20px; align-items: center">
                                <h3 class="text_name">{{__('Gender')}}:</h3>
                                <div class="text_value">
                                    @if(isset($model))
                                        {{$model->gender==2?__('female'):__('male')}}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div style="width: auto;">
                            <div class="d-flex justify-content-between" style="align-items: center">
                                <h3 class="text_name">{{__('Full Name')}}:</h3>
                                <div class="text_value">
                                    @if(isset($model))
                                        {{$model->first_name.' '.$model->last_name.' '.$model->middle_name}}
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex justify-content-between" style="margin-top: 20px; align-items: center">
                                <h3 class="text_name">{{__('Birth date')}}:</h3>
                                @php
                                    if(isset($model) && isset($model->birth_date)){
                                            $birth_date_arr = explode(' ', $model->birth_date);
                                    }else{
                                        $birth_date_arr = [];
                                    }
                                @endphp
                                <div class="text_value">
                                    {{$birth_date_arr[0]??''}}
                                </div>
                            </div>
                            <div class="d-flex justify-content-between" style="margin-top: 20px; align-items: center">
                                <h3 class="text_name">{{__('Update at')}}:</h3>
                                <div class="text_value">
                                    {{$model->updated_at??''}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <table id="datatable-buttons" class="table dt-responsive nowrap table_show" style="display:none;">
            <thead>
            <tr>
                <th>{{__('Attributes')}}</th>
                <th>{{__('Informations')}}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th>{{__('Role')}}</th>
                <td>{{$model->role->name??''}}</td>
            </tr>
            <tr>
                <th>{{__('Company')}}</th>
                <td>{{$model->company->name??''}}</td>
            </tr>
            <tr>
                <th>{{__('Full name')}}</th>
                <td>
                    @if(isset($model))
                        {{$model->first_name.' '.$model->last_name.' '.$model->middle_name}}</td>
                    @endif
            </tr>
            <tr>
                <th>{{__('Phone number')}}</th>
                <td>
                    @if(isset($model))
                        {{$model->phone_number}}
                    @endif
                </td>
            </tr>
            <tr>
                <th>{{__('Gender')}}</th>
                <td>
                    @if(isset($model))
                        {{$model->gender??''}}
                    @endif
                </td>
            </tr>
            <tr>
                <th>{{__('Birth date')}}</th>
                <td>
                    @if(isset($model))
                        {{$model->birth_date??''}}
                    @endif
                </td>
            </tr>
            <tr>
                <th>{{__('email')}}</th>
                <td>{{$model->email??''}}</td>
            </tr>
            <tr>
                <th>{{__('Updated at')}}</th>
                <td>{{$model->updated_at??''}}</td>
            </tr>
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <div class="hide_pagination"></div>
        </div>
    </div>
    <script>
        let hide_pagination = document.getElementsByClassName('hide_pagination')
        if(localStorage.getItem('layout_local') == undefined || localStorage.getItem('layout_local') == null){
            hide_pagination[0].style.backgroundColor = 'white'
        }else{
            hide_pagination[0].style.backgroundColor = '#313844'
        }
    </script>
@endsection
