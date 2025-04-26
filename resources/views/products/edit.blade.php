@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    @php
        if($product->images){
            $images = json_decode($product->images);
        }else{
            $images = [];
        }
    @endphp
    <style>
        .delete_product_func{
            height: 20px;
            background-color: transparent;
            border: 0px;
            color: silver;
        }
        .product_image img{

        }
        .product_image{
            margin-right: 4px;
            transition: 0.4s;
            padding:10px;
            border-radius: 4px;
        }
        .product_image:hover{
            border: lightgrey;
            transform: scale(1.02);
            background-color: rgba(0, 0, 0, 0.1);
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
                {{translate('Products list edit')}}
            </p>
            <form action="{{route('product.update', $product->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Name')}}</label>
                        <input type="text" name="name" class="form-control" required value="{{$product->name}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Company')}}</label>
                        <input type="text" name="company" class="form-control" value="{{$product->company}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Category')}}</label>
                        <select name="category_id" class="form-control" id="category_id">
                            @foreach($categories as $category)
                                <option value="{{$category->id}}"
                                    @if($category != 'no')
                                        {{$current_category->id == $category->id?'selected':'' }}
                                    @endif
                                >
                                    {{$category->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Sum')}}</label>
                        <input type="number" class="form-control" name="sum" required value="{{$product->sum??''}}">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6 display-none" id="subcategory_exists">
                        <label class="form-label">{{translate('Sub category')}}</label>
                        <select name="subcategory_id" class="form-control" id="subcategory_id">
                            @if(!$current_category->subcategory->isEmpty())
                                @foreach($current_category->subcategory as $subcategory)
                                    <option value="{{$subcategory->id}}" {{$subcategory->id == $current_sub_category_id?'selected':''}}>{{$subcategory->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3 col-6 display-none" id="subsubcategory_exists">
                        <label class="form-label">{{translate('Sub Sub category')}}</label>
                        <select name="subsubcategory_id" class="form-control" id="subsubcategory_id">
                            @if($category_product->sub_category)
                                @if(!$category_product->sub_category->subsubcategory->isEmpty())
                                    @foreach($category_product->sub_category->subsubcategory as $subsubcategory)
                                        <option value="{{$subsubcategory->id}}" {{$subsubcategory->id == $current_sub_sub_category_id?'selected':''}}>{{$subsubcategory->name}}</option>
                                    @endforeach
                                @endif
                            @endif
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        @foreach($images as $image)
                            @php
                                $avatar_main = storage_path('app/public/products/'.$image);
                            @endphp
                            @if(file_exists(storage_path('app/public/products/'.$image)))
                                <div class="col-2 mb-3 product_image">
                                    <div class="d-flex justify-content-between">
                                        <img src="{{asset('storage/products/'.$image)}}" alt="" height="200px">
                                        <button onclick="deleteProductImageFunc(event, this, `{{$image}}`)" class="delete_product_func">X</button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Status')}}</label>
                        <select name="status" class="form-control" id="status_id">
                            <option value="0" {{$product->status == 0?'selected':''}}>{{translate('No active')}}</option>
                            <option value="1" {{$product->status == 1?'selected':''}}>{{translate('Active')}}</option>
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Images')}}</label>
                        <input type="file" name="images[]" class="form-control" value="{{old('images')}}" multiple/>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{translate('Description in uzbek')}}</label>
                    <textarea class="form-control" name="description_uz" id="description_uz" cols="20" rows="10">
                        {{$product->productDescriptionUz?$product->productDescriptionUz->name:''}}
                    </textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{translate('Description in english')}}</label>
                    <textarea class="form-control" name="description_en" id="description_en" cols="20" rows="10">
                        {{$product->productDescriptionRu?$product->productDescriptionEn->name:''}}
                    </textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{translate('Description in russian')}}</label>
                    <textarea class="form-control" name="description_ru" id="description_ru" cols="20" rows="10">
                        {{$product->productDescriptionEn?$product->productDescriptionRu->name:''}}
                    </textarea>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset('assets/js/ckeditor/ckeditor.js')}}"></script>
    <script>
        ClassicEditor
            .create( document.querySelector('#description_uz'))
            .catch( error => {
                console.error( error );
            } );
        ClassicEditor
            .create( document.querySelector('#description_en'))
            .catch( error => {
                console.error( error );
            } );
        ClassicEditor
            .create( document.querySelector('#description_ru'))
            .catch( error => {
                console.error( error );
            } );
    </script>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        let product_image = document.getElementsByClassName('product_image')
        let delete_product_func = document.getElementsByClassName('delete_product_func')
        let deleted_text = "{{translate('Product image was deleted')}}"
        function deleteProductImageFunc(e, this_element, image_name) {
            e.preventDefault()
            $.ajax({
                url: '/api/delete-product',
                method: 'POST',
                dataType: 'json',
                data: {
                    id:"{{$product->id}}",
                    product_name: image_name
                },
                success: function(data){
                    if(data.status == true){
                        toastr.success(deleted_text)
                    }
                    let this_element_image = this_element.parentElement.parentElement
                    if(!this_element_image.classList.contains('display-none')){
                        this_element_image.classList.add('display-none')
                    }
                }
            });
        }
    </script>
    <script>

        let size_type = document.getElementById('size_type')
        let sizes_leg = document.getElementById('sizes_leg')

        let subcategory_exists = document.getElementById('subcategory_exists')
        let subsubcategory_exists = document.getElementById('subsubcategory_exists')

        let is_category = "{{$is_category}}"

        let sub_category = {}

        let category_id = document.getElementById('category_id')
        let subcategory_id = document.getElementById('subcategory_id')
        let subsubcategory_id = document.getElementById('subsubcategory_id')

        switch (is_category) {
            case "2":
                if(subcategory_exists.classList.contains('display-none')){
                    subcategory_exists.classList.remove('display-none')
                }
                break;
            case "3":
                if(subcategory_exists.classList.contains('display-none')){
                    subcategory_exists.classList.remove('display-none')
                }
                if(subsubcategory_exists.classList.contains('display-none')){
                    subsubcategory_exists.classList.remove('display-none')
                }
                break;
        }
        function addOption(item, index){
            let option = document.createElement('option')
            option.value = item.id
            option.text = item.name
            subcategory_id.add(option)
        }
        category_id.addEventListener('change', function () {
            subcategory_id.innerHTML = ""
            subsubcategory_id.innerHTML = ""
            $(document).ready(function () {
                $.ajax({
                    url:`/../api/subcategory/${category_id.value}`,
                    type:'GET',
                    success: function (data) {
                        if(data.status == true){
                            if(subcategory_exists.classList.contains('display-none')){
                                subcategory_exists.classList.remove('display-none')
                            }
                            if(!subsubcategory_exists.classList.contains('display-none')){
                                subsubcategory_exists.classList.add('display-none')
                            }
                        }else{
                            if(!subcategory_exists.classList.contains('display-none')){
                                subcategory_exists.classList.add('display-none')
                            }
                            if(!subsubcategory_exists.classList.contains('display-none')){
                                subsubcategory_exists.classList.add('display-none')
                            }
                        }
                        let disabled_option = document.createElement('option')
                        disabled_option.text = "{{translate('Select sub category')}}"
                        disabled_option.selected = true
                        disabled_option.disabled = true
                        subcategory_id.add(disabled_option)
                        data.data.forEach(addOption)
                    }
                })
            })
        })
        function addSubOption(item, index){
            let option = document.createElement('option')
            option.value = item.id
            option.text = item.name
            subsubcategory_id.add(option)
        }
        subcategory_id.addEventListener('change', function () {
            subsubcategory_id.innerHTML = ""
            $(document).ready(function () {
                $.ajax({
                    url:`/../api/subcategory/${subcategory_id.value}`,
                    type:'GET',
                    success: function (data) {
                        if(data.status == true){
                            if(subsubcategory_exists.classList.contains('display-none')){
                                subsubcategory_exists.classList.remove('display-none')
                            }
                        }else{
                            if(!subsubcategory_exists.classList.contains('display-none')){
                                subsubcategory_exists.classList.add('display-none')
                            }
                        }
                        let disabled_sub_option = document.createElement('option')
                        disabled_sub_option.text = "{{translate('Select sub sub category')}}"
                        disabled_sub_option.selected = true
                        disabled_sub_option.disabled = true
                        subsubcategory_id.add(disabled_sub_option)
                        data.data.forEach(addSubOption)
                    }
                })
            })
        })
    </script>
@endsection
