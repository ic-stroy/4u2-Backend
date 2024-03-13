<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Coupon;

use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::all();
        return view('coupons.index', ['coupons'=> $coupons]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $coupon = new Coupon();
        $coupon->name = $request->name;
        if ($request->coupon_type == "price") {
            $coupon->price = $request->price;
            $coupon->percent = NULL;
        }elseif ($request->coupon_type == "percent") {
            $coupon->price = NULL;
            $coupon->percent = $request->percent;
        }
        $coupon->min_price = $request->min_price;
        if ($request->coupon__type == "quantity") {
            $coupon->order_quantity = $request->order_quantity;
            $coupon->order_number = NULL;
        }elseif ($request->coupon__type == "number") {
            $coupon->order_quantity = NULL;
            $coupon->order_number = $request->order_number;
        }
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->save();
        return redirect()->route('coupons.index')->with('status', __('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Coupon::find($id);
        return view('coupons.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $coupon = Coupon::find($id);
        return view('coupons.edit', ['coupon'=> $coupon]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $coupon = Coupon::find($id);
        $coupon->name = $request->name;
        if ($request->coupon_type == "price") {
            $coupon->price = $request->price;
            $coupon->percent = NULL;
        } elseif ($request->coupon_type == "percent") {
            $coupon->price = NULL;
            $coupon->percent = $request->percent;
        }
        if(isset($request->min_price)){
            $coupon->min_price = $request->min_price;
        }
        if ($request->coupon__type == "quantity") {
            $coupon->order_quantity = $request->order_quantity;
            $coupon->order_number = NULL;
        }elseif ($request->coupon__type == "number") {
            $coupon->order_quantity = NULL;
            $coupon->order_number = $request->order_number;
        }
        if(isset($request->start_date)){
            $coupon->start_date = $request->start_date;
        }
        if(isset($request->end_date)){
            $coupon->end_date = $request->end_date;
        }
        $coupon->save();
        return redirect()->route('coupons.index')->with('status', __('Successfully created'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Coupon::find($id);
        $model->delete();
        return redirect()->route('coupons.index')->with('status', __('Successfully created'));
    }
}
