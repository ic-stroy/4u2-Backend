<?php

namespace Database\Seeders;

use App\Constants;
use App\Models\Category;
use App\Models\Sizes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public $all_clothes_sizes = ['S', 'M', 'L', 'X', 'XL', 'XXL', 'XXXL', 'XXXXL', '6-7 years', '8-10 years', '11-13 years'];
    public $all_shoes_sizes = ['S', 'M', 'L', 'X', 'XL', 'XXL', 'XXXL', 'XXXXL', '6-7 years', '8-10 years', '11-13 years'];


    public function run(): void
    {
        $categories = Category::withTrashed()->where('step', 0)->select('id', 'name')->get();
        $sizes = Sizes::withTrashed()->select('id', 'deleted_at')->orderBy('id', 'desc')->first();
        if(!$sizes){
            $last_size_id = isset($sizes->id)?$sizes->id:0;
            $size_array = [];
            foreach ($categories as $category){
                if($category->name == 'Clothes'){
                    foreach ($this->all_clothes_sizes as $all_cloth_size){
                        $last_size_id++;
                        $size_array[] = [
                            'id'=>$last_size_id,
                            'name'=>$all_cloth_size,
                            'category_id'=>$category->id,
                            'status'=>Constants::ACTIVE,
                        ];
                    }
                }
                if($category->name == 'Shoes'){
                    foreach ($this->all_shoes_sizes as $all_shoes_size){
                        $last_size_id++;
                        $size_array[] = [
                            'id'=>$last_size_id,
                            'name'=>$all_shoes_size,
                            'category_id'=>$category->id,
                            'status'=>Constants::ACTIVE,
                        ];
                    }
                }
            }
            foreach($size_array as $size){
                Sizes::create($size);
            }
        }else{
            if(!isset($sizes->deleted_at)){
                echo "Size is exist status deleted";
            }else{
                echo "Size is exist status active";
            }
        }
    }
}
