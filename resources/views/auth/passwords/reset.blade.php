@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row justify-content-start" style="margin-top: 150px">
            <div class="col-md-6">
                <div class="card box_shadow" style="border-radius: 20px">
                    <div class="card-header" style="border-top-left-radius: 20px; border-top-right-radius: 20px">Сброс пароля</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">Адрес электронной почты</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" style="border-radius: 13px" class="box_shadow form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Пароль</label>

                                <div class="col-md-6">
                                    <input id="password" style="border-radius: 13px" type="password" class="box_shadow form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Подтвердите пароль</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" style="border-radius: 13px" type="password" class="box_shadow form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" style="border-radius: 13px" class="btn btn-primary box_shadow">
                                        Сброс пароля
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
