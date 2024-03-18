@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="mt-0 header-title">{{__('Products lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('product.create')}}">{{__('Create')}}</a>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-pills navtab-bg nav-justified">
                @php
                    $i = 0;
                @endphp
                @foreach($categories as $category)
                    @php
                        $i++;
                    @endphp
                    <li class="nav-item">
                        <a href="#category_{{$category->id}}" data-bs-toggle="tab" aria-expanded="{{$i == 1?'true':'false'}}" class="nav-link {{$i == 1?'active':''}}">
                            {{$category->name??''}}
                            @if(count($all_products[$category->id]) > 0)
                                <span class="badge bg-danger">{{count($all_products[$category->id])}}</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                @php
                    $j = 0;
                @endphp
                @foreach($categories as $category)
                    @php
                        $j++;
                    @endphp
                    <div class="tab-pane{{$j == 1?' show active':''}}" id="category_{{$category->id}}">
                        <table class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Current category')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Images')}}</th>
                                <th>{{__('Updated_at')}}</th>
                                <th class="text-center">{{__('Functions')}}</th>
                            </tr>
                            </thead>
                            <tbody class="table_body">
                            @php
                                $i = 0
                            @endphp
                            @foreach($all_products[$category->id] as $product)
                                @php
                                    $i++;
                                @endphp
                                <tr>
                                    <th scope="row">
                                        <a class="show_page" href="{{route('characterizedProducts.category.characterized_product', $product['product']->id)}}">{{$i}}</a>
                                    </th>
                                    <td>
                                        <a class="show_page" href="{{route('characterizedProducts.category.characterized_product', $product['product']->id)}}">
                                            @if(isset($product['product']->name))
                                                @if(strlen($product['product']->name)>34)
                                                    {{ substr($product['product']->name, 0, 34) }}...
                                                @else
                                                    {{$product['product']->name}}
                                                @endif
                                            @else
                                                <div class="no_text"></div>
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a class="show_page" href="{{route('characterizedProducts.category.characterized_product', $product['product']->id)}}">
                                            @if($product['sub_sub_category_'] != '' || $product['sub_category_'] != '' || $product['category_'] != '')
                                                {{ implode(', ', [$product['category_'], $product['sub_category_'], $product['sub_sub_category_']])}}
                                            @else
                                                <div class="no_text"></div>
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a class="show_page" href="{{route('characterizedProducts.category.characterized_product', $product['product']->id)}}">
                                            {{$product['product']->status == 1?__('Active'):__('No active') }}
                                        </a>
                                    </td>
                                    <td>
                                        <a class="show_page_color" href="{{route('characterizedProducts.category.characterized_product', $product['product']->id)}}">
                                            <div class="d-flex">
                                                @if(isset($product['product']->images))
                                                    @php
                                                        $images = json_decode($product['product']->images);
                                                        $is_image = 0;
                                                    @endphp
                                                    @foreach($images as $image)
                                                        @php
                                                            $avatar_main = storage_path('app/public/products/'.$image);
                                                        @endphp
                                                        @if(file_exists($avatar_main))
                                                            @php $is_image = 1; @endphp
                                                            <div style="margin-right: 2px">
                                                                <img src="{{ asset('storage/products/'.$image) }}" alt="" height="40px">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                    @if($is_image == 0)
                                                        <img src="{{asset('icon/no_photo.jpg')}}" alt=""  height="40px">
                                                    @endif
                                                @else <img src="{{asset('icon/no_photo.jpg')}}" alt=""  height="40px"> @endif
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <a class="show_page" href="{{route('characterizedProducts.category.characterized_product', $product['product']->id)}}">
                                            @if(isset($product['product']->updated_at)){{ $product['product']->updated_at }}@else <div class="no_text"></div> @endif
                                        </a>
                                    </td>
                                    <td class="function_column">
                                        <div class="d-flex justify-content-center">
                                            <a class="form_functions btn btn-info" href="{{route('product.edit', $product['product']->id)}}"><i class="fe-edit-2"></i></a>
                                            <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('product.destroy', $product['product']->id)}}"><i class="fe-trash-2"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
