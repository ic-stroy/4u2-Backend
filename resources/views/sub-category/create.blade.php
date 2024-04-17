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
                {{translate('Sub category list create')}}
            </p>
            <form action="{{route('subcategory.store')}}" class="parsley-examples" method="POST">
                @csrf
                @method("POST")
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Select sex type')}}</label>
                        <select name="name" class="form-control" required>
                            @foreach($types_sex as $type_sex)
                                <option value="{{$type_sex}}">{{$type_sex}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('category')}}</label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
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
