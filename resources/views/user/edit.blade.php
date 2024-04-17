@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <p class="text-muted font-14">
                {{translate('User edit')}}
            </p>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('user.update', $user->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('First name')}}</label>
                        <input type="text" class="form-control" name="first_name" required value="{{$user->first_name??''}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Last name')}}</label>
                        <input type="text" class="form-control" name="last_name" value="{{$user->last_name??''}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Middle name')}}</label>
                        <input type="text" class="form-control" name="middle_name" value="{{$user->middle_name??''}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Phone number')}}</label>
                        <input type="text" class="form-control" name="phone_number" value="{{$user->phone_number??''}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <div style="text-align: center">
                            @if(isset($user->avatar))
                                @php
                                    $avatar = storage_path('app/public/user/'.$user->avatar)
                                @endphp
                            @else
                                @php
                                    $avatar = 'no'
                                @endphp
                            @endif
                            @if(file_exists($avatar))
                                <img src="{{asset('storage/user/'.$user->avatar)}}" alt="" height="94px">
                            @endif
                        </div>
                        <label class="form-label">{{translate('Avatar')}}</label>
                        <input type="file" class="form-control" name="avatar" value=""/>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="gender" class="form-label">{{translate('Gender')}}</label>
                        <select id="gender" class="form-select" name="gender">
                            <option value="">{{translate('Choose..')}}</option>
                            <option value="1" @if(isset($user)){{$user->gender==1?'selected':''}}@endif>{{translate('Man')}}</option>
                            <option value="2" @if(isset($user)){{$user->gender==2?'selected':''}}@endif>{{translate('Woman')}}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <div class="mb-3 col-6">
                            <label for="role" class="form-label">{{translate('Is admin')}}</label><br>
                            <input type="checkbox" value="1" name="is_admin" {{$user->is_admin == 1 ? 'checked':''}}>
                        </div>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Birth date')}}</label>
                        @php
                            $birth_date = explode(' ', $user->birth_date??'');
                        @endphp
                        <input type="date" class="form-control" name="birth_date" value="{{$birth_date[0]}}"/>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{translate('Login')}}</label>
                    <input type="email" class="form-control" name="email" required value="{{$user->email??''}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{translate('Current password')}}</label>
                    <input type="password" class="form-control" name="password" value=""/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{translate('New password')}}</label>
                    <input type="password" class="form-control" name="new_password" value=""/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{translate('Password confirmation')}}</label>
                    <input type="password" class="form-control" name="password_confirmation" value=""/>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
