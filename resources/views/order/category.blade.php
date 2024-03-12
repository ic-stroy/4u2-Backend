@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Category lists')}}</h4>
            <table class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                <tr>
                    <th></th>
                </tr>
                </thead>
                <tbody class="table_body">
{{--                    <tr>--}}
{{--                        <td>--}}
{{--                            <a class="show_page" href="{{route('company_order.index', \App\Constants::BASKED)}}">--}}
{{--                                {{translate('Basket')}}--}}
{{--                            </a>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
                    <tr>
                        <td>
                            <a class="show_page" href="{{route('company_order.index', \App\Constants::ORDERED)}}">
                                <span>{{translate('Ordered')}}
                                    @if($ordered_orders > 0)
                                        <span class="badge bg-danger"> {{translate('new')}} {{$ordered_orders}}</span>
                                    @endif
                                </span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a class="show_page" href="{{route('company_order.index', \App\Constants::PERFORMED)}}">
                                <span>{{translate('Performed')}}
                                    @if($performed_orders > 0)
                                        <span class="badge bg-success"> {{$performed_orders}}</span>
                                    @endif
                                </span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a class="show_page" href="{{route('company_order.index', \App\Constants::CANCELLED)}}">
                                <span>{{translate('Cancelled')}}
                                    @if($cancelled_orders > 0)
                                        <span class="badge bg-danger"> {{$cancelled_orders}}</span>
                                    @endif
                                </span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a class="show_page" href="{{route('company_order.index', \App\Constants::ACCEPTED_BY_RECIPIENT)}}">
                                <span>{{translate('Accepted by recipient')}}
                                    @if($accepted_by_recipient_orders>0)
                                        <span class="badge bg-danger"> {{$accepted_by_recipient_orders}}</span>
                                    @endif
                                </span>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
