@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p class="text-muted font-14">
                {{__('Coupon list edit')}}
            </p>
            <form action="{{route('coupons.update', $coupon->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Coupon name')}}</label>
                        <input type="text" name="name" class="form-control" required value="{{$coupon->name}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Coupon type')}}</label>
                        <select name="coupon_type" class="form-control" id="coupon_type">
                            <option value="price" class="form-control" {{$coupon->price != NULL?'selected':''}}>{{__('Price')}}</option>
                            <option value="percent" class="form-control" {{$coupon->percent != NULL?'selected':''}}>{{__('Percent')}}</option>
                        </select>
                    </div>
                    <div class="mb-3 col-6" id="coupon_price">
                        <label class="form-label">{{__('Coupon price')}}</label>
                        <input type="number" name="price" class="form-control" id="coupon_price_input"  min="0"  value="{{$coupon->price}}"/>
                    </div>
                    <div class="mb-3 col-6 display-none" id="coupon_percent">
                        <label class="form-label">{{__('Coupon percent')}}</label>
                        <input type="number" name="percent" value="{{$coupon->percent??''}}" class="form-control" id="coupon_percent_input" min="0" max="100"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__("Order's min price")}}</label>
                        <input type="number" name="min_price" class="form-control" min="0" value="{{$coupon->min_price??''}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{__('Number of orders')}}</label>
                        <input type="number" name="order_count" class="form-control" value="{{$coupon->order_count}}"/>
                    </div>
                    <div class="mb-3 col-3">
                        <label class="form-label">{{__('Start date')}}</label>
                        <input type="date" name="start_date" class="form-control" required value="{{explode(' ', $coupon->start_date)[0]}}"/>
                    </div>
                    <div class="mb-3 col-3">
                        <label class="form-label">{{__('End date')}}</label>
                        <input type="date" name="end_date" class="form-control" required value="{{explode(' ', $coupon->end_date)[0]}}"/>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{__('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{__('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
    <script>
        let coupon_price_value = "{{$coupon->price??''}}"
        let coupon_percent_value = "{{$coupon->percent??''}}"
    </script>
    <script src="{{asset('assets/js/coupon.js')}}"></script>
@endsection
