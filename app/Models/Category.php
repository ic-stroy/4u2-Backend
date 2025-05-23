<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use function Laravel\Prompts\select;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'categories';

    protected $fillable = [
        'id',
        'name',
        'parent_id',
        'step',  //0 Category  1 Subcategory  2 SubSubCategory
    ];

    public function subcategory(){
        return $this->hasmany(Category::class, 'parent_id', 'id')->where('step', 1);
    }
    public function subcategoriesId(){
        return $this->hasmany(Category::class, 'parent_id', 'id')->select('id', 'parent_id')->where('step', 1);
    }
    public function sub_category(){
        return $this->hasone(Category::class, 'id', 'parent_id')->where('step', 1);
    }
    public function category(){
        return $this->hasone(Category::class, 'id', 'parent_id')->where('step', 0);
    }
    public function subsubcategory(){
        return $this->hasmany(Category::class, 'parent_id', 'id')->select('id', 'name', 'parent_id')->where('step', 2);
    }
    public function subsubcategory_(){
        return $this->hasmany(Category::class, 'parent_id', 'id')->where('step', 2);
    }
    public function sub_sub_category(){
        return $this->hasone(Category::class, 'id', 'parent_id')->where('step', 2);
    }
    public function sizes(){
        return $this->hasMany(Sizes::class, 'category_id', 'id');
    }
    public function product(){
        return $this->hasOne(Products::class, 'category_id','id');
    }

    public function products(){
        return $this->hasMany(Products::class, 'category_id', 'id');
    }

    public function getTranslatedContent(){
        return $this->hasOne(CategoryTranslations::class, 'category_id', 'id')->where('lang', app()->getLocale());
    }

    public function getTranslatedModel()
    {
        return $this->hasOne(CategoryTranslations::class, 'category_id', 'id')->select('id', 'category_id', 'name');
    }
}
