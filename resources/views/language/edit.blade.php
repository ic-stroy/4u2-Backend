@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
            <form action="{{route('language.update',$language->id??'')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-7">
                        <div class="">
                            <label class="form-label">{{translate('Name')}}</label>

                            <input type="text" name="name" value="{{$language->name??''}}" class="form-control" required placeholder="{{translate('Type something')}}" />
                        </div>
                    </div>
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-3">
                        <div style="margin-top: 30px;">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Submit')}}</button>

                            {{-- <button type="reset" class="btn btn-secondary waves-effect">Cancel</button> --}}
                        </div>
                    </div>
                </div>

                {{-- <div class="mb-3">
                    <label class="form-label">E-Mail</label>
                    <div>
                        <input type="email" class="form-control" required parsley-type="email" placeholder="Enter a valid e-mail" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">URL</label>
                    <div>
                        <input parsley-type="url" type="url" class="form-control" required placeholder="URL" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Digits</label>
                    <div>
                        <input data-parsley-type="digits" type="text" class="form-control" required placeholder="Enter only digits" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Number</label>
                    <div>
                        <input data-parsley-type="number" type="text" class="form-control" required placeholder="Enter only numbers" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alphanumeric</label>
                    <div>
                        <input data-parsley-type="alphanum" type="text" class="form-control" required placeholder="Enter alphanumeric value" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Textarea</label>
                    <div>
                        <textarea required class="form-control"></textarea>
                    </div>
                </div> --}}

            </form>

@endsection
