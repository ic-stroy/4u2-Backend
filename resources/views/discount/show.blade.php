@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Discount lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('discount.create')}}">{{__('Create')}}</a>
            </div>
{{--            <table id="datatable-buttons" class="table dt-responsive nowrap table_show">--}}
            <table class="table dt-responsive nowrap table_show">
                <thead>
                    <tr>
                        <th>{{__('Attributes')}}</th>
                        <th>{{__('Informations')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{{__('Category')}}</th>
                        <td>
                            @if($category != '' || $subcategory != '')
                                {{$category}} {{' '.$subcategory}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Discount percent')}}</th>
                        <td>
                            @if($discounts_data['discount']->percent != null)
                                {{$discounts_data['discount']->percent}} {{__(' %')}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Number of warehouses')}}</th>
                        <td>
                            @if($discounts_data['number'] != null)
                                {{$discounts_data['number']}}
                            @endif
                        </td>
                    </tr>
                    @if(isset($discounts_data['discount']->product))
                        <tr>
                            <th>{{__('Product')}}</th>
                            <td>{{$discounts_data['discount']->product->name??''}}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>{{__('Updated at')}}</th>
                        <td>{{$discounts_data['discount']->updated_at??''}}</td>
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
