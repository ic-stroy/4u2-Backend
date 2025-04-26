<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\CharacterizedProducts;
use App\Models\Coupon;
use App\Models\Discount;
use App\Models\Language;
use App\Models\Order;
use App\Models\PaymentStatus;
use App\Models\ProductDescriptionTranslations;
use App\Models\Products;
use App\Models\ProductTranslations;
use App\Models\Sizes;
use App\Models\Category;
use App\Service\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public $current_page = 'products';

    public function __construct(public ProductService $productService){

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getCommonData = $this->getCommonData();
        $categories = Category::with([
            'subcategory',
            'subcategory.products',
            'subcategory.subsubcategory',
            'subcategory.subsubcategory.products',
            'products',
        ])->where('step', 0)->get();
        $all_products = [];
        foreach($categories as $category){
            $all_products[$category->id] = $category->products;
            foreach($category->subcategory as $subcategory){
                $all_products[$category->id] = $all_products[$category->id]->merge($subcategory->products);
                $all_products[$subcategory->id] = $subcategory->products;
                foreach($subcategory->subsubcategory as $subsubcategory){
                    $all_products[$category->id] = $all_products[$category->id]->merge($subsubcategory->products);
                    $all_products[$subcategory->id] = $all_products[$subcategory->id]->merge($subsubcategory->products);
                    $all_products[$subsubcategory->id] = $subsubcategory->products;
                }
            }
        }
        return view('products.index', array_merge(['all_products'=> $all_products, 'categories'=> $categories, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $getCommonData = $this->getCommonData();
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        return view('products.create', array_merge(['categories'=> $categories, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Products();
        $model->name = $request->name;
        if(isset($request->subsubcategory_id)){
            $model->category_id = $request->subsubcategory_id;
        }elseif($request->subcategory_id){
            $model->category_id = $request->subcategory_id;
        }else{
            $model->category_id = $request->category_id;
        }
        $model->company = $request->company;
        $model->status = $request->status;
        $model->sum = $request->sum;
        $model->description = $request->description_uz;
        $images = $request->file('images');
        $model->images = $this->imageSave($model, $images, 'store');
        $model->save();
        $product_description_translations = ProductDescriptionTranslations::firstOrNew(['lang' => 'uz', 'product_id' => $model->id]);
        $product_description_translations->lang = 'uz';
        if($request->description_uz){
            $product_description_translations->name = $request->description_uz;
        }
        $product_description_translations->product_id = $model->id;
        $product_description_translations->save();
        $product_description_translations = ProductDescriptionTranslations::firstOrNew(['lang' => 'ru', 'product_id' => $model->id]);
        $product_description_translations->lang = 'ru';
        if(!$request->description_ru){
            $product_description_translations->name = $request->description_uz;
        }else{
            $product_description_translations->name = $request->description_ru;
        }
        $product_description_translations->product_id = $model->id;
        $product_description_translations->save();
        $product_description_translations_en = ProductDescriptionTranslations::firstOrNew(['lang' => 'en', 'product_id' => $model->id]);
        $product_description_translations_en->lang = 'en';
        if(!$request->description_en){
            $product_description_translations_en->name = $request->description_uz;
        }else{
            $product_description_translations_en->name = $request->description_en;
        }
        $product_description_translations_en->product_id = $model->id;
        $product_description_translations_en->save();
        $languages = Language::get();
        foreach ($languages as $language) {
            $product_translations = ProductTranslations::firstOrNew(['lang' => $language->code, 'product_id' => $model->id]);
            $product_translations->lang = $language->code;
            $product_translations->name = $model->name;
            $product_translations->product_id = $model->id;
            $product_translations->save();
        }
        return redirect()->route('product.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getCommonData = $this->getCommonData();
        $model = Products::with([
            'category',
            'subCategory.category',
            'subSubCategory.sub_category',
            'subSubCategory.sub_category.category',
            'category.getTranslatedContent',
            'subCategory.category.getTranslatedContent',
            'subSubCategory.sub_category.getTranslatedContent',
            'subSubCategory.sub_category.category.getTranslatedContent',
        ])->find($id);
        $category_array = [];
        if($model->category){
            $category_ = optional($model->category->getTranslatedContent)->name??$model->category->name;
            $category_array = [$category_];
        }elseif($model->subCategory){
            $category_ = optional(optional($model->subCategory->category)->getTranslatedContent)->name??(optional($model->subCategory->category)->name??'');
            $sub_category_ = optional($model->subCategory->getTranslatedContent)->name??'';
            if($category_ != ''){
                $category_array = [$category_, $sub_category_];
            }else{
                $category_array = [$sub_category_];
            }
        }elseif($model->subSubCategory){
            $sub_sub_category_ = optional($model->subSubCategory->getTranslatedContent)->name??'';
            if($model->subSubCategory->sub_category){
                $category_ = optional(optional($model->subSubCategory->sub_category->category)->getTranslatedContent)->name??'';
                $sub_category_ = optional($model->subSubCategory->sub_category->getTranslatedContent)->name??'';
                if($category_ != ''){
                    $category_array = [$category_, $sub_category_, $sub_sub_category_];
                }else{
                    $category_array = [$sub_category_, $sub_sub_category_];
                }
            }else{
                $category_array = [$sub_sub_category_];
            }
        }

        return view('products.show', array_merge([
            'model'=>$model,
            'category_array'=> $category_array, 'current_page'=>$this->current_page
        ], $getCommonData));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $getCommonData = $this->getCommonData();
        $product = Products::with([
            'subCategory',
            'subCategory.category',
            'subSubCategory.sub_category',
            'subSubCategory.sub_category.category',
        ])->find($id);
        if($product->subCategory){
            $category_product = $product->subCategory;
            $is_category = 2;
            $current_category = $category_product->category??'no';
            $current_sub_category_id = $category_product->id??'no';
            $current_sub_sub_category_id = 'no';
        }elseif($product->category){
            $category_product = $product->category;
            $is_category = 1;
            $current_category = $category_product;
            $current_sub_category_id = 'no';
            $current_sub_sub_category_id = 'no';
        }elseif($product->subSubCategory) {
            $category_product = $product->subSubCategory;
            $is_category = 3;
            if($category_product->sub_category){
                if($category_product->sub_category->category){
                    $current_category = $category_product->sub_category->category;
                }else{
                    $current_category = 'no';
                }
                $current_sub_category_id = optional($category_product->sub_category)->id ?? 'no';
            }else{
                $current_category = 'no';
                $current_sub_category_id = 'no';
            }
            $current_sub_sub_category_id = optional($category_product)->id ?? 'no';
        }else{
            $category_product = 'no';
            $is_category = 0;
            $current_category = 'no';
            $current_sub_category_id = 'no';
            $current_sub_sub_category_id = 'no';
        }
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        return view('products.edit', array_merge([
            'product'=> $product, 'categories'=> $categories,
            'category_product'=> $category_product, 'is_category'=>$is_category,
            'current_category' => $current_category,
            'current_sub_category_id' => $current_sub_category_id,
            'current_sub_sub_category_id' => $current_sub_sub_category_id, 'current_page'=>$this->current_page
        ], $getCommonData));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Products::with([
            'categoryDiscount',
            'discount'
        ])->find($id);
        if(isset($request->subsubcategory_id)){
            $model->category_id = $request->subsubcategory_id;
        }elseif($request->subcategory_id){
            $model->category_id = $request->subcategory_id;
        }else{
            $model->category_id = $request->category_id;
        }
        $model->company = $request->company;
        $model->status = $request->status;
        $model->sum = $request->sum;
        $images = $request->file('images');
        $model->images = $this->imageSave($model, $images, 'update');
        if($request->name != $model->name){
            $languages = Language::get();
            foreach ($languages as $language) {
                $product_translations = ProductTranslations::firstOrNew(['lang' => $language->code, 'product_id' => $model->id]);
                $product_translations->lang = $language->code;
                $product_translations->name = $request->name;
                $product_translations->product_id = $model->id;
                $product_translations->save();
            }

        }
        $product_description_translations_uz = ProductDescriptionTranslations::firstOrNew(['lang' => 'uz', 'product_id' => $model->id]);
        $product_description_translations_uz->lang = 'uz';
        $product_description_translations_uz->product_id = $model->id;
        if($request->description_uz){
            $product_description_translations_uz->name = $request->description_uz;
        }
        $product_description_translations_uz->save();
        $product_description_translations_ru = ProductDescriptionTranslations::firstOrNew(['lang' => 'ru', 'product_id' => $model->id]);
        $product_description_translations_ru->lang = 'ru';
        $product_description_translations_ru->product_id = $model->id;
        if(!$request->description_ru){
            $product_description_translations_ru->name = $request->description_uz;
        }else{
            $product_description_translations_ru->name = $request->description_ru;
        }
        $product_description_translations_ru->save();
        $product_description_translations_en = ProductDescriptionTranslations::firstOrNew(['lang' => 'en', 'product_id' => $model->id]);
        $product_description_translations_en->lang = 'en';
        if(!$request->description_en){
            $product_description_translations_en->name = $request->description_uz;
        }else{
            $product_description_translations_en->name = $request->description_en;
        }
        $product_description_translations_en->product_id = $model->id;
        $product_description_translations_en->save();
        $model->description = $request->description_uz;
        $model->name = $request->name;
        $model->save();
        if(!empty($model->categoryDiscount)){
            if(empty($model->discount)){
                $discount = new Discount();
                $discount->percent = $model->categoryDiscount->percent;
                $discount->start_date = $model->categoryDiscount->start_date;
                $discount->end_date = $model->categoryDiscount->end_date;
                $discount->category_id = $model->category_id;
                $discount->product_id = $model->id;
                $discount->discount_number = $model->categoryDiscount->discount_number;
                $discount->save();
            }
        }
        return redirect()->route('product.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Products::with('warehouses')->find($id);
        if(!$model->warehouses->isEmpty()){
            return redirect()->back()->with('error', translate('You cannot delete this product because here is some products in warehouse'));
        }
        if($model->images){
            $images = json_decode($model->images);
            foreach ($images as $image){
                $avatar_main = storage_path('app/public/products/'.$image);
                if(file_exists($avatar_main)){
                    unlink($avatar_main);
                }
            }
        }
        $languages = Language::get();
        foreach ($languages as $language) {
            ProductTranslations::where(['lang' => $language->code, 'product_id' => $model->id])->delete();
        }
        $model->delete();
        return redirect()->route('product.index')->with('status', translate('Successfully deleted'));
    }


    public function getProductCategory($product){
        if($product->subSubCategory){
            $category_product = $product->subSubCategory;
            $is_category = 3;
        }elseif($product->subCategory){
            $category_product = $product->subCategory;
            $is_category = 2;
        }elseif($product->category){
            $category_product = $product->category;
            $is_category = 1;
        }else{
            $category_product = 'no';
            $is_category = 0;
        }
        switch ($is_category){
            case 1:
                $current_category = $category_product;
                break;
            case 2:
                $current_category = $category_product->category?$category_product->category:'no';
                break;
            case 3:
                if($category_product->sub_category){
                    $current_category = $category_product->sub_category->category?$category_product->sub_category->category:'no';
                }else{
                    $current_category = 'no';
                }
                break;
            default:
                $current_category = 'no';
        }
        return $current_category;
    }

    public function getProductCategoryLink($product){
        if($product->subSubCategory){
            $category_product = $product->subSubCategory;
            $is_category = 3;
        }elseif($product->subCategory){
            $category_product = $product->subCategory;
            $is_category = 2;
        }elseif($product->category){
            $category_product = $product->category;
            $is_category = 1;
        }else{
            $category_product = 'no';
            $is_category = 0;
        }
        switch ($is_category){
            case 1:
                $category_product_id = $category_product->id-1;
                $category_translation_name = optional($category_product->getTranslatedModel)->name??$category_product->name;
                $current_category_link = [
                    'name'=>$category_translation_name,
                    'link'=>"/products/$category_product_id"
                ];
                $current_sub_category_link = [];
                $current_sub_sub_category_link = [];
                break;
            case 2:
                $current_category = $category_product->category?$category_product->category:'no';
                $sub_category_translation_name = optional($category_product->getTranslatedModel)->name??$category_product->name;
                $category_translation_name = $current_category != 'no'?(optional($current_category->getTranslatedModel)->name??$current_category->name):'';
                $category_product_id = $current_category->id-1;
                if($current_category != 'no'){
                    $current_category_link = [
                        "name"=>$sub_category_translation_name,
                        "link"=>"/products/$category_product_id"
                    ];
                }else{
                    $current_category_link = [];
                }
                $current_sub_category_link = [
                    "name"=>$category_translation_name,
                    "link"=>"/sub-category-products/$category_product->id"
                ];
                $current_sub_sub_category_link = [];
                break;
            case 3:
                if($category_product->sub_category){
                    $current_category = $category_product->sub_category->category??'no';
                }else{
                    $current_category = 'no';
                }
                $category_translation_name = $current_category != 'no'?(optional($current_category->getTranslatedModel)->name??$current_category->name):'';
                $category_product_id = $current_category->id-1;
                if($current_category != 'no'){
                    $current_category_link = [
                        "name"=>$category_translation_name,
                        "link"=>"/products/$category_product_id"
                    ];
                }else{
                    $current_category_link = [];
                }
                $sub_current_category = $category_product->sub_category??'no';
                $sub_category_translation_name = $sub_current_category != 'no'?(optional($sub_current_category->getTranslatedModel)->name??$sub_current_category->name):'';
                if($sub_current_category != 'no'){
                    $current_sub_category_link = [
                        "name"=>$sub_category_translation_name,
                        "link"=>"/sub-category-products/$sub_current_category->id"
                    ];
                }else{
                    $current_sub_category_link = [];
                }
                $sub_sub_category_translation_name = optional($category_product->getTranslatedModel)->name??$category_product->name;
                $current_sub_sub_category_link = [
                    "name"=>$sub_sub_category_translation_name,
                    "link"=>"/sub-category-products/$category_product->id"
                ];
                break;
            default:
                $current_category_link = [];
                $current_sub_category_link = [];
                $current_sub_sub_category_link = [];
        }
        return [$current_category_link, $current_sub_category_link, $current_sub_sub_category_link];
    }

    public function category()
    {
        $getCommonData = $this->getCommonData();
        $category = Category::where('step', 0)->get();
        return view('products.category', array_merge(['categories'=>$category, 'current_page'=>$this->current_page], $getCommonData));
    }

    public function product($id)
    {
        $getCommonData = $this->getCommonData();
        $category = Category::with([
            'subcategoriesId',
        ])->find($id);
        $category_ids = $category->subcategoriesId->map(function($subcategory){
            return $subcategory->id;
        });
        $subsubcategories = Category::WhereIn('parent_id', $category_ids)->pluck('id')->toArray();
        $category_ids = array_merge($category_ids, $subsubcategories);
        $category_ids[] = $category->id;
        $products = Products::whereIn('category_id', $category_ids)->get();
        return view('products.product', array_merge(['products'=>$products, 'current_page'=>$this->current_page], $getCommonData));
    }

    public function imageSave($product, $images, $text){
        if($text == 'update'){
            if($product->images && !is_array($product->images)){
                $product_images = json_decode($product->images, true);
                foreach($product_images as $key => $product_image){
                    $product_image = $product_image??'no';
                    $product_image_file = storage_path('app/public/products/'.$product_image);
                    if(!file_exists($product_image_file)){
                       unset($product_images[$key]);
                    }
                }
            }else{
                $product_images = [];
            }
        }else{
            $product_images = [];
        }
        if(isset($images)){
            $ProductImage = [];
            foreach($images as $image){
                $random = $this->setRandom();
                $product_image_name = $random.''.date('Y-m-dh-i-s').'.'.$image->extension();
                $image->storeAs('public/products/', $product_image_name);
                $ProductImage[] = $product_image_name;
            }
            $all_product_images = array_values(array_merge($product_images, $ProductImage));
        }
        $productImages = json_encode($all_product_images??$product_images);
        return $productImages;
    }

    /**
     * Api responses
     */

    public function getProductsByCategory()
    {
        $categories = Category::with([
            'subcategory',
            'subcategory.subsubcategory',
        ])->where('step', 0)->get();
        foreach ($categories as $category) {
            $subcategory_ids[$category->id][] = $category->id;
            $categories_id[] = [
                'id'=>$category->id,
                'name'=>$category->name,
                'subcategory'=>[
                    'id'=>[]
                ]
            ];
            foreach ($category->subcategory as $subcategory){
                $subcategory_ids[$category->id][] = $subcategory->id;
                $categories_id[] = [
                    'id'=>$category->id,
                    'name'=>$category->name,
                ];
                $subsubcategories_id = $subcategory->subsubcategory->map(function($subsubcategory){
                    return $subsubcategory->id;
                });
                $subcategory_ids[$category->id] = array_merge($subcategory_ids[$category->id], $subsubcategories_id);
            }
        }
        $products = Products::whereIn('category_id', array_unique(array_merge(...array_values($subcategory_ids))))->get();
        $goods = $categories->mapWithKeys(function($category) use($products, $subcategory_ids){
           return [
               $category->name => $products->whereIn('category_id', $subcategory_ids[$category->id])->values()
           ];
        });
        return response()->json($goods);
    }

    public function getProducts(Request $request)
    {
        $language = $request->header('language')??'en';
        $products = Products::with([
            'discount',
            'warehouses',
            'warehouses.color',
            'warehouses.color.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'warehouses.size',
            'getTranslatedDescriptionModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subSubCategory',
            'subCategory',
            'category',
            'subSubCategory.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subCategory.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'category.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subSubCategory.sub_category',
            'subSubCategory.sub_category.category',
            'subCategory.category',
        ])->get();
        $goods = $this->getGoods($products);
        $response = [
            'status'=>true,
            'data'=>$goods
        ];
        return response()->json($response, 200);
    }

    public function getAllProducts(Request $request)
    {
        $language = $request->header('language')??'en';
        $products = Products::with([
            'discount',
            'warehouses',
            'warehouses.color',
            'warehouses.color.warehouses',
            'warehouses.color.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'warehouses.size',
            'getTranslatedDescriptionModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subSubCategory',
            'subCategory',
            'category',
            'subSubCategory.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subCategory.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'category.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subSubCategory.sub_category',
            'subSubCategory.sub_category.category',
            'subCategory.category',
        ])->get();
        $goods = $this->getGoods($products);
        $response = [
            'status'=>true,
            'data'=>$goods
        ];
        return response()->json($response, 200);
    }

    public function getProduct(Request $request)
    {
        $product_id = $request->header('id');
        $language = $request->header('language')??'en';
        $is_exist_in_warehouse = false;
        $product = Products::with([
            'subSubCategory',
            'subCategory',
            'category',
            'subCategory.category',
            'subSubCategory.sub_category',
            'subSubCategory.sub_category.category',
            'subSubCategory.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subCategory.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'category.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subCategory.category.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subSubCategory.sub_category.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subSubCategory.sub_category.category.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'discount',
            'warehouses',
            'warehouses.size',
            'warehouses.color',
            'warehouses.color.warehouses' => function($query) use($product_id) {
                $query->where('product_id', $product_id);
            },
            'warehouses.color.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'getTranslatedDescriptionModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            }
        ])->find($product_id);
        if($product){
            $discount = $product->discount;
            $firstProducts = [];
            if($product->warehouses->isNotEmpty()){
                $is_exist_in_warehouse = true;
                $firstProducts = $this->productService->getFirstProduct($product->warehouses->first(), $discount);
            }
            $categorizedByColor = $product->warehouses
                ->filter(fn($categorizedProduct_) => !empty($categorizedProduct_->color))
                ->map(function($categorizedProduct_) use($discount, $product_id){
                $colorModel = $categorizedProduct_->color;
                $productsByColor = $this->productService->getProductsByColor($colorModel->warehouses, $discount, $product_id);
                return [
                    'color' => $colorModel,
                    'products' => $productsByColor
                ];
            });

            $good = [];
            $images_ = json_decode($product->images);
            $images = [];
            foreach($images_ as $image_) {
                $images[] = asset('storage/products/' . $image_);
            }
            $translate_product_description = optional($product->getTranslatedDescriptionModel)->name??$product->description;
            $good['id'] = $product->id;
            $translate_product_name = optional($product->getTranslatedModel)->name??$product->name;
            $good['name'] = $translate_product_name ?? null;
            $current_category = $this->getProductCategory($product);
            $translate_category_name = null;
            if($current_category != 'no'){
                $translate_category_name = optional($current_category->getTranslatedModel)->name??null;
            }
            $good['category'] = $translate_category_name;
            $good['description'] = $translate_product_description ?? null;
            if($discount){
                $good['sum'] = $discount->percent?$product->sum - $product->sum*(int)$discount->percent/100:$product->sum;
            }else{
                $good['sum'] = $product->sum ?? null;
            }
            $good['price'] = $product->sum;
            $good['discount'] = optional($product->discount)->percent??null;
            $good['company'] = $product->company ?? null;
            $good['characters'] = $categorizedByColor ?? [];
            $good['first_color_products'] = $firstProducts ?? [];
            $good['is_exist_in_warehouse'] = $is_exist_in_warehouse;
            $good['images'] = $images ?? [];
            $good['basket_button'] = false;
            $good['category_link'] = $this->getProductCategoryLink($product, $language)[0];
            $good['sub_category_link'] = $this->getProductCategoryLink($product, $language)[1];
            $good['sub_sub_category_link'] = $this->getProductCategoryLink($product, $language)[2];
            $good['created_at'] = $product->created_at ?? null;
            $good['updated_at'] = $product->updated_at ?? null;
            $response = [
                'status'=>true,
                'data'=>$good
            ];
            return response()->json($response, 200);
        }else{
            return $this->error('Product not found', 400);
        }

    }

    public function getCharacterizedProduct(Request $request)
    {
        $language = $request->header('language')??'en';
        $all_sum = 0;
        $order_coupon_price = 0;
        $good = [];
        $selected_products = $request->selected_products;
        if(is_array($selected_products)){
            foreach($selected_products as $selected_product){
                $product = CharacterizedProducts::with([
                    'product',
                    'product.discount',
                    'product',
                    'product.category',
                    'product.category.getTranslatedModel' => function($query) use($language){
                        $query->where('lang', $language);
                    },
                    'product.getTranslatedModel' => function($query) use($language){
                        $query->where('lang', $language);
                    },
                    'discount'
                ])->find($selected_product['id']);
                if($product){
                    $images = null;
                    $company_name = null;
                    $translate_category_name = null;
                    $product_ = $product->product;
                    if($product_){
                        $discount = $product_->discount;
                        if($product->sum){
                            if($discount){
                                $categorizedProductSum = $discount->percent?(int)$product->sum - (int)$product->sum*(int)$discount->percent/100:(int)$product->sum;
                                $categorizedAllProductSum = $discount->percent?(int)$product->sum*(int)$selected_product['count'] - (int)$product->sum*(int)$selected_product['count']*(int)$discount->percent/100:(int)$product->sum*(int)$selected_product['count'];
                            }else{
                                $categorizedProductSum = (int)$product->sum;
                                $categorizedAllProductSum = (int)$product->sum*(int)$selected_product['count'];
                            }
                        }else{
                            if($discount){
                                $categorizedProductSum = $discount->percent?(int)$product_->sum - (int)$product_->sum*(int)$discount->percent/100:(int)$product_->sum;
                                $categorizedAllProductSum = $discount->percent?(int)$product_->sum*(int)$selected_product['count'] - (int)$product_->sum*(int)$selected_product['count']*(int)$discount->percent/100:(int)$product_->sum;
                            }else{
                                $categorizedAllProductSum = (int)$product_->sum*(int)$selected_product['count'];
                                $categorizedProductSum = (int)$product_->sum;
                            }
                        }
                        $images_ = json_decode($product_->images);
                        if(count($images_)>0){
                            $images = asset('storage/products/'.$images_[0]);
                        }else{
                            $images = '';
                        }
                        $company_name = $product_->company??null;
                        $translate_category_name = optional(optional($product_->category)->getTranslatedModel)->name??'';
                    }
                    $all_sum = $all_sum + $categorizedAllProductSum??(int)$product->sum*(int)$selected_product['count'];

                    $translate_product_name = optional($product_->getTranslatedModel)->name??'';
                    $good[] = [
                        'id'=>$product->id,
                        'product_id'=>$product_->id,
                        'name'=>$translate_product_name,
                        'images'=>$images,
                        'company'=>$company_name,
                        'category'=>$translate_category_name,
                        'size'=>$product->size?$product->size->name:'',
                        'color'=>$product->color??[],
                        'count'=>(int)$product->count,
                        'discount' => $product->discount?(int)$product->discount->percent:null,
                        'sum'=>(int)$categorizedProductSum??(int)$product->sum,
                        'price'=>(int)$product->sum??(int)$product_->sum,
                    ];
                }
            }
            if($request->coupon){
                $coupon = Coupon::where('name', $request->coupon)->where('status', 1)
                    ->where('start_date', '<=', date('Y-m-d H:i:s'))
                    ->where('end_date', '>=', date('Y-m-d H:i:s'))->first();
                $user = Auth::user();
                $order_count = Order::where('user_id', $user->id)->where('status', '!=', Constants::BASKED)->count();
                if($coupon) {
                    if($all_sum > $coupon->min_price){
                        if($coupon->order_quantity) {
                            if($coupon->order_quantity > 0){
                                $order_coupon_price = (int)$this->setOrderCoupon($coupon, $all_sum);
                            }else{
                                $message = translate("Coupon left 0 quantity");
                                return $this->error($message, 400);
                            }
                        }elseif($coupon->order_number) {
                            if($order_count+1 == $coupon->order_number){
                                $order_coupon_price = (int)$this->setOrderCoupon($coupon, $all_sum);
                            }
//                        else{
//                            $message = translate("Coupon for your $coupon->order_number - order this is your $order_count - order");
//                            return $this->error($message, 400);
//                        }
                        }else{
                            $order_coupon_price = (int)$this->setOrderCoupon($coupon, $all_sum);
                        }
                    }
                }
            }

            $response = [
                'status'=>true,
                'coupon_price'=>$order_coupon_price,
                'data'=>$good
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status'=>false,
                'data'=>[]
            ];
            return response()->json($response, 200);
        }
    }

    public function setOrderCoupon($coupon, $price){
        if ($coupon->percent) {
            $order_coupon_price = ((int)$price/100)*((int)$coupon->percent);
        }elseif($coupon->price){
            $order_coupon_price = (int)$coupon->price;
        }
        return $order_coupon_price;
    }

    public function getFavouriteProducts(Request $request)
    {
        $language = $request->header('language')??'en';
        $good = [];
        $is_exist_in_warehouse = false;
        $selected_products_id = $request->selected_products_id;
        foreach($selected_products_id as $selected_product_id){
            $images = null;
            $company_name = null;
            $category_name = null;
            $product = Products::with([
                'discount',
                'category',
                'category.getTranslatedModel' => function($query) use($language){
                    $query->where('lang', $language);
                },
                'getTranslatedModel' => function($query) use($language){
                    $query->where('lang', $language);
                },
                'warehouses',
                'warehouses.color',
                'warehouses.color.warehouses' => function($query) use($selected_product_id) {
                    $query->where('product_id', $selected_product_id);
                },
                'warehouses.color.getTranslatedModel' => function($query) use($language){
                    $query->where('lang', $language);
                },
                'warehouses.size'
            ])->find($selected_product_id);
            if($product){
                $discount = $product->discount;
                if (!$product->warehouses->isEmpty()) {
                    $discount = $product->discount;
                    $firstProducts = [];
                    if($product->warehouses->isNotEmpty()){
                        $is_exist_in_warehouse = true;
                        $firstProducts = $this->productService->getFirstProduct($product->warehouses->first(), $discount);
                    }
                    $categorizedByColor = $product->warehouses
                        ->filter(fn($categorizedProduct_) => !empty($categorizedProduct_->color))
                        ->map(function($categorizedProduct_) use($discount, $selected_product_id){
                            $colorModel = $categorizedProduct_->color;
                            $productsByColor = $this->productService->getProductsByColor($colorModel->warehouses, $discount, $selected_product_id);
                            return [
                                'color' => $colorModel,
                                'products' => $productsByColor
                            ];
                        });
                }

                if ($product->sum) {
                    if ($discount) {
                        $ProductSum = $discount->percent ? $product->sum - $product->sum * (int)$discount->percent / 100 : $product->sum;
                    } else {
                        $ProductSum = $product->sum;
                    }
                }
                $images_ = json_decode($product->images);
                $images = [];
                foreach ($images_ as $image_) {
                    $images[] = asset('storage/products/' . $image_);
                }
                $company_name = $product->company ?? null;
                $category_name = optional(optional($product->category)->getTranslatedModel)->name??(optional($product->category)->name??null);
                $translate_product_name = optional($product->getTranslatedModel)->name??($product->name??'');
                $good[] = [
                    'id' => $product->id,
                    'name' => $translate_product_name,
                    'images' => $images,
                    'company' => $company_name,
                    'category' => $category_name,
                    'size_id' => $product->size_id ?? null,
                    'color' => $product->color ?? null,
                    'count' => $product->count,
                    'characters' => $categorizedByColor ?? [],
                    'first_color_products' => $firstProducts ?? [],
                    'is_exist_in_warehouse' => $is_exist_in_warehouse,
                    'discount' => $discount ? $discount->percent : null,
                    'sum' => $ProductSum,
                    'basket_button' => false,
                    'price' => $product->sum,
                ];
            }
        }
        $response = [
            'status'=>true,
            'data'=>$good
        ];
        return response()->json($response, 200);
    }

    public function BestSeller(Request $request)
    {
        $language = $request->header('language')??'en';
        $products = Products::with([
            'discount',
            'warehouses',
            'warehouses.color',
            'warehouses.color.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'warehouses.size',
            'getTranslatedDescriptionModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subSubCategory',
            'subCategory',
            'category',
            'subSubCategory.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subCategory.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'category.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subSubCategory.sub_category',
            'subSubCategory.sub_category.category',
            'subCategory.category',
        ])->orderBy('id', 'DESC')->get();
        $goods = $this->getGoods($products);
        $response = [
            'status'=>true,
            'data'=>$goods
        ];
        return response()->json($response, 200);
    }

    public function getSizes($id){
        $sizes = Sizes::select('id', 'name', 'category_id')->where('category_id', $id)->get();
        $respone = [
            'status'=>true,
            'data'=>$sizes
        ];
        return response()->json($respone, 200);
    }

    public function getCategoriesByProduct($id){
        $product = Products::find($id);
        $current_category = $this->getProductCategory($product);
        $respone = [
            'status'=>true,
            'data'=>$current_category->sizes??[],
            'category'=>$current_category->name??"",
            'sum'=>$product->sum
        ];
        return response()->json($respone, 200);
    }

    public function getProductsByCategories(Request $request){
        $language = $request->header('language')??'en';
        $categories = Category::where('step', 0)->get();
        $data = $this->getProductsByAllCategories($categories, $language);
        return response()->json([
            'status'=>true,
            'message'=>'Success',
            'data'=>$data
        ]);
    }

    public function getProductsBySubCategories(Request $request, $id){
        $language = $request->header('language')??'en';
        $category = Category::find($id);
        $data = $this->getProductsByAllSubCategories($category, $language);
        return response()->json([
            'status'=>true,
            'message'=>'Success',
            'data'=>$data
        ]);
    }

    public function getProductsByAllCategories($categories, $language){
        $data = $categories->map(function($category) use($language) {
            $category_ids = $this->getCategoriesId($category);
            $products = Products::with([
                'discount',
                'warehouses',
                'warehouses.color',
                'warehouses.color.getTranslatedModel' => function($query) use($language){
                    $query->where('lang', $language);
                },
                'warehouses.size',
                'getTranslatedDescriptionModel' => function($query) use($language){
                    $query->where('lang', $language);
                },
                'getTranslatedModel' => function($query) use($language){
                    $query->where('lang', $language);
                },
                'subSubCategory',
                'subCategory',
                'category',
                'subSubCategory.getTranslatedModel' => function($query) use($language){
                    $query->where('lang', $language);
                },
                'subCategory.getTranslatedModel' => function($query) use($language){
                    $query->where('lang', $language);
                },
                'category.getTranslatedModel' => function($query) use($language){
                    $query->where('lang', $language);
                },
                'subSubCategory.sub_category',
                'subSubCategory.sub_category.category',
                'subCategory.category',
            ])->whereIn('category_id', $category_ids)->get();
            $goods = $this->getGoods($products);
            return [
                'category'=>[
                    'id'=>$category->id,
                    'name'=>$category->name,
                ],
                'products'=>$goods
            ];
        });

        return $data;
    }

    public function getProductsByAllSubCategories($category, $language){
        $category_ids = $this->getCategoriesId($category);
        $products = Products::with([
            'discount',
            'warehouses',
            'warehouses.color',
            'warehouses.color.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'warehouses.size',
            'getTranslatedDescriptionModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subSubCategory',
            'subCategory',
            'category',
            'subSubCategory.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subCategory.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'category.getTranslatedModel' => function($query) use($language){
                $query->where('lang', $language);
            },
            'subSubCategory.sub_category',
            'subSubCategory.sub_category.category',
            'subCategory.category',
        ])->whereIn('category_id', $category_ids)->get();
        $goods = $this->getGoods($products);
        $data = [
            'category'=>[
                'id'=>$category->id,
                'name'=>$category->name,
            ],
            'products'=>$goods
        ];
        return $data;
    }

    public function getGoods($products){
        $goods = [];
        $is_exist_in_warehouse = false;
        foreach ($products as $key => $product) {
            $product_id = $product->id;
            $discount = $product->discount;
            $firstProducts = [];
            if($product->warehouses->isNotEmpty()){
                if($product->warehouses->first()) {
                    $is_exist_in_warehouse = true;
                    $firstProducts = $this->productService->getFirstProduct($product->warehouses->first(), $discount);
                }
            }
            $categorizedByColor = $product->warehouses
                ->filter(fn($categorizedProduct_) => !empty($categorizedProduct_->color))
                ->map(function($categorizedProduct_) use($discount, $product_id){
                    $colorModel = $categorizedProduct_->color;
                    $productsByColor = $this->productService->getProductsByColor($colorModel->warehouses, $discount, $product_id);
                    return [
                        'color' => $colorModel,
                        'products' => $productsByColor
                    ];
                });

            $images_ = json_decode($product->images);
            $images = [];
            foreach ($images_ as $image_) {
                $images[] = asset('storage/products/' . $image_);
            }
            $translate_product_description = optional($product->getTranslatedDescriptionModel)->name??$product->name;
            $goods[$key]['id'] = $product->id;
            $translate_product_name = optional($product->getTranslatedModel)->name??$product->name;
            $goods[$key]['name'] = $translate_product_name;
            $current_category = $this->getProductCategory($product);
            $translate_category_name = optional($current_category->getTranslatedModel)->name??'';
            $goods[$key]['category'] = $translate_category_name;
            $goods[$key]['description'] = $translate_product_description ?? null;
            if($discount){
                $goods[$key]['sum'] = $discount->percent?$product->sum - $product->sum*(int)$discount->percent/100:$product->sum;
            }else{
                $goods[$key]['sum'] = $product->sum ?? null;
            }
            $goods[$key]['price'] = $product->sum;
            $goods[$key]['discount'] = $product->discount?$product->discount->percent:null;
            $goods[$key]['company'] = $product->company ?? null;
            $goods[$key]['characters'] = $categorizedByColor??[];
            $goods[$key]['first_color_products'] = $firstProducts ?? [];
            $goods[$key]['is_exist_in_warehouse'] = $is_exist_in_warehouse;
            $goods[$key]['images'] = $images ?? [];
            $goods[$key]['basket_button'] = false;
            $goods[$key]['created_at'] = $product->created_at ?? null;
            $goods[$key]['updated_at'] = $product->updated_at ?? null;
        }
        return $goods;
    }

    public function getCategoriesId($category){
        $category_ids = [];
        switch($category->step){
            case 0:
                $subcategories = $category->subcategory;
                $category__ids = $subcategories->map(function($subcategory){
                   return $subcategory->id;
                })->toArray();
                $sub_category__ids = Category::WhereIn('parent_id', $category__ids)->get()->map(function($subsubcategory){
                    return $subsubcategory->id;
                })->toArray();
                $category_ids = array_merge($category__ids, $sub_category__ids, [$category->id]);
                break;
            case 1:
                $sub_category__ids = Category::Where('parent_id', $category->id)->get()->map(function($subsubcategory){
                   return $subsubcategory->id;
                })->toArray();
                $category_ids = array_merge($sub_category__ids, [$category->id]);
                break;
            case 2:
                $category_ids[] = $category->id;
                break;
            default:
        }
        return $category_ids;
    }

    public function deleteProductImage(Request $request){
        $product = Products::find($request->id);
        if($product->images && !is_array($product->images)){
            $product_images_base = json_decode($product->images, true);
        }else{
            $product_images_base = [];
        }
        if(is_array($product_images_base)){
            if(isset($request->product_name)){
                $selected_product_key = array_search($request->product_name, $product_images_base);
                $product_main = storage_path('app/public/products/'.$request->product_name);
                if(file_exists($product_main)){
                    unlink($product_main);
                }
                unset($product_images_base[$selected_product_key]);
                foreach($product_images_base as $key => $product_image){
                    $product_image = $product_image??'no';
                    $product_image_file = storage_path('app/public/products/'.$product_image);
                    if(!file_exists($product_image_file)){
                        unset($product_images_base[$key]);
                    }
                }
            }
            $product->images = json_encode(array_values($product_images_base));
            $product->save();
        }
        return response()->json([
            'status'=>true,
            'message'=>'Success'
        ], 200);
    }


    public function paymentGetStatus(){
        $payment = PaymentStatus::first();
        $status = '';
        if($payment){
            if($payment->status == 0){
                $status = 'Not active';
            }elseif($payment->status == 1){
                $status = 'Active';
            }
        }
        return $this->success('Success', 200, [$status]);
    }

    public function payment(){
        $getCommonData = $this->getCommonData();
        $payment = PaymentStatus::first();
        return view('payment.index', array_merge(['payment'=>$payment, 'current_page'=>$this->current_page], $getCommonData));
    }

    public function paymentStatus(Request $request){
        $payment = PaymentStatus::find($request->id);
        if($payment){
            if($request->checked == 'true'){
                $payment->status = Constants::ACTIVE;
                $message = translate('Payment activated');
            }elseif($request->checked == 'false'){
                $payment->status = Constants::NOT_ACTIVE;
                $message = translate('Payment disactivated');
            }
            $payment->save();
        }
        return $this->success($message, 200);
    }
}
