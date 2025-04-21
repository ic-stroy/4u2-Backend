<?php

namespace App\Models;

use App\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';
    protected $fillable = [
        'id',
        'name',
        'category_id',
        'status',
        'description',
        'company',
    ];
    public function category(){
        return $this->hasOne(Category::class, 'id','category_id')->where('step', 0);
    }
    public function subCategory(){
        return $this->hasOne(Category::class, 'id','category_id')->where('step', 1);
    }
    public function subSubCategory(){
        return $this->hasOne(Category::class, 'id','category_id')->where('step', 2);
    }
    public function categoryDiscount()
    {
        return $this->hasOne(Discount::class, 'category_id','category_id')->where('start_date', '<=', date('Y-m-d H:i:s'))->where('end_date', '>=', date('Y-m-d H:i:s'));
    }

    public function discount()
    {
        return $this->hasOne(Discount::class, 'product_id','id')->where('start_date', '<=', date('Y-m-d H:i:s'))->where('end_date', '>=', date('Y-m-d H:i:s'));
    }
    public function category_(){
        return $this->hasOne(Category::class, 'parent_id','category_id');
    }
    public function getCategory(){
        return $this->hasOne(Category::class, 'id','category_id');
    }
    public function categorizedProducts(){
        return $this->hasMany(CharacterizedProducts::class, 'product_id', 'id');
    }

    public function getTranslatedContent(){
        return $this->hasOne(ProductTranslations::class, 'product_id', 'id')->select('id', 'product_id', 'name')->where('lang', app()->getLocale());
    }

    public function getTranslatedModel()
    {
        return $this->hasOne(ProductTranslations::class, 'product_id', 'id')->select('id', 'product_id', 'name');
    }

    public function getTranslatedDiscriptionContent(){
        return $this->hasOne(ProductDescriptionTranslations::class, 'product_id', 'id')->select('id', 'product_id', 'name')->where('lang', app()->getLocale());
    }

    public function getTranslatedDescriptionModel()
    {
        return $this->hasOne(ProductDescriptionTranslations::class, 'product_id', 'id')->select('id', 'product_id', 'name');
    }

    public function productDescriptionUz(){
        return $this->hasOne(ProductDescriptionTranslations::class, 'product_id', 'id')->where('lang', 'uz');
    }
    public function productDescriptionRu(){
        return $this->hasOne(ProductDescriptionTranslations::class, 'product_id', 'id')->where('lang', 'ru');
    }
    public function productDescriptionEn(){
        return $this->hasOne(ProductDescriptionTranslations::class, 'product_id', 'id')->where('lang', 'en');
    }

}
