@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p class="text-muted font-14">
                {{translate('Discount list create')}}
            </p>
            <form action="{{route('discount.store')}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("POST")
                <div class="row">
                    <div class="mb-3 col-4">
                        <label class="form-label">{{translate('Discount percent')}}</label>
                        <input type="number" name="percent" class="form-control" min="0" max="100" required/>
                    </div>
                    <div class="mb-3 col-4">
                        <label class="form-label">{{translate('Category')}}</label>
                        <select name="category_id" class="form-control" id="category_id">
                            <option value="" selected>{{translate('All category')}}</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}} {{$category->category?$category->category->name:''}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-4 display-none" id="subcategory_exists">
                        <label class="form-label">{{translate('Sub category')}}</label>
                        <select name="subcategory_id" class="form-control" id="subcategory_id"></select>
                    </div>
                    <div class="mb-3 col-4 display-none" id="subsubcategory_exists">
                        <label class="form-label">{{translate('Sub sub category')}}</label>
                        <select name="subsubcategory_id" class="form-control" id="subsubcategory_id"></select>
                    </div>
                    <div class="mb-3 col-4 display-none" id="product_exists">
                        <label class="form-label">{{translate('Products')}}</label>
                        <select name="product_id" class="form-control" id="product_id"></select>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Start date')}}</label>
                        <input type="date" name="start_date" class="form-control" required value="{{old('start_date')}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('End date')}}</label>
                        <input type="date" name="end_date" class="form-control" required value="{{old('end_date')}}"/>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Create')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        let discount_category_id = ""
        let discount_subcategory_id = ""
        let discount_subsubcategory_id = ""
        let discount_product_id = ""
        let discount_warehouse_id = ""
        let discount_percent_value = ""
        let text_select_sub_category = "{{translate('Select sub category')}}"
        let text_all_subcategory_products = "{{translate('All subcategories`s products')}}"
        let text_all_subsubcategory_products = "{{translate('All sub subcategories`s products')}}"
        let text_all_products = "{{translate('All products')}}"
        let text_select_product = "{{translate('Select product')}}"
    </script>

    <script src="{{asset('assets/js/discount.js')}}"></script>
@endsection
