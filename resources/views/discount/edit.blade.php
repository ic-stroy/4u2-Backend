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
                {{__('Discount list edit')}}
            </p>
            <form action="{{route('discount.update', $discount->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Discount percent')}}</label>
                        <input type="number" name="percent" value="{{$discount->percent}}" class="form-control" min="0" max="100" required/>
                    </div>
                    <div class="mb-3 col-4">
                        <label class="form-label">{{__('Category')}}</label>
                        <select name="category_id" class="form-control" id="category_id" required>
                            <option value="" selected>{{__('All category')}}</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" {{$category->id == $category_id? 'selected' : ''}}>{{$category->name}} {{$category->category?$category->category->name:''}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-4 display-none" id="subcategory_exists">
                        <label class="form-label">{{__('Sub category')}}</label>
                        <select name="subcategory_id" class="form-control" id="subcategory_id"></select>
                    </div>
                    <div class="mb-3 col-4 display-none" id="subsubcategory_exists">
                        <label class="form-label">{{__('Sub sub category')}}</label>
                        <select name="subsubcategory_id" class="form-control" id="subsubcategory_id"></select>
                    </div>
                    <div class="mb-3 col-4 display-none" id="product_exists">
                        <label class="form-label">{{__('Products')}}</label>
                        <select name="product_id" class="form-control" id="product_id"></select>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Start date')}}</label>
                        <input type="date" name="start_date" class="form-control" required value="{{explode(' ', $discount->start_date)[0]}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('End date')}}</label>
                        <input type="date" name="end_date" class="form-control" required value="{{explode(' ', $discount->end_date)[0]}}"/>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
{{--    @dd($category_id, $subcategory_id, $discount->product_id, $discount)--}}
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        let discount_category_id = "{{$category_id}}"
        let discount_subcategory_id = "{{$subcategory_id}}"
        let discount_subsubcategory_id = "{{$subsubcategory_id}}"
        let discount_product_id = "{{$discount->product_id}}"
        let discount_percent_value = "{{$discount->percent??''}}"
        let text_select_sub_category = "{{__('Select sub category')}}"
        let text_all_subcategory_products = "{{__('All subcategories`s products')}}"
        let text_all_subsubcategory_products = "{{__('All sub subcategories`s products')}}"
        let text_all_products = "{{__('All products')}}"
        let text_select_product = "{{__('Select product')}}"
    </script>
    <script src="{{asset('assets/js/discount.js')}}"></script>
@endsection
