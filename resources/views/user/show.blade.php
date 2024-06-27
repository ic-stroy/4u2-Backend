@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('User informations')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('user.create')}}">{{translate('Create')}}</a>
            </div>
            <div class="account">
                <div class="profile_box">
                    <div class="d-flex align-items-start">
                        <div>
                            @php
                                if($model->avatar){
                                    $sms_avatar = storage_path('app/public/user/'.$model->avatar);
                                }else{
                                    $sms_avatar = storage_path('app/public/user/no');
                                }
                            @endphp
                            @if(file_exists($sms_avatar))
                                <img class="user_photo_2" src="{{asset('storage/user/'.$model->avatar)}}" alt="">
                            @else
                                <img class="user_photo_2" src="{{asset('assets/images/man.jpg')}}" alt="">
                            @endif
                        </div>
                        <div id="color_black" style="margin-left: 30px;">
                            <h3>{{$model->first_name.' '.$model->last_name.' '.$model->middle_name}}</h3>
                            <p>{{translate('Role').': '}}<b>{{$model->is_admin == 1?'Admin':'User' }}</b></p>
                            <p>{{translate('Phone').': '}}<b>{{$model->phone_number??''}}</b></p>
                            @if($year_old>0)
                                <p>{{translate('Age').': '}}<b>{{$year_old??''}}</b></p>
                            @else
                                <div class="mt-4"></div>
                            @endif
                        </div>
                    </div>

                    <div class="profile_box_content">
                        <div style="width: auto;">
                            <div class="d-flex justify-content-between" style="align-items: center">
                                <h3 class="text_name">{{translate('Email')}}:</h3>
                                <div class="text_value">
                                    {{$model->email??''}}
                                </div>
                            </div>

                            <div class="d-flex justify-content-between" style="margin-top: 20px; align-items: center">
                                <h3 class="text_name">{{translate('Role')}}:</h3>
                                <div class="text_value">
                                    {{$model->is_admin == 1?'Admin':'User' }}
                                </div>
                            </div>

                            <div class="d-flex justify-content-between" style="margin-top: 20px; align-items: center">
                                <h3 class="text_name">{{translate('Gender')}}:</h3>
                                <div class="text_value">
                                    {{$model->gender==2?translate('female'):translate('male')}}
                                </div>
                            </div>
                        </div>

                        <div style="width: auto;">
                            <div class="d-flex justify-content-between" style="align-items: center">
                                <h3 class="text_name">{{translate('Full Name')}}:</h3>
                                <div class="text_value">
                                    {{$model->first_name.' '.$model->last_name.' '.$model->middle_name}}
                                </div>
                            </div>
                            <div class="d-flex justify-content-between" style="margin-top: 20px; align-items: center">
                                <h3 class="text_name">{{translate('Birth date')}}:</h3>
                                @php
                                    if($model->birth_date){
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
                                <h3 class="text_name">{{translate('Update at')}}:</h3>
                                <div class="text_value">
                                    {{$model->updated_at??''}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <table class="table dt-responsive nowrap table_show" style="display:none;">
            <thead>
            <tr>
                <th>{{translate('Attributes')}}</th>
                <th>{{translate('Informations')}}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th>{{translate('Role')}}</th>
                <td>{{$model->role->name??''}}</td>
            </tr>
            <tr>
                <th>{{translate('Company')}}</th>
                <td>{{$model->company->name??''}}</td>
            </tr>
            <tr>
                <th>{{translate('Full name')}}</th>
                <td>{{$model->first_name.' '.$model->last_name.' '.$model->middle_name}}</td>
            </tr>
            <tr>
                <th>{{translate('Phone number')}}</th>
                <td>
                    {{$model->phone_number}}
                </td>
            </tr>
            <tr>
                <th>{{translate('Gender')}}</th>
                <td>
                    {{$model->gender??''}}
                </td>
            </tr>
            <tr>
                <th>{{translate('Birth date')}}</th>
                <td>
                    {{$model->birth_date??''}}
                </td>
            </tr>
            <tr>
                <th>{{translate('email')}}</th>
                <td>{{$model->email??''}}</td>
            </tr>
            <tr>
                <th>{{translate('Updated at')}}</th>
                <td>{{$model->updated_at??''}}</td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection
