<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\ColorTranslations;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = Color::get();
        $datas = [];
        $languages = Language::get();
        foreach ($colors as $color){
            foreach ($languages as $language) {
                if(!ColorTranslations::where(['lang' => $language->code, 'color_id' => $color->id])->exists()){
                    $datas[] = [
                        'name'=>$color->name,
                        'color_id'=>$color->id,
                        'lang' => $language->code
                    ];
                }
            }
        }
        DB::table('color_translations')->insert($datas);
    }
}
