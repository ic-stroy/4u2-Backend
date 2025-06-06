<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Products;
use App\Models\ProductTranslations;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Products::all();
        $datas = [];
        foreach ($products as $product){
            foreach (Language::get() as $language) {
                if(!ProductTranslations::where(['lang' => $language->code, 'product_id' => $product->id])->exists()){
                    $datas[] = [
                        'name'=>$product->name,
                        'product_id'=>$product->id,
                        'lang' => $language->code
                    ];
                }
            }
        }
        DB::table('product_translations')->insert($datas);
    }
}
