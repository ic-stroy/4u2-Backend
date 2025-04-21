<?php

namespace Database\Seeders;

use App\Models\Cities;
use App\Models\CityTranslations;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CityTranslationSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $city_translations = CityTranslations::first();
        if(!$city_translations){
            $languages = Language::get();
            $datas = Cities::get()->map(function($city) use($languages){
                return $languages->map(function($language) use($city){
                    return [
                        'name'=>$city->name,
                        'city_id'=>$city->id,
                        'lang' => $language->code
                    ];
                });
            })->collapse();
            DB::table('city_translations')->insert($datas->toArray());
        }
        
    }
}
