@extends('layout.layout')

@section('title')
{{ translate("$type translation") }}
@endsection
@section('content')



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
                        <a href="{{ route('table.tableShow', ['language_id' => $value->id, 'type' => $type]) }}"
                           title="{{ translate('Translation') }}"  >
                            <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fa fa-language"></i></button>
                        </a>
                    </td>
                </tr>
            @endforeach

        @endempty
        </tbody>
    </table>



    {{-- Your page content --}}

@endsection

