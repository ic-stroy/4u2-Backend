@extends('layout.app')

@section('content')
    <style>
        .white_color{
            color: white;
        }
        .box_shadow{box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25), 0px 4px 4px 0px rgba(0, 0, 0, 0.25);}

        .correct_img img {
            height: 36.7px;
            width: 173px;
        }
    </style>
    <div class="container">

        <div class="row justify-content-start">
            <div class="col-md-8 col-lg-6 col-xl-4">
                @if (session('status'))
                    <div class="alert alert-danger">
                        {{session('status')}}
                    </div>
                @endif
                <div class="card box_shadow" style="border-radius: 20px; margin-top: 100px">
                    <div class="card-body p-4">

                        <div class="text-center mb-4">
                            <h4 class="text-uppercase mt-0">Войти</h4>
                        </div>

                        <form action="{{route('login')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="emailaddress" class="form-label">Адрес электронной почты</label>
                                <input style="border-radius: 8px !important;" class="box_shadow form-control @error('email') is-invalid @enderror" type="email" id="emailaddress" name="email" required="" placeholder="Введите адрес электронной почты">
                            </div>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            <div class="mb-3">
                                <label for="password" class="form-label">Пароль</label>
                                <input style="border-radius: 8px !important;" class="box_shadow form-control @error('password') is-invalid @enderror" type="password" required="" name="password" id="password" placeholder="Введите ваш пароль">
                            </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input box_shadow" id="checkbox-signin" checked>
                                    <label class="form-check-label" for="checkbox-signin">Запомнить меня</label>
                                </div>
                            </div>

                            <div class="mb-3 d-grid text-center">
                                <button class="btn btn-primary box_shadow" type="submit"> Авторизоваться </button>
                            </div>
                        </form>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p> <a href="{{ route('password.request') }}" class="text-muted ms-1 color_black"><i class="fa fa-lock me-1 color_black"></i>Забыли пароль?</a></p>
                        {{--                            <p class="text-muted color_black">Don't have an account? <a href="" class="text-dark ms-1"><b>Sign Up</b></a></p>--}}
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
@endsection
