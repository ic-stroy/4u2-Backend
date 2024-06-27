@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Coupon lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success mb-2" href="{{route('coupons.create')}}">{{translate('Create')}}</a>
            </div>
{{--            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">--}}
            <table class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('Name')}}</th>
                        <th>{{translate('Coupon quantity')}}</th>
                        <th>{{translate('Minimum price')}}</th>
                        <th>{{translate('Orders\' quantity or number')}}</th>
                        <th class="text-center">{{translate('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @php
                        $i = 0
                    @endphp
                    @foreach($coupons as $coupon)
                        @php
                            $i++;
                        @endphp
                        <tr>
                            <td>
                                <a class="show_page" href="{{route('coupons.show', $coupon->id)}}">
                                    {{$i}}
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('coupons.show', $coupon->id)}}">
                                    @if($coupon->name)
                                        {{$coupon->name}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('coupons.show', $coupon->id)}}">
                                    @if ($coupon->price != null)
                                       {{$coupon->price}} {{translate(' sum')}}
                                    @elseif($coupon->percent != null)
                                       {{$coupon->percent}} {{translate(' %')}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('coupons.show', $coupon->id)}}">
                                    @if($coupon->min_price)
                                        {{$coupon->min_price}}
                                    @else
                                        <div class="no_text"></div>
                                    @endif
                                </a>
                            </td>
                            @if($coupon->order_quantity)
                                <td>
                                    <a class="show_page" href="{{route('coupons.show', $coupon->id)}}">
                                        {{$coupon->order_quantity}} {{translate('quantity')}}
                                    </a>
                                </td>
                            @elseif($coupon->order_number)
                                <td>
                                    <a class="show_page" href="{{route('coupons.show', $coupon->id)}}">
                                        {{$coupon->order_number}} {{translate('number')}}
                                    </a>
                                </td>
                            @else
                                <a class="show_page" href="{{route('coupons.show', $coupon->id)}}">
                                    <div class="no_text"></div>
                                </a>
                            @endif
                            <td class="function_column">
                                <div class="d-flex justify-content-center">
                                    <a class="form_functions btn btn-info" href="{{route('coupons.edit', $coupon->id)}}"><i class="fe-edit-2"></i></a>
                                    <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('coupons.destroy', $coupon->id)}}"><i class="fe-trash-2"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
