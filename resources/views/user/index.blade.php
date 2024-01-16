@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <style>
        .delete-datas{
            border-radius: 8px;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('user.create')}}">{{__('Create')}}</a>
            </div>
            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('Avatar')}}</th>
                        <th>{{__('Firstname')}}</th>
                        <th>{{__('Lastname')}}</th>
                        <th>{{__('Role')}}</th>
                        <th>{{__('Phone number')}}</th>
                        <th class="text-center">{{__('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                @php
                    $i = 0
                @endphp
                @foreach($users as $user)
                    @php
                        $i++
                    @endphp
                    <tr>
                        <th scope="row">
                            <a class="show_page" href="{{ route('user.show', $user->id) }}">
                                {{$i}}
                            </a>
                        </th>
                        <td class="text-center">
                            <a class="show_page_color" href="{{ route('user.show', $user->id) }}">
                                @if(isset($user->id))
                                    @php
                                        if(isset($user)){
                                            $sms_avatar = storage_path('app/public/user/'.$user->avatar);
                                        }else{
                                            $sms_avatar = 'no';
                                        }
                                    @endphp
                                    @if(file_exists($sms_avatar))
                                        <img class="user_photo" src="{{asset('storage/user/'.$user->avatar??'no')}}" alt="">
                                    @else
                                        <img class="user_photo" src="{{asset('assets/images/man.jpg')}}" alt="">
                                    @endif
                                @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{ route('user.show', $user->id) }}">
                                @if(isset($user->first_name)){{ $user->first_name }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{ route('user.show', $user->id) }}">
                                @if(isset($user->last_name)){{ $user->last_name }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{ route('user.show', $user->id) }}">
                                @if($user->is_admin == 1){{ __('Admin') }}@else {{ __('User') }} @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{ route('user.show', $user->id) }}">
                                @if(isset($user->phone_number)){{ $user->phone_number }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td class="function_column">
                            <div class="d-flex justify-content-center">
                                <a class="form_functions btn btn-info" href="{{route('user.edit', $user->id)}}"><i class="fe-edit-2"></i></a>
                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{ route('user.destroy', $user->id) }}"><i class="fe-trash-2"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
