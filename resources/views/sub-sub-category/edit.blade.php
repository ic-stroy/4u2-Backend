@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <style>
        .display-none{
            display: none;
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
                {{translate('Sub Sub category list edit')}}
            </p>
            <form action="{{route('subsubcategory.update', $subsubcategory->id)}}" class="parsley-examples" method="POST">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Sub Category')}}</label>
                        <select id="category_id" class="form-control" required>
                            <option value="" selected disabled>{{translate('Select category')}}</option>
                            @foreach($categories as $category)
                                <option @if(isset($subsubcategory->sub_category->category)){{$subsubcategory->sub_category->category->id == $category->id?'selected':''}}@endif value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-6 @if(!isset($subsubcategory->sub_category->category->subcategory)) display-none @endif" id="subcategory_exists">
                        <label class="form-label">{{translate('Sub category')}}</label>
                        <select id="subcategory_id" name="subcategory_id" class="form-control" required>
                            @if(isset($subsubcategory->sub_category->category->subcategory))
                                @foreach($subsubcategory->sub_category->category->subcategory as $sub_category)
                                    <option {{$sub_category->id == $subsubcategory->sub_category->id?'selected':''}} value="{{$sub_category->id}}">{{$sub_category->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{translate('Name')}}</label>
                    <input type="text" class="form-control" name="name" value="{{$subsubcategory->name}}">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div><script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        let category_id = document.getElementById('category_id')
        let subcategory_id = document.getElementById('subcategory_id')
        let subcategory_exists = document.getElementById('subcategory_exists')

        function addOption(item, index){
            let option = document.createElement('option')
            option.value = item.id
            option.text = item.name
            subcategory_id.add(option)
        }
        category_id.addEventListener('change', function () {
            subcategory_id.innerHTML = ""
            $(document).ready(function () {
                $.ajax({
                    url:`/../api/subcategory/${category_id.value}`,
                    type:'GET',
                    success: function (data) {
                        console.log(data)
                        if(data.status == true){
                            if(subcategory_exists.classList.contains('display-none')){
                                subcategory_exists.classList.remove('display-none')
                            }
                        }else{
                            if(!subcategory_exists.classList.contains('display-none')){
                                subcategory_exists.classList.add('display-none')
                            }
                        }
                        data.data.forEach(addOption)
                    }
                })
            })
        })
    </script>
@endsection
