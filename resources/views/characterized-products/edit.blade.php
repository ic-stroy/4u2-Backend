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
                {{__('Characterized products list edit')}}
            </p>
            <form action="{{route('characterizedProducts.update', $characterized_product->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label" for="product_id">{{__('Product')}}</label>
                        <input name="product_id" type="hidden" class="form-control" required value="{{$product->id}}">
                        <input class="form-control" readonly id="product_id" value="{{$product->name!='no'?$product->name:''}}">
                    </div>
                    <div style="width: 45%">
                        <label class="form-label">{{__('Sum')}}</label>
                        @if(isset($characterized_product->sum))
                            <input name="sum" class="form-control" required id="sum" value="{{$characterized_product->sum}}">
                        @elseif(isset($characterized_product->product->sum))
                            <input name="sum" class="form-control" required id="sum" value="{{$characterized_product->product->sum}}">
                        @else
                            <input name="sum" class="form-control" required id="sum" value="">
                        @endif
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label">{{__('Category')}}</label>
                        <input name="category_id" type="hidden" class="form-control" required value="{{$current_category->id}}">
                        <input class="form-control" readonly id="category_id" value="{{$current_category!='no'?$current_category->name:''}}">
                    </div>
                    <div style="width: 45%">
                        <label class="form-label">{{__('Sizes')}}</label>
                        <select name="size_id" class="form-control" required id="size_types">
                            @foreach($sizes as $size_)
                                <option {{$characterized_product->size_id ==$size_->id?'selected':'' }} value="{{$size_->id}}">{{$size_->name}} {{__('size')}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label">{{__('Color')}}</label>
                        <select name="color_id" required class="form-control" id="colors_id">
                            <option value="">{{__('Choose product color')}}</option>
                            @foreach($colors as $color)
                                <option @if($color->id == $characterized_product->color_id) selected @endif value="{{$color->id}}" style="background-color: {{$color->code}};">{{$color->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="width: 45%">
                        <label class="form-label">{{__('Count')}}</label>
                        <input type="number" name="count" required class="form-control" value="{{$characterized_product->count}}"/>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
