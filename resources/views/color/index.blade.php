@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    </style>
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Color lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" href="{{route('color.create')}}">{{translate('Create')}}</a>
            </div>
            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('Name')}}</th>
                        <th>{{translate('Color')}}</th>
                        <th>{{translate('Updated_at')}}</th>
                        <th class="text-center">{{translate('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @php
                        $i = 0
                    @endphp
                    @foreach($colors as $color)
                        @php
                            $i++
                        @endphp
                        <tr>
                            <th scope="row">
                                <a class="show_page" href="{{route('color.show', $color->id)}}">{{$i}}</a>
                            </th>
                            <td>
                                <a class="show_page" href="{{route('color.show', $color->id)}}">
                                    @if(isset($color->name)){{ $color->name }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page_color" href="{{route('color.show', $color->id)}}">
                                    <div style="background-color: {{$color->code??''}}; height: 40px; width: 64px; border-radius: 4px; border: solid 1px"></div>
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('color.show', $color->id)}}">
                                    @if(isset($color->updated_at)){{ $color->updated_at }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td class="function_column">
                                <div class="d-flex justify-content-center">
                                    <a class="form_functions btn btn-info" href="{{route('color.edit', $color->id)}}"><i class="fe-edit-2"></i></a>
                                    <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('color.destroy', $color->id)}}"><i class="fe-trash-2"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
