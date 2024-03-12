@extends('layout.layout')

@section('title')
    {{__('Color create')}}
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
                {{__('Car color list create')}}
            </p>
            <form action="{{route('color.store')}}" class="parsley-examples" method="POST">
                @csrf
                @method("POST")
                <div class="mb-3">
                    <label class="form-label">{{__('Name')}}</label>
                    <input type="text" name="name" class="form-control" required value="{{old('name')}}"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('Code')}}</label>
                    <div id="colorPicker">
                        <div>
                            <label for="colorInput">Select a color:</label>
                            <input type="color" id="colorInput">
                        </div>
                        <div id="selectedColor"></div>
                    </div>
                    <input type="hidden" id="color_code" name="code" value="">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Create')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        let color_code = document.getElementById('color_code')
        document.addEventListener('DOMContentLoaded', function () {
            const colorInput = document.getElementById('colorInput');
            const selectedColor = document.getElementById('selectedColor');

            colorInput.addEventListener('input', function () {
                color_code.value = colorInput.value
                const color = colorInput.value;
                selectedColor.style.backgroundColor = color;
                selectedColor.textContent = `Selected Color: ${color}`;
            });
        });
    </script>

@endsection
