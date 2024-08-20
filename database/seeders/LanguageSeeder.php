<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            foreach ($datas as $data){
                Language::create($data);
            }
        }else{
            echo "Language is exist status active";
        }
    }
}
