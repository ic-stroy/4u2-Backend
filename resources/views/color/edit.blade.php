@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <link rel="stylesheet" href="{{asset('assets/css/color-picker.css')}}">
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p class="text-muted font-14">
                {{translate('Color list edit')}}
            </p>
            <form action="{{route('color.update', $color->id)}}" class="parsley-examples" method="POST">
                @csrf
                @method("PUT")
                <div class="mb-3">
                    <label class="form-label">{{translate('Name')}}</label>
                    <input type="text" name="name" class="form-control" required value="{{$color->name??''}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{translate('Code')}}</label>
                    <div id="colorPicker">
                        <div>
                            <label for="colorInput">Select a color:</label>
                            <input type="color" id="colorInput">
                        </div>
                        <div id="selectedColor"></div>
                    </div>
                    <input type="hidden" id="color_code" name="code" value="{{$color->code??''}}">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        const colorInput = document.getElementById('colorInput');
        const selectedColor = document.getElementById('selectedColor');
        let color_code = document.getElementById('color_code')
        let color_code_base = "{{$color->code??''}}"
        document.addEventListener('DOMContentLoaded', function () {
            colorInput.addEventListener('input', function () {
                color_code.value = colorInput.value
                const color = colorInput.value;
                selectedColor.style.backgroundColor = color;
                selectedColor.textContent = `Selected Color: ${color}`;
            });
        });
        if(color_code_base != ''){
            selectedColor.style.backgroundColor = color_code_base;
            selectedColor.textContent = `Selected Color: ${color_code_base}`;
        }
    </script>
@endsection
