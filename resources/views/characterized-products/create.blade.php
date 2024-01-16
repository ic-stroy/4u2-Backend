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
                {{__('Characterized Products list create')}}
            </p>
            <form action="{{route('characterizedProducts.store')}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("POST")
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label">{{__('Product')}}</label>
                        <select name="product_id" class="form-control" id="product_id" required>
                            <option value="">{{__('Choose product')}}</option>
                            @foreach($products as $product)
                                <option value="{{$product->id}}">{{$product->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="width: 45%">
                        <label class="form-label">{{__('Sum')}}</label>
                        <input name="sum" class="form-control" id="sum" required>
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label">{{__('Category')}}</label>
                        <input class="form-control" type="text" readonly id="category_id">
                    </div>
                    <div style="width: 45%">
                        <label class="form-label">{{__('Sizes')}}</label>
                        <select name="size_id" class="form-control" id="size_types" required></select>
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 45%">
                        <label class="form-label">{{__('Color')}}</label>
                        <select name="color_id" class="form-control" id="colors_id" required>
                            <option value="">{{__('Choose product color')}}</option>
                            @foreach($colors as $color)
                                <option value="{{$color->id}}" style="background-color: {{$color->code}}; color:{{strtolower($color->name)=='white'?'black':'white'}}">{{$color->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="width: 45%">
                        <label class="form-label">{{__('Count')}}</label>
                        <input type="number" name="count" class="form-control" required value="{{old('count')}}"/>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Create')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        let sub_category = {}
        let category_id = document.getElementById('category_id')
        let product_id = document.getElementById('product_id')
        let size_types = document.getElementById('size_types')
        let sum = document.getElementById('sum')
        function addSize(item, index){
            let size_option = document.createElement('option')
            size_option.value = item.id
            size_option.text = item.name
            size_types.add(size_option)
        }
        product_id.addEventListener('change', function () {
            category_id.value = ""
            size_types.innerHTML = ""
            $(document).ready(function () {
                $.ajax({
                    url:`/../api/get-categories-by-product/${product_id.value}`,
                    type:'GET',
                    success: function (data) {
                        category_id.value = data.category
                        sum.value = data.sum
                        data.data.forEach(addSize)
                    }
                })
            })
        })
    </script>
@endsection
