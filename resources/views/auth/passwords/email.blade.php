@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row justify-content-start align-items-center" style="margin-top: 210px">
            <div class="col-md-5">
                <div class="card box_shadow" style="border-radius: 20px">
                    <div class="card-header" style="border-top-left-radius: 20px; border-top-right-radius: 20px">Сброс пароля</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="email" class="col-md-5 col-form-label text-md-end">Адрес электронной почты</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" style="border-radius: 13px" class="box_shadow form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Введите адрес электронной почты">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-5"></div>
                                <div class="col-md-6">
                                    <button type="submit" style="border-radius: 13px; width: 100%" class="btn btn-primary">
                                        Отправить ссылку
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
