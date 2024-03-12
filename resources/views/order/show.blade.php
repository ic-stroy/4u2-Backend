@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Order lists')}}</h4>
{{--            <table id="datatable-buttons" class="table dt-responsive nowrap table_show">--}}
            <table class="table dt-responsive nowrap table_show">
                <thead>
                <tr>
                    <th>{{translate('Attributes')}}</th>
                    <th>{{translate('Informations')}}</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{{translate('id')}}</th>
                        <td>{{$order->id??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Price')}}</th>
                        <td>@if(isset($order->price)){{ $order->price }}@endif</td>
                    </tr>
                    <tr>
                        <th>{{translate('Status')}}</th>
                        <td>
                            @switch($order->status)
                                @case(1)
                                    {{translate('Basked')}}
                                    @break
                                @case(2)
                                    {{translate('Ordered')}}
                                    @break
                                @case(3)
                                    {{translate('Finished')}}
                                    @break
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <th>{{translate('Client')}}</th>
                        <td>
                            @if(isset($order->user->personalInfo->id))
                                @php
                                    $first_name = isset($order->user->personalInfo->first_name)?$order->user->personalInfo->first_name.' ':'';
                                    $last_name = isset($order->user->personalInfo->last_name)?$order->user->personalInfo->last_name.' ':'';
                                    $middle_name = isset($order->user->personalInfo->middle_name)?$order->user->personalInfo->middle_name:'';
                                    $user_name = $first_name.''.$last_name.''.$middle_name;
                                @endphp
                                {{$user_name}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{translate('Delivery date')}}</th>
                        <td>{{$warehouse->delivery_date??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Delivery price')}}</th>
                        <td>{{$warehouse->delivery_price??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('All price')}}</th>
                        <td>{{$warehouse->all_price??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Coupon')}}</th>
                        <td>{{$warehouse->coupon_id??''}}</td>
                    </tr>
                    <tr>
                        <th>{{translate('Updated at')}}</th>
                        <td>{{$warehouse->updated_at??''}}</td>
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
