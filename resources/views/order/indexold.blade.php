@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">
                @switch($id)
                    @case(1)
                    {{translate('Basked orders list')}}
                    @break
                    @case(2)
                    {{translate('Ordered orders list')}}
                    @break
                    @case(3)
                    {{translate('Finished orders list')}}
                    @break
                @endswitch
            </h4>
            {{--            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">--}}
            <table class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Products')}}</th>
                    <th>{{translate('Quantity')}}</th>
                    <th>{{translate('Status')}}</th>
                    <th>{{translate('User')}}</th>
                    <th class="text-center">{{translate('Functions')}}</th>
                </tr>
                </thead>
                <tbody class="table_body">
                @php
                    $i=0
                @endphp
                @foreach($order_data as $order)
                    @php
                        $i++
                    @endphp
                    <tr>
                        <th scope="row">
                            <a class="show_page" href="{{route('company_order.show', $order['order']->id)}}">{{$i}}</a>
                        </th>
                        <td>
                            <a class="show_page" href="{{route('company_order.show', $order['order']->id)}}">
                                @if(isset($order['product_types'])){{ $order['product_types'] }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('company_order.show', $order['order']->id)}}">
                                @if(isset($order['product_quantity'])){{ $order['product_quantity'] }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('company_order.show', $order['order']->id)}}">
                                @if($order['order']->status)
                                    @switch($order['order']->status)
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
                                @else<div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('company_order.show', $order['order']->id)}}">
                                @if(isset($order['order']->user->personalInfo->id))
                                    @php
                                        $first_name = isset($order['order']->user->personalInfo->first_name)?$order['order']->user->personalInfo->first_name.' ':'';
                                        $last_name = isset($order['order']->user->personalInfo->last_name)?$order['order']->user->personalInfo->last_name.' ':'';
                                        $middle_name = isset($order['order']->user->personalInfo->middle_name)?$order['order']->user->personalInfo->middle_name:'';
                                        $user_name = $first_name.''.$last_name.''.$middle_name;
                                    @endphp
                                    {{$user_name}}
                                @else
                                    <div class="no_text"></div>
                                @endif
                            </a>
                        </td>
                        <td class="function_column">
                            <div class="d-flex justify-content-around">
                                <button type="button" class="btn btn-success delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#success-alert-modal" data-url=""><i class="fa fa-check"></i></button>
                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url=""><i class="fa fa-times"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
