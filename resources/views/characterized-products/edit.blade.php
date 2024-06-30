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
                {{translate('Characterized products list edit')}}
            </p>
            <form action="{{route('characterizedProducts.update', $characterized_product->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label" for="product_id">{{translate('Product')}}</label>
                        <input name="product_id" type="hidden" class="form-control" required value="{{$product->id}}">
                        <input class="form-control" readonly id="product_id" value="{{$product->name!='no'?$product->name:''}}">
                    </div>
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Sum')}}</label>
                        @if($characterized_product->sum)
                            <input name="sum" class="form-control" required id="sum" value="{{$characterized_product->sum}}">
                        @elseif($characterized_product->product)
                            @if($characterized_product->product->sum)
                                <input name="sum" class="form-control" required id="sum" value="{{$characterized_product->product->sum}}">
                            @else
                                <input name="sum" class="form-control" required id="sum" value="">
                            @endif
                        @else
                            <input name="sum" class="form-control" required id="sum" value="">
                        @endif
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Category')}}</label>
                        <input name="category_id" type="hidden" class="form-control" required value="{{$current_category->id}}">
                        <input class="form-control" readonly id="category_id" value="{{$current_category!='no'?$current_category->name:''}}">
                    </div>
                    <div style="width: 45%">
                        @if(!$current_category->sizes->isEmpty())
                            <label class="form-label">{{translate('Sizes')}}</label>
                            <select name="size_id" @if($characterized_product->size) required @endif class="form-control" id="size_types">
                                @foreach($sizes as $size_)
                                    <option {{$characterized_product->size_id ==$size_->id?'selected':'' }} value="{{$size_->id}}">{{$size_->name}} {{translate('size')}}</option>
                                @endforeach
                            </select>
                        @else
                            <div class="d-flex">
                                <label class="form-label me-1">{{translate('Add size')}}</label>
                                <a class="btn btn-success me-1" onclick="showSizeInput()">+</a>
                                <a class="btn btn-danger" onclick="hideSizeInput()">-</a>
                            </div>
                            <input type="text" name="size_name" @if($characterized_product->size) required @endif id="input_size_id" class="form-control @if(!$characterized_product->size) d-none @endif" value="{{$characterized_product->size?$characterized_product->size->name:''}}">
                        @endif
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        @if(!$current_category->sizes->isEmpty())
                            <label class="form-label">{{translate('Color')}}</label>
                            <select name="color_id" @if($characterized_product->color) required @endif class="form-control" id="colors_id">
                                <option value="">{{translate('Choose product color')}}</option>
                                @foreach($colors as $color)
                                    <option @if($color->id == $characterized_product->color_id) selected @endif value="{{$color->id}}" style="background-color: {{$color->code}};">{{$color->name}}</option>
                                @endforeach
                            </select>
                        @else
                            <div class="d-flex">
                                <label class="form-label me-1">{{translate('Add color')}}</label>
                                <a class="btn btn-success me-1" onclick="showColorInput()">+</a>
                                <a class="btn btn-danger" onclick="hideColorInput()">-</a>
                            </div>
                            <select name="color_id" @if($characterized_product->color) required @endif class="form-control @if(!$characterized_product->color) d-none @endif" id="select_colors_id">
                                <option value="">{{translate('Choose product color')}}</option>
                                @foreach($colors as $color)
                                    <option @if($color->id == $characterized_product->color_id) selected @endif value="{{$color->id}}" style="background-color: {{$color->code}}; color:{{strtolower($color->name)=='white'?'black':'white'}}">{{$color->name}}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Count')}}</label>
                        <input type="number" name="count" required class="form-control" value="{{$characterized_product->count}}"/>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Update')}}</button>
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
