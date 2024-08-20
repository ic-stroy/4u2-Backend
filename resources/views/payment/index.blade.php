@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <link rel="stylesheet" href="{{asset('assets/css/toogle-switch.css')}}">
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Payment status')}}</h4>
            <table class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('Status')}}</th>
                        <th>{{translate('Updated_at')}}</th>
                        <th class="text-center">{{translate('On Slide show')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @if($payment)
                        <tr>
                            <th scope="row">
                                <a class="show_page">{{$payment->id}}</a>
                            </th>
                            <td>
                                <a class="show_page">
                                    {{$payment->status == 1?translate('Active'):translate('No active') }}
                                </a>
                            </td>
                            <td>
                                <a class="show_page">
                                    @if(isset($payment->updated_at)){{ $payment->updated_at }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td class="function_column">
                                <input type="hidden" id="productId" name="product_id" value="{{$payment->id}}">
                                <div class="d-flex justify-content-center">
                                    <label class="switch">
                                        <input name="slide_show" id="slideShow" type="checkbox" {{$payment->status == 1?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            let slideShow = document.getElementById('slideShow')
            let productId = document.getElementById('productId')

            slideShow.addEventListener('change', function () {
                if (this.checked) {
                    $.ajax({
                        type: "GET",
                        url: "/../payment/status/",
                        data: {
                            "id": productId.value,
                            "checked": true,
                        },
                        success: function (data) {
                            if(data.status == true){
                                toastr.success(data.message)
                            }
                        }
                    });
                } else {
                    $.ajax({
                        type: "GET",
                        url: "/../payment/status/",
                        data: {
                            "id": productId.value,
                            "checked": false,
                        },
                        success: function (data) {
                            if(data.status == true){
                                toastr.warning(data.message)
                            }
                        }
                    });
                }
            })
        })
    </script>
@endsection
