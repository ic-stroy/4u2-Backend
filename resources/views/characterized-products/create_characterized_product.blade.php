@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
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
                {{translate('Characterized ')}} {{isset($product->name)?$product->name :''}} {{translate(' product create')}}
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
                        <label class="form-label">{{translate('Sizes')}}</label>
                        <select name="size_id" required class="form-control" id="size_types">
                            @foreach($current_category->sizes as $size)
                                <option value="{{$size->id}}">{{$size->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label">{{translate('Color')}}</label>
                        <select name="color_id" class="form-control" required id="colors_id">
                            <option value="">{{translate('Choose product color')}}</option>
                            @foreach($colors as $color)
                                <option value="{{$color->id}}" style="background-color: {{$color->code}}; color:{{strtolower($color->name)=='white'?'black':'white'}}">{{$color->name}}</option>
                            @endforeach
                        </select>
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
@endsection
