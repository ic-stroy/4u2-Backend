<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryTranslations;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category_translations = CategoryTranslations::first();
        if(!$category_translations){
            $languages = Language::get();
            $datas = Category::get()->map(function($category) use ($languages){
                return $languages->map(function($language) use ($category){
                    return [
                        'name' => $category->name,
                        'category_id' => $category->id,
                        'lang' => $language->code
                    ];
                });
            })->collapse();
            DB::table('category_translations')->insert($datas->toArray());
        }
    }
}
