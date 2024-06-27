@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Products lists')}}</h4>
            <table class="table dt-responsive nowrap table_show">
                <thead>
                    <tr>
                        <th>{{translate('Attributes')}}</th>
                        <th>{{translate('Informations')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{{translate('Product')}}</th>
                        <td>{{$model->product?$model->product->name:''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Current category')}}</th>
                        <td>@if(!empty($category_array)){{ implode(', ', $category_array)}}@endif</td>
                    </tr>
                    <tr>
                        <th>{{translate('Size')}}</th>
                        <td>
                            @if($model->size){{ $model->size->name?$model->size->name:'' }}@endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{translate('Colors')}}</th>
                        <td class="d-flex justify-content-between">
                            @if($model->color)
                                <div class="color_content" style=" background-color: {{$model->color->code??''}};">{{$model->color->name?$model->color->name:''}}</div>
                            @else
                                <div>{{translate('No color')}}</div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{translate('Count')}}</th>
                        <td>{{$model->count??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('image')}}</th>
                        <td>
                            @if($model->product)
                                @if($model->product->images)
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
                            @else
                                <div>
                                    <img src="{{asset('icon/no_photo.jpg')}}" alt=""  height="100px">
                                </div>
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
