<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public $sub_sub_categories = ['Sneaker', 'T-shirt', 'Sweater', 'Jacket', 'Cap', 'Trousers', 'Boots', 'Dress'];
    public $sub_categories = ['Unisex', 'Women', 'Men', 'Boys and girls', 'Boys', 'Girls'];
    public $categories = ['Shoes', 'Clothes', 'Beauty and healty', 'Medicine and vitamins', 'Accessories', 'Toys'];

    public function run(): void
    {
        $category_id = Category::withTrashed()->select('id', 'deleted_at')->orderBy('id', 'desc')->first();
        if(!isset($category_id->id)){
            $category_id_ = isset($category_id->id)?$category_id->id:0;
            $sub_category_id_ = 0;
            $last_category_id = -1;
            foreach ($this->categories as $key => $category){
                $category_id_++;
                $all_categories[] = ['id'=>$category_id_, 'name'=>$category, 'step'=>0, 'parent_id'=>0];
                if($key < 2){
                    if($last_category_id < $sub_category_id_){
                        $sub_category_id_ = $category_id_ + count($this->categories)-1;
                    }else{
                        $sub_category_id_ = $last_category_id;
                    }
                    foreach ($this->sub_categories as $sub_category){
                        $sub_category_id_++;
                        $last_category_id = $sub_category_id_;
                        $all_sub_categories[] = ['id'=>$sub_category_id_, 'name'=>$sub_category, 'step'=>1, 'parent_id'=>$category_id_];
                    }
                }
            }
            $sub_sub_category_id_ = $last_category_id;
            foreach($all_sub_categories as $subCategory){
                foreach ($this->sub_sub_categories as $sub_sub_category){
                    $sub_sub_category_id_++;
                    $all_sub_sub_categories[] = ['id'=>$sub_sub_category_id_, 'name'=>$sub_sub_category, 'step'=>2, 'parent_id'=>$subCategory['id']];
                }
            }
            $all_categories_ = array_merge($all_categories, $all_sub_categories, $all_sub_sub_categories);
            foreach ($all_categories_ as $all_category){
                Category::create($all_category);
            }
        }else{
            if(!isset($category_id->deleted_at)){
                echo "Category is exist status deleted";
            }else{
                echo "Category is exist status active";
            }
        }
    }
}
