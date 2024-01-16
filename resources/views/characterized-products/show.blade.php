@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Products lists')}}</h4>
            <table id="datatable-buttons" class="table dt-responsive nowrap table_show">
                <thead>
                <tr>
                    <th>{{__('Attributes')}}</th>
                    <th>{{__('Informations')}}</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{{__('Product')}}</th>
                        <td>{{$model->product?$model->product->name:''}}</td>
                    </tr>
                    <tr>
                        <th>{{__('Size')}}</th>
                        <td>
                            @if(isset($model->size->name)){{ $model->size->name }}@endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Colors')}}</th>
                        <td class="d-flex justify-content-between">
                            @if(isset($model->color->name))
                                <div class="color_content" style=" background-color: {{$model->color->code??''}};">{{$model->color->name}}</div>
                            @else
                                <div>{{__('No color')}}</div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Count')}}</th>
                        <td>{{$model->count??''}}</td>
                    </tr>
                    <tr>
                        <th>{{__('image')}}</th>
                        <td>
                            @if(isset($model->product->images))
                                @php
                                    $images = json_decode($model->product->images);
                                    $is_image = 0;
                                @endphp
                                <div class="row">
                                    @foreach($images as $image)
                                        @php
                                            $avatar_main = storage_path('app/public/products/'.$image);
                                        @endphp
                                        @if(file_exists($avatar_main))
                                            @php($is_image = 1)
                                            <div class="col-4 mb-3">
                                                <img src="{{asset('storage/products/'.$image)}}" alt="">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                @if($is_image == 0)
                                    <div>
                                        <img src="{{asset('icon/no_photo.jpg')}}" alt=""  height="100px">
                                    </div>
                                @endif
                            @else
                                <div>
                                    <img src="{{asset('icon/no_photo.jpg')}}" alt=""  height="100px">
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Updated at')}}</th>
                        <td>{{$model->updated_at??''}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <style>
        .color_content{
            height: 40px;
            width: 64px;
            border-radius: 4px;
            border: solid 1px;
            display: flex;
            align-items: center;
            justify-content: center
        }
    </style>
@endsection
