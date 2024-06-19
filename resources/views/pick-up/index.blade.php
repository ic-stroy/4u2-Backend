@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Pick up lists')}}</h4>
            <div class="dropdown float-end mb-2">
                <a class="form_functions btn btn-success" href="{{route('pick_up.create')}}">{{translate('Create')}}</a>
            </div>
            <table class="table table-striped table-bordered dt-responsive nowrap text-center">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('City')}}</th>
                    <th>{{translate('Updated_at')}}</th>
                    <th class="text-center">{{translate('Functions')}}</th>
                </tr>
                </thead>
                <tbody class="table_body">
                @php
                    $i = 0
                @endphp
                @foreach($addresses as $address)
                    @php
                        $i++
                    @endphp
                    <tr>
                        <th scope="row">
                            <a class="show_page" href="{{route('pick_up.show', $address['id'])}}">{{$i}}</a>
                        </th>
                        <td>
                            <a class="show_page" href="{{route('pick_up.show', $address['id'])}}">
                                @if(isset($address['name'])){{ $address['name'] }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('pick_up.show', $address['id'])}}">
                                @if(isset($address['city'])){{ $address['city'] }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td>
                            <a class="show_page" href="{{route('pick_up.show', $address['id'])}}">
                                @if(isset($address['updated_at'])){{ $address['updated_at'] }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                        <td class="function_column">
                            <div class="d-flex justify-content-center">
                                <a class="form_functions btn btn-info" href="{{route('pick_up.edit', $address['id'])}}"><i class="fe-edit-2"></i></a>
                                <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('pick_up.destroy', $address['id'])}}"><i class="fe-trash-2"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        if(localStorage.getItem('region_id') != undefined && localStorage.getItem('region_id') != null){
            localStorage.removeItem('region_id')
        }
        if(localStorage.getItem('district_id') != undefined && localStorage.getItem('district_id') != null){
            localStorage.removeItem('district_id')
        }
        if(localStorage.getItem('region') != undefined && localStorage.getItem('region') != null){
            localStorage.removeItem('region')
        }
        if(localStorage.getItem('district') != undefined && localStorage.getItem('district') != null){
            localStorage.removeItem('district')
        }
    </script>

@endsection
