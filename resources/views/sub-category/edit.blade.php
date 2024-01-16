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
                {{__('Sub category list edit')}}
            </p>
            <form action="{{route('subcategory.update', $subcategory->id)}}" class="parsley-examples" method="POST">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Name')}}</label>
                        <select name="name" class="form-control" required>
                            @foreach($types_sex as $type_sex)
                                <option value="{{$type_sex}}" {{$type_sex == $subcategory->name?'selected':''}}>{{$type_sex}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Category')}}</label>
                        <select name="category_id" class="form-control" required>
                            @if(isset($subcategory->category->id))
                                @foreach($categories as $category)
                                    <option {{$subcategory->category->id == $category->id?'selected':''}} value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            @endif
                        </select>
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
