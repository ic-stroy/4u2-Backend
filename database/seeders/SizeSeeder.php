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
    public $all_sizes = ['S', 'M', 'L', 'X', 'XL', 'XXL', 'XXXL', 'XXXXL', '6-7 years', '8-10 years', '11-13 years'];


    public function run(): void
    {
        $categories = Category::withTrashed()->where('step', 0)->select('id')->get();
        $sizes = Sizes::withTrashed()->select('id', 'deleted_at')->orderBy('id', 'desc')->first();
        if(!isset($sizes->id)){
            $last_size_id = isset($sizes->id)?$sizes->id:0;
            $size_array = [];
            foreach ($categories as $category){
                if($category->name != 'Accessories'){
                    foreach ($this->all_sizes as $all_size){
                        $last_size_id++;
                        $size_array[] = [
                            'id'=>$last_size_id,
                            'name'=>$all_size,
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
