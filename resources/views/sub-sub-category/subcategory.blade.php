@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <style>
        .nav-link{
            height: 100%;
            width: 194px !important;
        }
        .nav-item{
            margin-bottom: 1rem !important;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Sub category lists')}}</h4>
            <div class="dropdown float-end mb-2">
                <a class="form_functions btn btn-success" href="{{route('subcategory.create')}}">{{translate('Create')}}</a>
            </div>
            <ul class="nav nav-pills navtab-bg nav-justified">
                @php
                    $i = 0;

                @endphp
                @foreach($subcategories as $subcategory)
                    @php
                        $i++;
                    @endphp
                    <li class="nav-item">
                        <a href="#category_{{$subcategory->id}}" data-bs-toggle="tab" aria-expanded="{{$i == 1?'true':'false'}}" class="nav-link {{$i == 1?'active':''}}">
                            {{$subcategory->name??''}}
                            @if(count($all_sub_sub_categories[$subcategory->id]) > 0)
                                <span class="badge bg-danger">{{count($all_sub_sub_categories[$subcategory->id])}}</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                @php
                    $j = 0;
                @endphp
                @foreach($subcategories as $subcategory)
                    @php
                        $j++;
                    @endphp
                    <div class="tab-pane{{$j == 1?' show active':''}}" id="category_{{$subcategory->id}}">
                        <table class="table table-striped table-bordered dt-responsive nowrap text-center">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{translate('Name')}}</th>
                                <th>{{translate('Updated_at')}}</th>
                                <th class="text-center">{{translate('Functions')}}</th>
                            </tr>
                            </thead>
                            <tbody class="table_body">
                            @php
                                $i = 0
                            @endphp
                            @foreach($all_sub_sub_categories[$subcategory->id] as $subsubcategory)
                                @php
                                    $i++
                                @endphp
                                <tr>
                                    <th scope="row">
                                        <a class="show_page" href="{{route('subcategory.show', $subsubcategory->id)}}">{{$i}}</a>
                                    </th>
                                    <td>
                                        <a class="show_page" href="{{route('subcategory.show', $subsubcategory->id)}}">
                                            @if(isset($subsubcategory->name)){{ $subsubcategory->name }}@else <div class="no_text"></div> @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a class="show_page" href="{{route('subcategory.show', $subsubcategory->id)}}">
                                            @if(isset($subsubcategory->updated_at)){{ $subsubcategory->updated_at }}@else <div class="no_text"></div> @endif
                                        </a>
                                    </td>
                                    <td class="function_column">
                                        <div class="d-flex justify-content-center">
                                            <a class="form_functions btn btn-info" href="{{route('subsubcategory.edit', $subsubcategory->id)}}"><i class="fe-edit-2"></i></a>
                                            <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('subsubcategory.destroy', $subsubcategory->id)}}"><i class="fe-trash-2"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
