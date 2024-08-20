<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryTranslations;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $datas = [];
        foreach ($categories as $category){
            foreach (Language::all() as $language) {
                if(!CategoryTranslations::where(['lang' => $language->code, 'category_id' => $category->id])->exists()){
                    $datas[] = [
                        'name'=>$category->name,
                        'category_id'=>$category->id,
                        'lang' => $language->code
                    ];
                }
            }
        }
        foreach ($datas as $data){
            CategoryTranslations::create($data);
        }
    }
}
