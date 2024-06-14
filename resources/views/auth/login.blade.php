@extends('layout.app')

@section('content')
    <link href="https://fonts.cdnfonts.com/css/gilroy-bold" rel="stylesheet">
    <style>
        .white_color{
            color: white;
        }
        .box_shadow{
            box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25), 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
        }
        .login_page{
            height:100vh;
        }
        .correct_img img {
            height: 38px;
            width: 47%;
        }
        .login_page_image{
            background-image: url("{{asset('/assets/images/login_background.png')}}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100%;
            width: 100%;
        }
        .login_page input{
            border-radius: 8px !important;
            background-color: #F4F4F6;
            border: 0px;
        }
        .login_page input:focus{
            background-color: #F4F4F6;
        }
        .login_button{
            background-color: #3C7CFB;
            padding: 10px 0px;
            width: 50%;
            text-align: center;
            border-radius: 12px;
            border: 0px;
            color: white;
        }

        .login_modal{
            width: 334px;
        }
        .login_modal_background{
            width: 424px;
            height: 424px;
            display: flex;
            justify-content: center;
            background-image: url("{{asset('assets/images/border_back.svg')}}");
            background-size: contain;
            background-repeat: no-repeat;
            font-family: Arial, Helvetica, sans-serif !important;
        }
        .login_modal{
            color: white;
            padding: 37px 0px;
        }
        .login_modal h3{
            font-weight: 700;
            font-size: 28px;
            line-height: 46px;
        }
        .login_modal label, .login_modal a{
            font-weight: 400;
            font-size: 14px;
            line-height: 20px;
        }
        .login_modal img{
            border-radius: 12px;
            opacity: 0.74;
        }
        .color_white{
            color: white !important;
        }
        .sign_in_button{
            background-color: #003465;
            border: 0px;
            border-radius: 8px !important;
            font-weight: 700;
            width: 100%;
            color: white;
            height: 38px;
        }
        .form-check-input:checked{
            background-color: #71B6F9 !important;
        }
        .eye_icon{
            padding-right: 14px;
            color: #BCBEC0;
            margin-top: -28px;
        }
    </style>
    <div class="login_page">
        <div class="login_page_image d-flex flex-column justify-content-center align-items-center">
            @if (session('status'))
                <div class="alert alert-danger">
                    {{session('status')}}
                </div>
            @endif
            <div class="login_modal_background">
                <div class="login_modal">
                    <img src="{{asset('assets/images/logo.svg')}}" alt="" height="40px">
                    <div class="text-start">
                        <h3 class="mt-0 color_white">Войти</h3>
                    </div>
                    <form action="{{route('login')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="emailaddress" class="form-label">E-mail</label>
                            <input class="form-control @error('email') is-invalid @enderror" type="email" id="emailaddress" name="email" required="" placeholder="username@gmail.com">
                        </div>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div class="mb-1">
                            <label for="password" class="form-label">Пароль</label>
                            <input class="form-control @error('password') is-invalid @enderror" type="password" required="" name="password" id="password" placeholder="Password">
                            <div class="d-flex justify-content-between">
                                <div></div>
                                <div class="eye_icon hide_password"><i id="hide_password_icon" class="fa fa-eye"></i></div>
                                <div class="eye_icon show_password d-none"><i id="show_password_icon" class="fa fa-eye-slash"></i></div>
                            </div>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div class="mb-3 d-flex justify-content-start">
                            <a href="{{ route('password.request') }}" class="text-muted ms-1 color_white">Забыли пароль?</a>
                        </div>
                        <button class="sign_in_button">Войти</button>
                    </form>
                </div>

            </div> <!-- end card-body -->
            <!-- end card -->
        </div> <!-- end col -->
    </div>
    <script>
        let hide_password = document.getElementsByClassName('hide_password')
        let password = document.getElementById('password')
        let show_password = document.getElementsByClassName('show_password')
        let hide_password_icon = document.getElementById('hide_password_icon')
        let show_password_icon = document.getElementById('show_password_icon')
        hide_password_icon.addEventListener('click', function () {
            if(!hide_password[0].classList.contains('d-none')){
                hide_password[0].classList.add('d-none')
            }
            if(show_password[0].classList.contains('d-none')){
                show_password[0].classList.remove('d-none')
            }
            if(password.getAttribute('type') && password.getAttribute('type') == 'password'){
                password.setAttribute('type', 'text')
            }
        })
        show_password_icon.addEventListener('click', function () {
            if(!show_password[0].classList.contains('d-none')){
                show_password[0].classList.add('d-none')
            }
            if(hide_password[0].classList.contains('d-none')){
                hide_password[0].classList.remove('d-none')
            }
            if(password.getAttribute('type') && password.getAttribute('type') == 'text'){
                password.setAttribute('type', 'password')
            }
        })
    </script>
@endsection
