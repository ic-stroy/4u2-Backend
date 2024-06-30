@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <style>
        .btn-success, .btn-danger{
            padding: 3px 14px;
            margin: -4px 0px 4px 0px;
        }
    </style>
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
                {{translate('Characterized ')}} {{$product->name?$product->name :''}} {{translate(' product create')}}
            </p>
            <form action="{{route('characterizedProducts.store')}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("POST")
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Product')}}</label>
                        <input name="product_id" value="{{$product->id}}" type="hidden" class="form-control" id="product_id">
                        <input  value="{{$product->name??''}}" class="form-control" readonly>
                    </div>
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Sum')}}</label>
                        <input name="sum" required class="form-control" id="sum" value="{{$product->sum??''}}">
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Category')}}</label>
                        <input name="category_id" value="{{$product->category_id??''}}" type="hidden" class="form-control" id="category_id">
                        <input value="{{$product->getCategory->name??''}}" class="form-control" readonly>
                    </div>
                    <div style="width: 45%">
                        @if(!$current_category->sizes->isEmpty())
                            <label class="form-label">{{translate('Sizes')}}</label>
                            <select name="size_id" required class="form-control" id="size_types">
                                @foreach($current_category->sizes as $size)
                                    <option value="{{$size->id}}">{{$size->name}}</option>
                                @endforeach
                            </select>
                        @else
                            <div class="d-flex">
                                <label class="form-label me-1">{{translate('Add size')}}</label>
                                <a class="btn btn-success me-1" onclick="showSizeInput()">+</a>
                                <a class="btn btn-danger" onclick="hideSizeInput()">-</a>
                            </div>
                            <input type="text" name="size_name" id="input_size_id" class="form-control d-none">
                        @endif
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        @if(!$current_category->sizes->isEmpty())
                            <label class="form-label">{{translate('Color')}}</label>
                            <select name="color_id" class="form-control" @if(in_array($current_category->id, [1, 2])) required @endif id="colors_id">
                                <option value="">{{translate('Choose product color')}}</option>
                                @foreach($colors as $color)
                                    <option value="{{$color->id}}" style="background-color: {{$color->code}}; color:{{strtolower($color->name)=='white'?'black':'white'}}">{{$color->name}}</option>
                                @endforeach
                            </select>
                        @else
                            <div class="d-flex">
                                <label class="form-label me-1">{{translate('Add color')}}</label>
                                <a class="btn btn-success me-1" onclick="showColorInput()">+</a>
                                <a class="btn btn-danger" onclick="hideColorInput()">-</a>
                            </div>
                            <select name="color_id" class="form-control d-none" id="select_colors_id">
                                <option value="">{{translate('Choose product color')}}</option>
                                @foreach($colors as $color)
                                    <option value="{{$color->id}}" style="background-color: {{$color->code}}; color:{{strtolower($color->name)=='white'?'black':'white'}}">{{$color->name}}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Count')}}</label>
                        <input type="number" name="count" class="form-control" required value="{{old('count')}}"/>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Create')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        let input_size_id = document.getElementById('input_size_id')
        let select_colors_id = document.getElementById('select_colors_id')
        function showSizeInput() {
            if(input_size_id.classList.contains('d-none')){
                input_size_id.classList.remove('d-none')
            }
            if(!input_size_id.hasAttribute('required')){
                input_size_id.setAttribute('required', true)
            }
        }
        function hideSizeInput() {
            if(!input_size_id.classList.contains('d-none')){
                input_size_id.classList.add('d-none')
            }
            if(input_size_id.hasAttribute('required')){
                input_size_id.removeAttribute('required')
            }
            input_size_id.value=""
        }
        function showColorInput() {
            if(select_colors_id.classList.contains('d-none')){
                select_colors_id.classList.remove('d-none')
            }
            if(!select_colors_id.hasAttribute('required')){
                select_colors_id.setAttribute('required', true)
            }
        }
        function hideColorInput() {
            if(!select_colors_id.classList.contains('d-none')){
                select_colors_id.classList.add('d-none')
            }
            if(select_colors_id.hasAttribute('required')){
                select_colors_id.removeAttribute('required')
            }
            select_colors_id.value=""
        }
    </script>
@endsection
