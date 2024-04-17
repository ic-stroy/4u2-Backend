@extends('layout.layout')

@section('title')

     {{ translate("Table translation") }}
@endsection
@section('content')
    <style>
        .translation_content{
            list-style: none;
            text-align: center;
            color: black;
        }
        .translation_list{
            height: 44px;
        }
        .translation_menu{
            width: 100% !important;
            height: 44px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            border-radius: 4px;
            color: black;
        }
        .translation_list{
            margin-top: 8px;
        }
        .translation_menu{
            transition: 0.4s;
        }
        .translation_menu:hover{
            background-color: silver;
            transform: scale(1.01);
        }
    </style>
    <div class="justify-content-center">
        <ul class="translation_content">
            <li class="translation_list">
                <a href="{{ route('table.show', 'city') }}"><div class="translation_menu">{{translate('City translate')}}</div></a>
            </li>
            <li class="translation_list">
                <a href="{{ route('table.show', 'category') }}"><div class="translation_menu">{{translate('Category translate')}}</div></a>
            </li>
            <li class="translation_list">
                <a href="{{ route('table.show', 'color') }}"><div class="translation_menu">{{translate('Color translate')}}</div></a>
            </li>
            <li class="translation_list">
                <a href="{{ route('table.show', 'company') }}"><div class="translation_menu">{{translate('Company translate')}}</div></a>
            </li>
            <li class="translation_list">
                <a href="{{ route('table.show', 'product') }}"><div class="translation_menu">{{translate('Product translate')}}</div></a>
            </li>
            <li class="translation_list">
                <a href="{{ route('table.show', 'warehouse') }}"><div class="translation_menu">{{translate('Warehouse translate')}}</div></a>
            </li>
            <li class="translation_list">
                <a href="{{ route('table.show', 'role') }}"><div class="translation_menu">{{translate('Role translate')}}</div></a>
            </li>
        </ul>
    </div>

    <script src="{{ asset('assets/js/other/language.js') }}"></script>

@endsection
