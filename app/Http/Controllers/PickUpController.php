<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class PickUpController extends Controller
{
    public $current_page = 'pick-up';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getCommonData = $this->getCommonData();
        $super_admins_id = User::where('is_admin', 1)->pluck('id')->all();
        $addresses_ = Address::with([
            'cities',
            'cities.region',
        ])->whereIn('user_id', $super_admins_id)->get();
        $addresses = [];
        foreach ($addresses_ as $model){
            $city = '';
            $region = '';
            if($model->cities){
                if($model->cities->parent_id != '0'){
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

        return view('pick-up.index', array_merge(['addresses'=>$addresses, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $getCommonData = $this->getCommonData();
        return view('pick-up.create', array_merge($getCommonData, ['current_page'=>$this->current_page]));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $address = new Address();
        $address->name = $request->name;
        if($request->district){
            $address->city_id = $request->district;
        }elseif($request->region){
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
     * Display the specified resource.
     */

    public function show(string $id)
    {
        $getCommonData = $this->getCommonData();
        $model = Address::with([
            'cities',
            'cities.region'
        ])->find($id);
        $city = '';
        $region = '';
        if($model->cities){
            if($model->cities->parent_id != '0'){
                $city = $model->cities->name??'';
                if($model->cities->region){
                    $region = $model->cities->region->name??'';
                }
            }else{
                $region = $model->cities->name??'';
            }
        }

        $address = $region.' '.$city;
        return view('pick-up.show', array_merge(['model'=>$model, 'address'=>$address, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $getCommonData = $this->getCommonData();
        $address = Address::find($id);
        return view('pick-up.edit', array_merge(['address'=>$address, 'current_page'=>$this->current_page], $getCommonData));
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
