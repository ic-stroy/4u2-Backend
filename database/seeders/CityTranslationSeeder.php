<?php

namespace Database\Seeders;

use App\Models\Cities;
use App\Models\CityTranslations;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CityTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = Cities::all();
        $datas = [];
        foreach ($cities as $city){
            foreach (Language::all() as $language) {
                if(!CityTranslations::where(['lang' => $language->code, 'city_id' => $city->id])->exists()){
                    $datas[] = [
                        'name'=>$city->name,
                        'city_id'=>$city->id,
                        'lang' => $language->code
                    ];
                }
            }
        }
        foreach ($datas as $data){
            CityTranslations::create($data);
        }
    }
}
