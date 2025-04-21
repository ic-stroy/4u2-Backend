@extends('layout.layout')

@section('title')
     {{-- Index --}}
    {{ translate(" $type Translation") }}
@endsection
@section('content')
    <form class="form-horizontal" action="{{ route('table_translation.save') }}" method="POST">
        @csrf
        <input type="hidden" id="language_code" value="{{ $language->code??'' }}">
        <input type="hidden" name="id" value="{{ $language->id }}">
        <input type="hidden" name="type" value="{{ $type??''}}">
        {{-- @dd($language->id); --}}
        <h5 class="mb-md-0 h6">{{ $language->name??'' }}</h5>
        <table id="datatable" class="table table-striped datatable table-bordered dt-responsive nowrap">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Key') }}</th>
                    <th> {{ translate('Translation') }}</th>
                </tr>
            </thead>

            <tbody>
            @if (count($lang_keys) > 0)
                @php
                    $n = 1;
                @endphp
                @foreach ($lang_keys as $key => $translation)
                    <tr>
                        <td>{{ $n++ }}</td>
                        <td class="lang_key">{{ optional($translation->getModel)->name??'' }}</td>
                        <td class="lang_value">
                            @switch($type)
                                @case('city')
                                    <input type="text" class="checkboxDivPerewvod value" id="input"
                                    style="width:100%" name="values[{{ $translation->city_id }}]"
                                    value="{{ $translation->name??'' }}">
                                    @break
                                @case('category')
                                    <input type="text" class="checkboxDivPerewvod value" id="input"
                                    style="width:100%" name="values[{{ $translation->category_id }}]"
                                    value="{{ $translation->name??'' }}">
                                    @break
                                @case('color')
                                    <input type="text" class="checkboxDivPerewvod value" id="input"
                                    style="width:100%" name="values[{{ $translation->color_id }}]"
                                    value="{{ $translation->name??'' }}">
                                    @break
                                @case('product')
                                    <input type="text" class="checkboxDivPerewvod value" id="input"
                                    style="width:100%" name="values[{{ $translation->product_id }}]"
                                    value="{{ $translation->name??'' }}">
                                    @break
                                @default
                                    <span>Something went wrong, please try again</span>
                            @endswitch

                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        <div class="row ">
            <div class="col-xl-6 col-md-6">
           {{-- @dd(fsefes) --}}
            </div>
            <div class="col-xl-6 col-md-6">
                <div class="form-group mt-2 text-right">
                    <button type="button" class="btn btn-primary"
                            onclick="copyTranslation()">{{ translate('Copy Translations') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                </div>
            </div>
        </div>
    </form>

    <script src="{{ asset('assets/js/other/language.js') }}"></script>

@endsection
