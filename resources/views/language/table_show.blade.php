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
        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
            <thead>
            <tr>
                <th>#</th>
                {{-- <th>{{ translate('status') }}</th> --}}
                <th>{{ translate('Key') }}</th>
                {{-- <th>{{ translate('company') }}</th> --}}
                <th> {{ translate('Translation') }}</th>
                {{-- <th>{{ translate('cars list') }}</th> --}}

            </tr>
            </thead>

            <tbody>
            @if (count($lang_keys) > 0)
                @php
                    $n = 1;
                @endphp
                @foreach ($lang_keys as $key => $translation)
                    {{-- @dd($translation->country_id); --}}
                    <tr>
                        <td>{{ $n++ }}</td>
                        <td class="lang_key">{{ $translation->name??'' }}</td>
                        <td class="lang_value">
                            {{-- @dd($translation) --}}


                            @switch($type)

                                @case('city')
                                    @php
                                        $translate_lang = \App\Models\CityTranslations::where('lang', $language->code??'')->where('city_id', $translation->city_id??'')->first();
                                    @endphp
                                    <input type="text" class="checkboxDivPerewvod value" id="input"
                                    style="width:100%" name="values[{{ $translation->city_id }}]"
                                    @if (($translate_lang) != null) value="{{ $translate_lang->name }}" @endif>

                                    @break

                                @case('category')
                                    @php
                                       $translate_lang = \App\Models\CategoryTranslations::where('lang', $language->code??'')->where('category_id', $translation->category_id??'')->first();
                                    @endphp
                                    <input type="text" class="checkboxDivPerewvod value" id="input"
                                    style="width:100%" name="values[{{ $translation->category_id }}]"
                                    @if (($translate_lang) != null) value="{{ $translate_lang->name }}" @endif>

                                    @break

                                @case('color')
                                    @php
                                        $translate_lang = \App\Models\ColorTranslations::where('lang', $language->code??'')->where('color_id', $translation->color_id??'')->first();
                                    @endphp
                                    <input type="text" class="checkboxDivPerewvod value" id="input"
                                    style="width:100%" name="values[{{ $translation->color_id }}]"
                                    @if (($translate_lang) != null) value="{{ $translate_lang->name }}" @endif>

                                    @break

                                @case('company')
                                    @php
                                       $translate_lang = \App\Models\CompanyTranslations::where('lang', $language->code??'')->where('company_id', $translation->company_id??'')->first();
                                    @endphp
                                    <input type="text" class="checkboxDivPerewvod value" id="input"
                                    style="width:100%" name="values[{{ $translation->company_id }}]"
                                    @if (($translate_lang) != null) value="{{ $translate_lang->name }}" @endif>

                                    @break

                                @case('product')
                                    @php
                                        $translate_lang = \App\Models\ProductTranslations::where('lang', $language->code??'')->where('product_id', $translation->product_id??'')->first();
                                    @endphp
                                    <input type="text" class="checkboxDivPerewvod value" id="input"
                                    style="width:100%" name="values[{{ $translation->option_id }}]"
                                    @if (($translate_lang) != null) value="{{ $translate_lang->name }}" @endif>

                                    @break

                                @case('role')
                                    @php
                                       $translate_lang = \App\Models\RoleTranslations::where('lang', $language->code??'')->where('role_id', $translation->role_id??'')->first();
                                    @endphp
                                    <input type="text" class="checkboxDivPerewvod value" id="input"
                                    style="width:100%" name="values[{{ $translation->role_id }}]"
                                    @if (($translate_lang) != null) value="{{ $translate_lang->name }}" @endif>

                                    @break
                                @case('warehouse')
                                    @php
                                    $translate_lang = \App\Models\WarehouseTranslations::where('lang', $language->code??'')->where('warehouse_id', $translation->warehouse_id??'')->first();
                                    @endphp
                                    <input type="text" class="checkboxDivPerewvod value" id="input"
                                    style="width:100%" name="values[{{ $translation->warehouse_id }}]"
                                    @if (($translate_lang) != null) value="{{ $translate_lang->name }}" @endif>

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
