<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cities;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AddressController extends Controller
{
    public function getCities(Request $request){
        $language = $request->header('language');
        $cities = Cities::where('parent_id', 0)->orderBy('id', 'ASC')->get();
        $data = [];
        foreach ($cities as $city){
            $city_translate = table_translate($city,'city',$language);
            $cities_ = [];
            foreach ($city->getDistricts as $district){
                $district_translate = table_translate($district,'city',$language);
                $cities_[] = [
                    'id'=>$district->id,
                    'name'=>$district_translate,
                    'lat'=>$district->lat,
                    'long'=>$district->lng
                ];
            }
            $data[] = [
                'id'=>$city->id,
                'name'=>$city_translate,
                'lat'=>$city->lat,
                'long'=>$city->lng,
                'cities'=>$cities_,
            ];
        }
        if(count($data)>0){
            return $this->success('Success', 200, $data);
        }else{
            return $this->error('No cities', 400);
        }
    }

    public function setAddress(Request $request){
        $user = Auth::user();
        $address_count = Address::where('user_id', $user->id)->count();
        if($address_count < 4){
            $address = new Address();
            $cities = Cities::find($request->city_id);
            if(!$cities){
                return $this->error('City or Region not found', 400);
            }
            $address->city_id = $request->city_id;
            $address->name = $request->name;
            $address->user_id = $user->id;
            $address->latitude = $request->latitude;
            $address->longitude = $request->longitude;
            $address->save();
        }else{
            return $this->success("Number of your addresses has reached the limit", 200, []);
        }
        return $this->success('Success', 200, []);
    }

    public function editAddress(Request $request){
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->find($request->id);
        if(!$address){
            return $this->error('Address not found', 400);
        }
        $cities = Cities::find($request->city_id);
        if(!$cities){
            return $this->error('City or Region not found', 400);
        }
        $address->city_id = $request->city_id;
        if($request->name != ''){
            $address->name = $request->name;
        }
        $address->user_id = $user->id;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->save();
        return $this->success('Success', 200);
    }

    public function getAddress(Request $request){
//        $response = Http::get(asset("assets/json/cities.json"));
//        $cities = json_decode($response);
//        foreach ($cities as $city){
//            if(!Cities::where('name', $city->region)->exists()){
//                $model_region = new Cities();
//                $model_region->name = $city->region;
//                $model_region->type = 'region';
//                $model_region->parent_id = 0;
//                $model_region->lng = $city->long;
//                $model_region->lat = $city->lat;
//                $model_region->save();
//                foreach ($city->cities as $city_district){
//                    $model = new Cities();
//                    $model->name = $city_district->name;
//                    $model->type = 'district';
//                    $model->parent_id = $model_region->id;
//                    $model->lng = $city_district->long;
//                    $model->lat = $city_district->lat;
//                    $model->save();
//                }
//            }else{
//                $model_region = Cities::where('name', $city->region)->first();
//                $model_region->lng = $city->long;
//                $model_region->lat = $city->lat;
//                $model_region->save();
//            }
//        }
//        return response()->json('good');

        $language = $request->header('language');
        $user = Auth::user();
        $address = [];
        $city = [];
        $region = [];
        foreach ($user->addresses as $address_) {
            $region_city = [];
            if($address_->cities){
                $city_translate = table_translate($address_->cities,'city',$language);
                if($address_->cities->type == 'district'){
                    $city = [
                        'id' => $address_->cities->id,
                        'name' => $city_translate??'',
                        'lat' => $address_->cities->lat??'',
                        'long' => $address_->cities->lng??'',
                    ];
                    if($address_->cities->region){
                        $region_translate = table_translate($address_->cities->region,'city',$language);
                        $region = [
                            'id' => $address_->cities->region->id,
                            'name' => $region_translate??'',
                            'lat' => $address_->cities->region->lat??'',
                            'long' => $address_->cities->region->lng??'',
                        ];
                        if(!$address_->cities->region->getDistricts->isEmpty()){
                            foreach($address_->cities->region->getDistricts as $regionCity){
                                $region_city_translate = table_translate($regionCity,'city',$language);
                                $region_city[] = [
                                    'id' => $regionCity->id,
                                    'name' => $region_city_translate??'',
                                    'lat' => $regionCity->lat??'',
                                    'long' => $regionCity->lng??'',
                                ];
                            }
                        }
                    }
                }else{
                    $region = [
                        'id' => $address_->cities->id,
                        'name' => $city_translate??'',
                        'lat' => $address_->cities->lat??'',
                        'long' => $address_->cities->lng??'',
                    ];
                }
            }

            $address[] = [
                'id'=>$address_->id,
                'name'=>$address_->name??'',
                'region'=>$region,
                'city'=>$city,
                'region_cities'=>$region_city,
                'latitude'=>$address_->latitude??null,
                'longitude'=>$address_->longitude??null,
                'postcode'=>$address_->postcode??null,
            ];
        }
        if(!empty($address)){
            return $this->success('Success', 200, $address);
        }else{
            return $this->error('No address', 400);
        }
    }
    public function getPickUpAddress(Request $request){
        $address = [];
        $city = [];
        $region = [];
        $super_admins_id = User::where('is_admin', 1)->pluck('id')->all();
        $addresses = Address::whereIn('user_id', $super_admins_id)->get();
        $language = $request->header('language');
        foreach ($addresses as $address_) {
            $region_city = [];
            if($address_->cities){
                $city_translate = table_translate($address_->cities,'city',$language);
                if($address_->cities->type == 'district'){
                    $city = [
                        'id' => $address_->cities->id,
                        'name' => $city_translate??'',
                        'lat' => $address_->cities->lat??'',
                        'long' => $address_->cities->lng??'',
                    ];
                    if($address_->cities->region){
                        $region_translate = table_translate($address_->cities->region,'city',$language);
                        $region = [
                            'id' => $address_->cities->region->id,
                            'name' => $region_translate??'',
                            'lat' => $address_->cities->region->lat??'',
                            'long' => $address_->cities->region->lng??'',
                        ];
                        if(!$address_->cities->region->getDistricts->isEmpty()){
                            foreach($address_->cities->region->getDistricts as $regionCity){
                                $region_city_translate = table_translate($regionCity,'city',$language);
                                $region_city[] = [
                                    'id' => $regionCity->id,
                                    'name' => $region_city_translate??'',
                                    'lat' => $regionCity->lat??'',
                                    'long' => $regionCity->lng??'',
                                ];
                            }
                        }
                    }
                }else{
                    $region = [
                        'id' => $address_->cities->id,
                        'name' => $city_translate??'',
                        'lat' => $address_->cities->lat??'',
                        'long' => $address_->cities->lng??'',
                    ];
                }
            }

            $address[] = [
                'id'=>$address_->id,
                'name'=>$address_->name??'',
                'region'=>$region,
                'city'=>$city,
                'region_cities'=>$region_city,
                'latitude'=>$address_->latitude??null,
                'longitude'=>$address_->longitude??null,
                'postcode'=>$address_->postcode??null,
            ];
        }
        if(!empty($address)){
            return $this->success('Success', 200, $address);
        }else{
            return $this->error('No address', 400);
        }
    }

    public function destroy(Request $request){
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->find($request->id);
        if($address){
            if($address->order){
                return $this->success(translate('prohibited'), 200);
            }
            $address->delete();
            return $this->success('Success', 200);
        }else{
            return $this->error('No address', 400);
        }
    }
}
