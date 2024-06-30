@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate($product->name??'')}} {{translate('product lists')}}</h4>
            <div class="dropdown float-end mb-1">
                <a class="form_functions btn btn-success" href="{{route('characterizedProducts.category.create_characterized_product', $product->id)}}">{{translate('Add products to warehouse')}}</a>
            </div>
{{--            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">--}}
            <table class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('Product')}}</th>
                        <th>{{translate('Size')}}</th>
                        <th>{{translate('Sum')}}</th>
                        <th>{{translate('Color')}}</th>
                        <th>{{translate('Count')}}</th>
                        <th>{{translate('Updated_at')}}</th>
                        <th class="text-center">{{translate('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                @php
                    $i = 0
                @endphp
                @foreach($characterized_products as $product)
                    @php
                        $i++
                    @endphp
                    <tr>
                        <th scope="row">
                            <a class="show_page" href="{{route('characterizedProducts.show', $product->id)}}">{{$i}}</a>
                        </th>
                        <td>
                            <a class="show_page" href="{{route('characterizedProducts.show', $product->id)}}">
                                @if(isset($product->product->name))
                                    @if(strlen($product->product->name)>34)
                                        {{ substr($product->product->name, 0, 34) }}...
                                    @else
                                        {{$product->product->name}}
                                    @endif
                                @else
                                    <div class="no_text"></div>
                                @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('characterizedProducts.show', $product->id)}}">
                                @if($product->size)
                                    @if($product->size->name)
                                        {{ $product->size->name}}
                                    @else <div class="no_text"></div> @endif
                                @else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('characterizedProducts.show', $product->id)}}">
                                @if($product->sum) {{ $product->sum }} @else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            @if($product->color)
                                @if($product->color->code)
                                    <a class="show_page_color" href="{{route('characterizedProducts.show', $product->id)}}">
                                        <div style="background-color: {{$product->color->code}}; height: 40px; width: 64px; border-radius: 4px; border: solid 1px"></div>
                                    </a>
                                @else
                                    <a class="show_page" href="{{route('characterizedProducts.show', $product->id)}}">
                                        <div class="no_text"></div>
                                    </a>
                                @endif
                            @else
                                <a class="show_page" href="{{route('characterizedProducts.show', $product->id)}}">
                                    <div class="no_text"></div>
                                </a>
                            @endif
                        </td>
                        <td>
                            <a class="show_page" href="{{route('characterizedProducts.show', $product->id)}}">
                                @if($product->count){{ $product->count }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('characterizedProducts.show', $product->id)}}">
                                @if($product->updated_at){{ $product->updated_at }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td class="function_column">
                            <div class="d-flex justify-content-center">
                                <a class="form_functions btn btn-info" href="{{route('characterizedProducts.edit', $product->id)}}"><i class="fe-edit-2"></i></a>
                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('characterizedProducts.destroy', $product->id)}}"><i class="fe-trash-2"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
