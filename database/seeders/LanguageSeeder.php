<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $language = Language::first();
        if(!$language){
            $datas = [
                [
                    'id'=>1,
                    'name'=>'uz',
                    'code'=>'uz',
                ],
                [
                    'id'=>2,
                    'name'=>'ru',
                    'code'=>'ru',
                ],
                [
                    'id'=>3,
                    'name'=>'en',
                    'code'=>'en',
                ],
            ];
            DB::table('languages')->insert($datas);
        }else{
            echo "Language is exist status active";
        }
    }
}
