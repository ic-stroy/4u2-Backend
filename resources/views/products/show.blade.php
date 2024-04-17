@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    @php
        if(isset($model->subSubCategory->id)){
            $current_category = $model->subSubCategory->name;
        }elseif(isset($model->subCategory->id)){
            $current_category = $model->subCategory->name;
        }elseif(isset($model->category->id)){
            $current_category = $model->category->name;
        }
    @endphp
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Products lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('product.create')}}">{{translate('Create')}}</a>
            </div>
            <table id="datatable-buttons" class="table dt-responsive table_show">
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
                        <th>{{translate('Company')}}</th>
                        <td>{{$model->company??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Current category')}}</th>
                        <td>@if(isset($current_category)){{ $current_category }}@endif</td>
                    </tr>
                    <tr>
                        <th>{{translate('Status')}}</th>
                        <td>{{$model->status == 1?translate('Active'):translate('No active') }}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Sum')}}</th>
                        <td>{{$model->sum??'' }}</td>
                    </tr>
                    <tr>
                        <th>{{translate('image')}}</th>
                        <td>
                            @if(isset($model->images))
                                @php
                                    $images = json_decode($model->images);
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
                        <th>{{translate('Description')}}</th>
                        <td style="word-wrap: break-word">{!! $model->description??'' !!}</td>
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
        .dtr-details{
            width: 100% !important;
        }
        table.dataTable.nowrap td
    </style>
@endsection
