@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Order lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('order.create')}}">{{__('Create')}}</a>
            </div>
            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('Product')}}</th>
                        <th>{{__('User')}}</th>
                        <th>{{__('Updated_at')}}</th>
                        <th class="text-center">{{__('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @php
                        $i = 0
                    @endphp
                    @foreach($orders as $order)
                        @php
                            $i++
                        @endphp
                        <tr>
                            <th scope="row">
                                <a class="show_page" href="{{route('order.show', $order->id)}}">{{$i}}</a>
                            </th>
                            <td>
                                <a class="show_page" href="{{route('order.show', $order->id)}}">
                                    @if(isset($order->name)){{ $order->name }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page_color" href="{{route('order.show', $order->id)}}">
                                    <div style="background-color: {{$colorList->code??''}}; height: 40px; width: 64px; border-radius: 4px; border: solid 1px"></div>
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('order.show', $order->id)}}">
                                    @if(isset($order->updated_at)){{ $order->updated_at }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td class="function_column">
                                <div class="d-flex justify-content-center">
                                    <a class="form_functions btn btn-info" href="{{route('order.edit', $order->id)}}"><i class="fe-edit-2"></i></a>
                                    <form action="{{route('order.destroy', $order->id)}}" method="POST">
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
