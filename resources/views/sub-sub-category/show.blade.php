@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Sub category lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('subcategory.create')}}">{{translate('Create')}}</a>
            </div>
            <table class="table dt-responsive nowrap table_show">
                <thead>
                <tr>
                    <th>{{translate('Attributes')}}</th>
                    <th>{{translate('Informations')}}</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{{translate('Name')}}</th>
                        <td>{{$model->name??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Category')}}</th>
                        <td>
                            @if($model->sub_category)
                                @if($model->sub_category->category)
                                    {{$model->sub_category->category->name?$model->sub_category->category->name:''}}
                                @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{translate('Sub category')}}</th>
                        <td>
                            @if($model->sub_category)
                                {{$model->sub_category->name?$model->sub_category->name:''}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{translate('Updated at')}}</th>
                        <td>{{$model->updated_at??''}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
