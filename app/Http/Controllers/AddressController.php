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
        $language = $request->header('language')??'en';
        $data = Cities::with([
            'getTranslatedModel' => function ($query) use ($language) {
                $query->where('lang', $language);
            },
            'getDistricts',
            'getDistricts.getTranslatedModel' => function ($query) use ($language) {
                $query->where('lang', $language);
            },
        ])->where('parent_id', 0)->orderBy('id', 'ASC')->get()->map(function($city){
            $cities_ = $city->getDistricts->map(function($district){
                return [
                    'id' => $district->id,
                    'name' => optional($district->getTranslatedModel)->name??($city->name??''),
                    'lat' => $district->lat,
                    'long' => $district->lng,
                ];
            });
            return [
                'id' => $city->id,
                'name' => optional($city->getTranslatedModel)->name??($city->name??''),
                'lat'=>$city->lat,
                'long'=>$city->lng,
                'cities'=>$cities_->toArray(),
            ];
        });
        if($data->isNotEmpty()){
            return $this->success('Success', 200, $data->toArray());
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
        $language = $request->header('language') ?? 'ru';
        
        $user = Auth::user()->load([
            "addresses", 
            "addresses.cities",
            "addresses.cities.getTranslatedModel" => function ($query) use ($language) {
                $query->where('lang', $language);
            },
            "addresses.cities.region",
            "addresses.cities.region.getTranslatedModel" => function ($query) use ($language) {
                $query->where('lang', $language);
            },
            "addresses.cities.region.getDistricts",
            "addresses.cities.region.getDistricts.getTranslatedModel" => function ($query) use ($language) {
                $query->where('lang', $language);
            }
        ]);
        $address_data = $user->addresses->map(function($address_){
            $city = [];
            $region = [];
            $region_city = [];
            $city_translate = $address_->cities;
            if($city_translate){
                if($city_translate->parent_id != '0'){
                    $city = [
                        'id' => $city_translate->id,
                        'name' => optional($city_translate->getTranslatedModel)->name??'',
                        'lat' => $city_translate->lat??'',
                        'long' => $city_translate->lng??'',
                    ];
                    if($city_translate->region){
                        $region = [
                            'id' => $city_translate->region->id,
                            'name' => optional($city_translate->region->getTranslatedModel)->name??'',
                            'lat' => $city_translate->region->lat??'',
                            'long' => $city_translate->region->lng??'',
                        ];
                        $region_city = $city_translate->region->getDistricts->map(function($regionCity){
                            return [
                                'id' => $regionCity->id,
                                'name' => optional($regionCity->getTranslatedModel)->name??'',
                                'lat' => $regionCity->lat??'',
                                'long' => $regionCity->lng??'',
                            ];
                        });
                    }
                }else{
                    $region = [
                        'id' => $city_translate->id,
                        'name' => optional($city_translate->getTranslatedModel)->name??'',
                        'lat' => $city_translate->lat??'',
                        'long' => $city_translate->lng??'',
                    ];
                }
            }
            
            return [
                'id'=>$address_->id,
                'name'=>$address_->name??'',
                'region'=>$region,
                'city'=>$city,
                'region_cities'=>$region_city,
                'latitude'=>$address_->latitude??null,
                'longitude'=>$address_->longitude??null,
                'postcode'=>$address_->postcode??null,
            ];
        });
        if($address_data->isNotEmpty()){
            return $this->success('Success', 200, $address_data->toArray());
        }else{
            return $this->error('No address', 400);
        }
    }

    public function getPickUpAddress(Request $request){
        $super_admins_id = User::where('is_admin', 1)->pluck('id')->all();
        $language = $request->header('language')??'en';
        $addresses = Address::with([
            "cities",
            "cities.getTranslatedModel" => function ($query) use ($language) {
                $query->where('lang', $language);
            },
            "cities.region",
            "cities.region.getTranslatedModel" => function ($query) use ($language) {
                $query->where('lang', $language);
            },
            "cities.region.getDistricts",
            "cities.region.getDistricts.getTranslatedModel" => function ($query) use ($language) {
                $query->where('lang', $language);
            }
        ])->whereIn('user_id', $super_admins_id)->get();
        $address_data = $addresses->map(function($address_){
            $city = [];
            $region = [];
            $region_city = [];
            $city_translate = optional($address_->cities->getTranslatedModel)->name??'';
            if($address_->cities){
                if($address_->cities->parent_id != '0'){
                    $city = [
                        'id' => $address_->cities->id,
                        'name' => optional($address_->cities->getTranslatedModel)->name??'',
                        'lat' => $address_->cities->lat??'',
                        'long' => $address_->cities->lng??'',
                    ];
                    if($address_->cities->region){
                        $region = [
                            'id' => $address_->cities->region->id,
                            'name' => optional($address_->cities->region->getTranslatedModel)->name??'',
                            'lat' => $address_->cities->region->lat??'',
                            'long' => $address_->cities->region->lng??'',
                        ];
                        $region_city = $address_->cities->region->getDistricts->map(function($regionCity){
                            return [
                                'id' => $regionCity->id,
                                'name' => optional($regionCity->getTranslatedModel)->name??'',
                                'lat' => $regionCity->lat??'',
                                'long' => $regionCity->lng??'',
                            ];
                        });
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
            
            return [
                'id'=>$address_->id,
                'name'=>$address_->name??'',
                'region'=>$region,
                'city'=>$city,
                'region_cities'=>$region_city,
                'latitude'=>$address_->latitude??null,
                'longitude'=>$address_->longitude??null,
                'postcode'=>$address_->postcode??null,
            ];
        });
        if($address_data->isNotEmpty()){
            return $this->success('Success', 200, $address_data->toArray());
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
