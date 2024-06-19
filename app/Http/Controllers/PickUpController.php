<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PickUpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $super_admins_id = User::where('is_admin', 1)->pluck('id')->all();
        $addresses_ = Address::whereIn('user_id', $super_admins_id)->get();

        $addresses = [];
        foreach ($addresses_ as $model){

            $city = '';
            $region = '';
            if($model->cities){
                if($model->cities->type == 'district'){
                    $city = $model->cities->name??'';
                    if($model->cities->region){
                        $region = $model->cities->region->name??'';
                    }
                }else{
                    $region = $model->cities->name??'';
                }
            }

            $address = $region.' '.$city;

            $addresses[] = [
                'id'=>$model->id,
                'name'=>$model->name,
                'city'=>$address,
                'postcode'=>$model->postcode,
                'updated_at'=>$model->updated_at
            ];

        }

        return view('pick-up.index', ['addresses'=>$addresses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pick-up.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $address = new Address();
        $address->name = $request->name;
        if($request->district){
            $address->city_id = $request->district_id;
        }elseif($request->region){
            $address->city_id = $request->region_id;
        }
        $super_admin_id = User::select('id')->where('is_admin', 1)->orderBy('created_at', 'asc')->first();
        $address->postcode = $request->postcode;
        if($super_admin_id){
            $address->user_id = $super_admin_id->id;
            $address->save();
        }
        return redirect()->route('pick_up.index');
    }

    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        $model = Address::find($id);
        $address = '';
        $city = '';
        $region = '';
        if($model->cities){
            if($model->cities->type == 'district'){
                $city = $model->cities->name??'';
                if($model->cities->region){
                    $region = $model->cities->region->name??'';
                }
            }else{
                $region = $model->cities->name??'';
            }
        }

        $address = $region.' '.$city;
        return view('pick-up.show', ['model'=>$model, 'address'=>$address]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $address = Address::find($id);
        return view('pick-up.edit', ['address'=>$address]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $address = Address::find($id);
        $address->name = $request->name;
        if($request->district){
            $address->city_id = $request->district;
        }elseif($request->region_id){
            $address->city_id = $request->region;
        }
        $super_admin_id = User::select('id')->where('is_admin', 1)->orderBy('created_at', 'asc')->first();
        $address->postcode = $request->postcode;
        if($super_admin_id){
            $address->user_id = $super_admin_id->id;
            $address->save();
        }
        return redirect()->route('pick_up.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $address = Address::find($id);
        $address->delete();
        return redirect()->route('pick_up.index');
    }
}
