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
            @if(isset($discount_data['discount'][0]))
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
                                @if(!empty($discount_data['category'][0]) || !empty($discount_data['subcategory'][0]) || !empty($discount_data['subsubcategory'][0]))
                                    {{implode(', ', [$discount_data['category'][0], $discount_data['subcategory'][0], $discount_data['subsubcategory'][0]])}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{__('Discount percent')}}</th>
                            <td>
                                @if($discount_data['discount'][0]->percent != null)
                                    {{$discount_data['discount'][0]->percent}} {{__(' %')}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{__('Number of warehouses')}}</th>
                            <td>
                                @if($discount_data['number'] != null)
                                    {{$discount_data['number']}}
                                @endif
                            </td>
                        </tr>
                        @if(isset($discount_data['discount'][0]->product))
                            <tr>
                                <th>{{__('Product')}}</th>
                                <td>
                                    @foreach($discount_data['discount'] as $discount)
                                        {{$discount->product->name??''}}. <br>
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>{{__('Updated at')}}</th>
                            <td>{{$discount_data['discount'][0]->updated_at??''}}</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
