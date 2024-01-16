@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Characterized products lists')}}</h4>
            <table class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="table_body">
                @php
                    $i = 0
                @endphp
                @foreach($products as $product)
                    @php
                        $i++
                    @endphp
                    <tr>
                        <td>
                            <a class="show_page" href="{{route('characterizedProducts.category.characterized_product', $product->id)}}">
                                @if(isset($product->name))
                                    @if(strlen($product->name)>34)
                                        {{ substr($product->name, 0, 34) }}...
                                    @else
                                        {{$product->name}}
                                    @endif
                                @else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page_color" href="{{route('characterizedProducts.category.characterized_product', $product->id)}}">
                                <div class="d-flex">
                                    @if(isset($product->images))
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
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
