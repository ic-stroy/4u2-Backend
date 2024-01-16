<?php

namespace Database\Seeders;

use App\Models\Cities;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $city_last = Cities::select('id')->orderBy('id', 'desc')->first();
        $response = Http::get(asset("assets/json/cities.json"));
        $cities = json_decode($response);
        $city_id = isset($city_last->id)?$city_last->id:0;
        foreach ($cities as $city){
            $city_id++;
            $region_array[] = [
                'name'=>$city->region,
                'type'=>'region',
                'parent_id'=>0
            ];
            foreach ($city->cities as $city_district){
                $district_array[] = [
                  [
                      'name'=>$city_district->name,
                      'type'=>'district',
                      'parent_id'=>$city_id,
                      'lng'=>$city_district->long,
                      'lat'=>$city_district->lat,
                  ]
                ];
            }
        }
        $all_cities = array_merge($region_array, $district_array);
        foreach ($all_cities as $all_city){
            Cities::create($all_city);
        }
    }
}
