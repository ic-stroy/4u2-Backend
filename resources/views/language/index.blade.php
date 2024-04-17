@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')






    <form class="parsley-examples" action="{{ route('env_key_update.update') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
               <h2>{{ translate('Default language') }}</h2>
            </div>
            <div class="col-md-2 ">
                <div class=" mt-2">
                    <input type="hidden" name="types[]" value="DEFAULT_LANGUAGE">
                    <select  class="form-select"    id="country" name="DEFAULT_LANGUAGE">
                        @foreach ($languages as $key => $language)
                        <option value="{{ $language->code??'' }}" <?php if (env('DEFAULT_LANGUAGE') == $language->code??'') {
                            echo 'selected';
                        } ?>>
                            {{ $language->name??'' }}
                        </option>
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary waves-effect waves-light mt-2">{{ translate('Save') }}</button>
            </div>
        </div>

    </form>

                        <table class="table mt-2" style="text-align:center !important">
                            <thead class="table-light">
                            <tr>
                                <th scope="row">№</th>
                                <td>{{ translate('Language') }}</td>
                                <td>{{ translate('Code') }}</td>
                                <td>{{ translate('Action') }}</td>
                            </tr>
                            </thead>
                            <tbody class="text-align:center !important">
                                @empty(!$languages)
                                    @php
                                        $i = 1;
                                    @endphp

                                    @foreach ($languages as $value)
                                    <tr>
                                        <th scope="row">{{ $i++ }}</th>
                                        <td> {{ $value->name??'' }}</td>
                                        <td>{{ $value->code??'' }}</td>
                                        <td>
                                            <a href="{{ route('language.show', $value->id) }}"
                                                title="{{ translate('Translation') }}"  >
                                                <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fa fa-language"></i></button>

                                                {{-- <i class="fa fa-language fa-2x" aria-hidden="true"></i> --}}
                                                {{-- <i class="fe-edit"> --}}
                                            </a>
                                            {{-- <a href="" class="fe-edit fa-2x"
                                               style="margin-left: 15px">
                                            </a> --}}
                                            <a href="{{ route('language.edit', encrypt($value->id)) }}">
                                                <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fe-edit"></i></button>
                                            </a>
                                            @if ($value->code != 'en')
                                                <button type="button" class="btn btn-danger delete-datas" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" data-url="{{ route('language.destroy', $language->id) }}"><i class="fe-trash-2"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach

                                @endempty
                            </tbody>
                        </table>



    {{-- Your page content --}}

@endsection

