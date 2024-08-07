@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Products lists')}}</h4>
            <div class="dropdown float-end mb-2">
                <a class="form_functions btn btn-success" href="{{route('product.create')}}">{{translate('Create')}}</a>
            </div>
            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
{{--            <table class="table table-striped table-bordered dt-responsive nowrap">--}}
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('Current category')}}</th>
                    <th>{{translate('Sum')}}</th>
                    <th>{{translate('Images')}}</th>
                    <th>{{translate('Updated_at')}}</th>
                    <th class="text-center">{{translate('Functions')}}</th>
                </tr>
                </thead>
                <tbody class="table_body">
                @php
                    $i = 0
                @endphp
                @foreach($products as $product)
                    @php
                        $i++;
                        if(!$product->subSubCategory->isEmpty()){
                            $current_category = $product->subSubCategory->name??'';
                        }elseif(!$product->subCategory->isEmpty()){
                            $current_category = $product->subCategory->name??'';
                        }elseif(!$product->category->isEmpty()){
                            $current_category = $product->category->name??'';
                        }
                    @endphp
                    <tr>
                        <th scope="row">
                            <a class="show_page" href="{{route('product.show', $product->id)}}">{{$i}}</a>
                        </th>
                        <td>
                            <a class="show_page" href="{{route('product.show', $product->id)}}">
                                @if($product->name)
                                    @if(strlen($product->name)>34)

                                        {{ substr($product->name, 0, 34) }}...
                                    @else
                                        {{$product->name}}
                                    @endif
                                @else
                                    <div class="no_text"></div>
                                @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('product.show', $product->id)}}">
                                @if(isset($current_category) && $current_category !=''){{ $current_category }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('product.show', $product->id)}}">
                                {{$product->sum??''}}
                            </a>
                        </td>
                        <td>
                            <a class="show_page_color" href="{{route('product.show', $product->id)}}">
                                <div class="d-flex">
                                    @if($product->images)
                                        @php
                                            $images = json_decode($product->images);
                                            $is_image = 0;
                                        @endphp
                                        @foreach($images as $image)
                                            @php
                                                $avatar_main = storage_path('app/public/products/'.$image);
                                            @endphp
                                            @if(file_exists($avatar_main))
                                                @php($is_image = 1)
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
                            <a class="show_page" href="{{route('product.show', $product->id)}}">
                                @if($product->updated_at){{ $product->updated_at }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td class="function_column">
                            <div class="d-flex justify-content-center">
                                <a class="form_functions btn btn-info" href="{{route('product.edit', $product->id)}}"><i class="fe-edit-2"></i></a>
                                <form action="{{route('product.destroy', $product->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="form_functions btn btn-danger" type="submit"><i class="fe-trash-2"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
