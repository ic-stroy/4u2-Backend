<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\ColorTranslations;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = Color::all();
        $datas = [];
        foreach ($colors as $color){
            foreach (Language::all() as $language) {
                if(!ColorTranslations::where(['lang' => $language->code, 'color_id' => $color->id])->exists()){
                    $datas[] = [
                        'name'=>$color->name,
                        'color_id'=>$color->id,
                        'lang' => $language->code
                    ];
                }
            }
        }
        foreach ($datas as $data){
            ColorTranslations::create($data);
        }
    }
}
