@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Discount lists')}}</h4>
            <div class="dropdown float-end mb-2">
                <a class="form_functions btn btn-success" href="{{route('discount.create')}}">{{__('Create')}}</a>
            </div>
            {{--            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">--}}
            <table class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Discount percent')}}</th>
                    <th>{{__('Category')}}</th>
                    <th>{{__('Product')}}</th>
                    <th>{{__('Number of warehouses')}}</th>
                    <th class="text-center">{{__('Functions')}}</th>
                </tr>
                </thead>
                <tbody class="table_body">
                @php
                    $i = 0
                @endphp
                @foreach($discounts_data as $discount_data)
                    @php
                        $i++;
                        $category = '';
                        $subcategory = '';
                        if($discount_data['discount']->product){
                            if($discount_data['discount']->product->category){
                                $category = $discount_data['discount']->product->category->name;
                                $subcategory = '';
                            }elseif($discount_data['discount']->product->subCategory){
                                $category = $discount_data['discount']->product->subCategory->category?$discount_data['discount']->product->subCategory->category->name:'';
                                $subcategory = $discount_data['discount']->product->subCategory->name;
                            }
                        }
                    @endphp
                    <tr>
                        <td>
                            <a class="show_page" href="{{route('discount.show', $discount_data['discount']->id)}}">
                                {{$i}}
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('discount.show', $discount_data['discount']->id)}}">
                                @if($discount_data['discount']->percent != null)
                                    {{$discount_data['discount']->percent}} {{__(' %')}}
                                @else
                                    <div class="no_text"></div>
                                @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('discount.show', $discount_data['discount']->id)}}">
                                @if($category != '' || $subcategory != '')
                                    {{$category}} {{' '.$subcategory}}
                                @else
                                    <div class="no_text"></div>
                                @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('discount.show', $discount_data['discount']->id)}}">
                                @if(isset($discount_data['discount']->product->id))
                                    {{$discount_data['discount']->product->name}}
                                @else
                                    {{__('All products')}}
                                @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('discount.show', $discount_data['discount']->id)}}">
                                @if(isset($discount_data['number']))
                                    {{$discount_data['number']}}
                                @else
                                    <div class="no_text"></div>
                                @endif
                            </a>
                        </td>
                        <td class="function_column">
                            <div class="d-flex justify-content-center">
                                <a class="form_functions btn btn-info" href="{{route('discount.edit', $discount_data['discount']->id)}}"><i class="fe-edit-2"></i></a>
                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('discount.destroy', $discount_data['discount']->id)}}"><i class="fe-trash-2"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
