@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
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
                @foreach($categories as $category)
                    @php
                        $i++;
                    @endphp
                    <li class="nav-item">
                        <a href="#category_{{$category->id}}" data-bs-toggle="tab" aria-expanded="{{$i == 1?'true':'false'}}" class="nav-link {{$i == 1?'active':''}}">
                            {{$category->name??''}}
                            @if(count($all_categories[$category->id]) > 0)
                                <span class="badge bg-danger">{{count($all_categories[$category->id])}}</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                @php
                    $j = 0;
                @endphp
                @foreach($categories as $category)
                    @php
                        $j++;
                    @endphp
                    <div class="tab-pane{{$j == 1?' show active':''}}" id="category_{{$category->id}}">
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
                            @foreach($all_categories[$category->id] as $subcategory)
                                @php
                                    $i++
                                @endphp
                                <tr>
                                    <th scope="row">
                                        <a class="show_page" href="{{route('subcategory.show', $subcategory->id)}}">{{$i}}</a>
                                    </th>
                                    <td>
                                        <a class="show_page" href="{{route('subcategory.show', $subcategory->id)}}">
                                            @if($subcategory->name){{ $subcategory->name }}@else <div class="no_text"></div> @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a class="show_page" href="{{route('subcategory.show', $subcategory->id)}}">
                                            @if($subcategory->updated_at){{ $subcategory->updated_at }}@else <div class="no_text"></div> @endif
                                        </a>
                                    </td>
                                    <td class="function_column">
                                        <div class="d-flex justify-content-center">
                                            <a class="form_functions btn btn-info" href="{{route('subcategory.edit', $subcategory->id)}}"><i class="fe-edit-2"></i></a>
                                            <button type="button" class="btn btn-danger delete-datas btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{route('subcategory.destroy', $subcategory->id)}}"><i class="fe-trash-2"></i></button>
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
