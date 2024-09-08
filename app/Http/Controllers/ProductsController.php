<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\CharacterizedProducts;
use App\Models\Color;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('step', 0)->get();
        $all_products = [];
        foreach($categories as $category){
            $categories_id = [];
            $sub_categories_id = Category::where('parent_id', $category->id)->pluck('id')->all();
            foreach($sub_categories_id as $sub_category_id){
                $sub_sub_categories_id = Category::where('parent_id', $sub_category_id)->pluck('id')->all();
                $categories_id = array_merge($categories_id, $sub_sub_categories_id);
            }
            $categories_id = array_merge($categories_id, $sub_categories_id);
            array_push($categories_id, $category->id);
            $products = Products::orderBy('created_at', 'desc')->whereIn('category_id', $categories_id)->get();

            $all_products[$category->id] = $products;
        }
        return view('products.index', ['all_products'=> $all_products, 'categories'=> $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        return view('products.create', ['categories'=> $categories]);
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
//        dd()
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
        foreach (Language::all() as $language) {
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
        $model = Products::find($id);
        $category_array = [];
        if($model->category){
            $category_ = $model->category->name;
            $category_array = [$category_];
        }elseif($model->subCategory){
            $category_ = $model->subCategory->category?$model->subCategory->category->name:'';
            $sub_category_ = $model->subCategory->name;
            if($category_ != ''){
                $category_array = [$category_, $sub_category_];
            }else{
                $category_array = [$sub_category_];
            }
        }elseif($model->subSubCategory){
            $sub_sub_category_ = $model->subSubCategory->name;
            if($model->subSubCategory->sub_category){
                $category_ = $model->subSubCategory->sub_category->category?$model->subSubCategory->sub_category->category->name:'';
                $sub_category_ = $model->subSubCategory->sub_category->name;
                if($category_ != ''){
                    $category_array = [$category_, $sub_category_, $sub_sub_category_];
                }else{
                    $category_array = [$sub_category_, $sub_sub_category_];
                }
            }else{
                $category_array = [$sub_sub_category_];
            }
        }

        return view('products.show', [
            'model'=>$model,
            'category_array'=> $category_array
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Products::find($id);
        if($product->subCategory){
            $category_product = $product->subCategory;
            $is_category = 2;
            $current_category = $category_product->category?$category_product->category:'no';
            $current_sub_category_id = $category_product->id?$category_product->id:'no';
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
                $current_sub_category_id = $category_product->sub_category ? $category_product->sub_category->id : 'no';
            }else{
                $current_category = 'no';
                $current_sub_category_id = 'no';
            }
            $current_sub_sub_category_id = $category_product ? $category_product->id : 'no';
        }else{
            $category_product = 'no';
            $is_category = 0;
            $current_category = 'no';
            $current_sub_category_id = 'no';
            $current_sub_sub_category_id = 'no';
        }
        $categories = Category::where('parent_id', 0)->orderBy('id', 'asc')->get();
        return view('products.edit', [
            'product'=> $product, 'categories'=> $categories,
            'category_product'=> $category_product, 'is_category'=>$is_category,
            'current_category' => $current_category,
            'current_sub_category_id' => $current_sub_category_id,
            'current_sub_sub_category_id' => $current_sub_sub_category_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Products::find($id);
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
            foreach (Language::all() as $language) {
                $product_translations = ProductTranslations::firstOrNew(['lang' => $language->code, 'product_id' => $model->id]);
                $product_translations->lang = $language->code;
                $product_translations->name = $request->name;
                $product_translations->product_id = $model->id;
                $product_translations->save();
            }

        }
//        if($model->description != $request->description_uz){
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
//        }
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
        $model = Products::find($id);
        if(!$model->categorizedProducts->isEmpty()){
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
        foreach (Language::all() as $language) {
            $product_translations = ProductTranslations::where(['lang' => $language->code, 'product_id' => $model->id])->get();
            foreach ($product_translations as $product_translation){
                $product_translation->delete();
            }
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

    public function getProductCategoryLink($product, $language){
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
                    $category_translation_name = table_translate($category_product, 'category', $language);
                    $current_category_link = [
                        'name'=>$category_translation_name,
                        'link'=>"/products/$category_product_id"
                    ];
                    $current_sub_category_link = [];
                    $current_sub_sub_category_link = [];
                    break;
                case 2:
                    $current_category = $category_product->category?$category_product->category:'no';
                    $category_product_id = $current_category->id-1;
                    if($current_category != 'no'){
                        $category_translation_name_ = table_translate($current_category, 'category', $language);
                        $current_category_link = [
                            "name"=>$category_translation_name_,
                            "link"=>"/products/$category_product_id"
                        ];
                    }else{
                        $current_category_link = [];
                    }
                    $category_translation_name = table_translate($category_product, 'category', $language);
                    $current_sub_category_link = [
                        "name"=>$category_translation_name,
                        "link"=>"/sub-category-products/$category_product->id"
                    ];
                    $current_sub_sub_category_link = [];
                    break;
                case 3:
                    if($category_product->sub_category){
                        $current_category = $category_product->sub_category->category?$category_product->sub_category->category:'no';
                    }else{
                        $current_category = 'no';
                    }
                    $category_product_id = $current_category->id-1;
                    if($current_category != 'no'){
                        $category_translation_name = table_translate($current_category, 'category', $language);
                        $current_category_link = [
                            "name"=>$category_translation_name,
                            "link"=>"/products/$category_product_id"
                        ];
                    }else{
                        $current_category_link = [];
                    }
                    $sub_current_category = $category_product->sub_category?$category_product->sub_category:'no';
                    if($sub_current_category != 'no'){
                        $category_translation_name_ = table_translate($sub_current_category, 'category', $language);
                        $current_sub_category_link = [
                            "name"=>$category_translation_name_,
                            "link"=>"/sub-category-products/$sub_current_category->id"
                        ];
                    }else{
                        $current_sub_category_link = [];
                    }
                    $category_translation_name__ = table_translate($category_product, 'category', $language);
                    $current_sub_sub_category_link = [
                        "name"=>$category_translation_name__,
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
        $category = Category::where('step', 0)->get();
        return view('products.category', ['categories'=>$category]);
    }

    public function product($id)
    {
        $category = Category::find($id);
        $subcategories = $category->subcategory;
        foreach ($subcategories as $subcategory){
            $category_ids[] = $subcategory->id;
        }
        $subsubcategories = Category::WhereIn('parent_id', $category_ids)->get();
        foreach ($subsubcategories as $subsubcategory){
            $category_ids[] = $subsubcategory->id;
        }
        $category_ids[] = $category->id;
        $products = Products::whereIn('category_id', $category_ids)->get();
        return view('products.product', ['products'=>$products]);
    }

    public function imageSave($product, $images, $text){
        if($text == 'update'){
            if($product->images && !is_array($product->images)){
                $product_images = json_decode($product->images);
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
        $categories = Category::where('step', 0)->get();
        foreach ($categories as $category) {
            $subcategory_ids[$category->id][] = $category->id;
            $categories_id[] = [
                'id'=>$category->id,
                'name'=>$category->name,
                'subcategory'=>[
                    'id'=>[]
                ]
            ];
            if(!$category->subcategory->isEmpty()){
                foreach ($category->subcategory as $subcategory){
                    $subcategory_ids[$category->id][] = $subcategory->id;
                    $categories_id[] = [
                        'id'=>$category->id,
                        'name'=>$category->name,
                    ];
                    foreach ($subcategory->subsubcategory as $subsubcategory){
                        $subcategory_ids[$category->id][] = $subsubcategory->id;
                        $subsubcategories_id[] = $subsubcategory->id;
                    }
                }
            }
        }
        $goods = [];
        foreach ($categories as $category) {
            $goods[$category->name] = Products::whereIn('category_id', $subcategory_ids[$category->id])->get();
        }
        return response()->json($goods);
    }

    public function getProducts(Request $request)
    {
        $language = $request->header('language');
        $products = Products::all();
        $goods = $this->getGoods($products, $language);

//        $goods = array_merge($goods,$goods,$goods,$goods,$goods,$goods,$goods,$goods,$goods,$goods);

        $response = [
            'status'=>true,
            'data'=>$goods
        ];
        return response()->json($response, 200);
    }

    public function getAllProducts(Request $request)
    {
        $language = $request->header('language');
        $products = Products::all();
        $goods = $this->getGoods($products, $language);
        $response = [
            'status'=>true,
            'data'=>$goods
        ];
        return response()->json($response, 200);
    }

    public function getProduct(Request $request)
    {
        $language = $request->header('language');
        $is_exist_in_warehouse = false;
        $product = Products::find($request->header('id'));
        if($product){
            $discount = $product->discount;
            if (!$product->categorizedProducts->isEmpty()) {
                $colors_array = [];
                $firstProducts = [];
                $categorizedByColor = [];
                foreach ($product->categorizedProducts as $categorizedProduct_) {
                    if((int)$categorizedProduct_->count>0){
                        $is_exist_in_warehouse = true;
                    }
                    if($discount){
                        $categorizedProductSum_ = $discount->percent?$categorizedProduct_->sum - $categorizedProduct_->sum*(int)$discount->percent/100:$categorizedProduct_->sum;
                    }else{
                        $categorizedProductSum_ = $categorizedProduct_->sum;
                    }
                    if($categorizedProduct_->color) {
                        $color_id = $categorizedProduct_->color->id;
                        $colors_array[] = $categorizedProduct_->color->id;
                    }else{
                        $color_id = 'no';
                        $colors_array[] = 'no';
                    }
                    if($colors_array[0] == $color_id){
                        if($categorizedProduct_->color){
                            $translate_color_name = table_translate($categorizedProduct_->color, 'color', $language);
                            $color = [
                                'id' => $categorizedProduct_->color->id,
                                'name' => $translate_color_name??'',
                                'code' => $categorizedProduct_->color->code,
                            ];
                        }else{
                            $color = [];
                        }
                        $firstProducts[] = [
                            'id' => $categorizedProduct_->id,
                            'size' => $categorizedProduct_->size ? $categorizedProduct_->size->name : '',
                            'color' => $color,
                            'sum' => $categorizedProductSum_,
                            'discount' => $product->discount ? $product->discount->percent : null,
                            'price' => $categorizedProduct_->sum,
                            'count' => $categorizedProduct_->count
                        ];
                    }
                }
                foreach (array_unique($colors_array) as $color__) {
                    $productsByColor = [];
                    $colorModel = [];
                    $color_id = '';
                    foreach ($product->categorizedProducts as $categorizedProduct) {
                        if((int)$categorizedProduct->count>0){
                            $is_exist_in_warehouse = true;
                        }
                        if ($categorizedProduct->color) {
                            $color_id = $categorizedProduct->color->id;
                        } else {
                            $color_id = 'no';
                        }
                        if ($color__ == $color_id) {
                            if ($categorizedProduct->color) {
                                $colorModel = $categorizedProduct->color;

                                $translate_color_name_ = table_translate($categorizedProduct->color, 'color', $language);
                                $color_ = [
                                    'id' => $categorizedProduct->color->id,
                                    'name' => $translate_color_name_??'',
                                    'code' => $categorizedProduct->color->code,
                                ];
                            } else {
                                $colorModel = [];
                                $color_ = [];
                            }
                            if ($discount) {
                                $categorizedProductSum = $discount->percent ? $categorizedProduct->sum - $categorizedProduct->sum * (int)$discount->percent / 100 : $categorizedProduct->sum;
                            } else {
                                $categorizedProductSum = $categorizedProduct->sum;
                            }
                            $productsByColor[] = [
                                'id' => $categorizedProduct->id,
                                'size' => $categorizedProduct->size ? $categorizedProduct->size->name : '',
                                'sum' => $categorizedProductSum,
                                'color' => $color_,
                                'discount' => $product->discount ? $product->discount->percent : null,
                                'price' => $categorizedProduct->sum,
                                'count' => $categorizedProduct->count
                            ];
                        }
                    }
                    if($color_id != ''){
                        $categorizedByColor[] = [
                            'color' => $colorModel,
                            'products' => $productsByColor
                        ];
                    }
                }
            }
            $good = [];
            $images_ = json_decode($product->images);
            $images = [];
            foreach($images_ as $image_) {
                $images[] = asset('storage/products/' . $image_);
            }
            $translate_product_description = table_translate($product, 'product_description', $language);
            $good['id'] = $product->id;
            $translate_product_name = table_translate($product, 'product', $language);
            $good['name'] = $translate_product_name ?? null;
            $current_category = $this->getProductCategory($product);
            $translate_category_name = table_translate($current_category, 'category', $language);
            $good['category'] = $translate_category_name ?? null;
            $good['description'] = $translate_product_description ?? null;
            if($discount){
                $good['sum'] = $discount->percent?$product->sum - $product->sum*(int)$discount->percent/100:$product->sum;
            }else{
                $good['sum'] = $product->sum ?? null;
            }
            $good['price'] = $product->sum;
            $good['discount'] = $product->discount?$product->discount->percent:null;
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
        $language = $request->header('language');
        $all_sum = 0;
        $order_coupon_price = 0;
        $good = [];
        $selected_products = $request->selected_products;
        if(is_array($selected_products)){
            foreach($selected_products as $selected_product){
                $product = CharacterizedProducts::find($selected_product['id']);
                if($product){
                    $images = null;
                    $company_name = null;
                    $translate_category_name = null;
                    $product_ = Products::find($product->product_id);
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
                        if($product_->category){
                            $translate_category_name = table_translate($product_->category, 'category', $language);
                        }
                    }
                    $all_sum = $all_sum + $categorizedAllProductSum??(int)$product->sum*(int)$selected_product['count'];

                    $translate_product_name = table_translate($product_, 'product', $language);
                    $good[] = [
                        'id'=>$product->id,
                        'product_id'=>$product_->id,
                        'name'=>$translate_product_name,
                        'images'=>$images,
                        'company'=>$company_name,
                        'category'=>$translate_category_name,
                        'size'=>$product->size?$product->size->name:'',
                        'color'=>$product->color?$product->color:[],
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
        $language = $request->header('language');
        $good = [];
        $is_exist_in_warehouse = false;
        $selected_products_id = $request->selected_products_id;
        foreach($selected_products_id as $selected_product_id){
            $images = null;
            $company_name = null;
            $category_name = null;
            $product = Products::find($selected_product_id);
            if($product){

                $discount = $product->discount;
                if (!$product->categorizedProducts->isEmpty()) {
                    $colors_array = [];
                    $firstProducts = [];
                    $categorizedByColor = [];
                    foreach ($product->categorizedProducts as $categorizedProduct_) {
                        if((int)$categorizedProduct_->count>0){
                            $is_exist_in_warehouse = true;
                        }
                        if($discount){
                            $categorizedProductSum_ = $discount->percent?$categorizedProduct_->sum - $categorizedProduct_->sum*(int)$discount->percent/100:$categorizedProduct_->sum;
                        }else{
                            $categorizedProductSum_ = $categorizedProduct_->sum;
                        }
                        if($categorizedProduct_->color) {
                            $color_id = $categorizedProduct_->color->id;
                            $colors_array[] = $categorizedProduct_->color->id;
                        }else{
                            $color_id = 'no';
                            $colors_array[] = 'no';
                        }
                        if($colors_array[0] == $color_id){
                            if($categorizedProduct_->color){
                                $translate_color_name = table_translate($categorizedProduct_->color, 'color', $language);
                                $color = [
                                    'id' => $categorizedProduct_->color->id,
                                    'name' => $translate_color_name,
                                    'code' => $categorizedProduct_->color->code,
                                ];
                            }else{
                                $color = [];
                            }
                            $firstProducts[] = [
                                'id' => $categorizedProduct_->id,
                                'size' => $categorizedProduct_->size ? $categorizedProduct_->size->name : '',
                                'color' => $color,
                                'sum' => $categorizedProductSum_,
                                'discount' => $product->discount ? $product->discount->percent : null,
                                'price' => $categorizedProduct_->sum,
                                'count' => $categorizedProduct_->count
                            ];
                        }
                    }
                    foreach (array_unique($colors_array) as $color__) {
                        $productsByColor = [];
                        $colorModel = [];
                        $color_id = '';
                        foreach ($product->categorizedProducts as $categorizedProduct) {
                            if((int)$categorizedProduct->count>0){
                                $is_exist_in_warehouse = true;
                            }
                            if ($categorizedProduct->color) {
                                $color_id = $categorizedProduct->color->id;
                            } else {
                                $color_id = 'no';
                            }
                            if ($color__ == $color_id) {
                                if ($categorizedProduct->color) {
                                    $colorModel = $categorizedProduct->color;
                                    $translate_color_name_ = table_translate($categorizedProduct->color, 'color', $language);
                                    $color_ = [
                                        'id' => $categorizedProduct->color->id,
                                        'name' => $translate_color_name_,
                                        'code' => $categorizedProduct->color->code,
                                    ];
                                } else {
                                    $colorModel = [];
                                    $color_ = [];
                                }
                                if ($discount) {
                                    $categorizedProductSum = $discount->percent ? $categorizedProduct->sum - $categorizedProduct->sum * (int)$discount->percent / 100 : $categorizedProduct->sum;
                                } else {
                                    $categorizedProductSum = $categorizedProduct->sum;
                                }
                                $productsByColor[] = [
                                    'id' => $categorizedProduct->id,
                                    'size' => $categorizedProduct->size ? $categorizedProduct->size->name : '',
                                    'sum' => $categorizedProductSum,
                                    'color' => $color_,
                                    'discount' => $product->discount ? $product->discount->percent : null,
                                    'price' => $categorizedProduct->sum,
                                    'count' => $categorizedProduct->count
                                ];
                            }
                        }
                        if($color_id != ''){
                            $categorizedByColor[] = [
                                'color' => $colorModel,
                                'products' => $productsByColor
                            ];
                        }
                    }
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
                $category_name = $product->category ? $product->category->name : null;
                $translate_product_name = table_translate($product, 'product', $language);
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
        $language = $request->header('language');
        $products = Products::orderBy('id', 'DESC')->get();
        $goods = $this->getGoods($products, $language);
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
        $language = $request->header('language');
        $categories = Category::where('step', 0)->get();
        $data = $this->getProductsByAllCategories($categories, $language);
        return response()->json([
            'status'=>true,
            'message'=>'Success',
            'data'=>$data
        ]);
    }

    public function getProductsBySubCategories($id){
        $categories = Category::where('id', $id)->get();
        $data = $this->getProductsByAllCategories($categories);
        return response()->json([
            'status'=>true,
            'message'=>'Success',
            'data'=>$data
        ]);
    }

    public function getProductsByAllCategories($categories, $language){
        $data = [];
        foreach ($categories as $category){
            $category_ids = $this->getCategoriesId($category);
            $products = Products::whereIn('category_id', $category_ids)->get();
            $goods = $this->getGoods($products, $language);
            $data[] = [
                'category'=>[
                    'id'=>$category->id,
                    'name'=>$category->name,
                ],
                'products'=>$goods
            ];
        }
        return $data;
    }

    public function getGoods($products, $language){
        $goods = [];
        $is_exist_in_warehouse = false;
        foreach ($products as $key => $product) {
            $discount = $product->discount;
            $colors_array = [];
            $firstProducts = [];
            $categorizedByColor = [];
            if (!$product->categorizedProducts->isEmpty()) {
                foreach ($product->categorizedProducts as $categorizedProduct_) {
                    if((int)$categorizedProduct_->count>0){
                        $is_exist_in_warehouse = true;
                    }
                    if($discount){
                        $categorizedProductSum_ = $discount->percent?$categorizedProduct_->sum - $categorizedProduct_->sum*(int)$discount->percent/100:$categorizedProduct_->sum;
                    }else{
                        $categorizedProductSum_ = $categorizedProduct_->sum;
                    }
                    if($categorizedProduct_->color) {
                        $color_id = $categorizedProduct_->color->id;
                        $colors_array[] = $categorizedProduct_->color->id;
                    }else{
                        $color_id = 'no';
                        $colors_array[] = 'no';
                    }
                    if($colors_array[0] == $color_id){
                        if($categorizedProduct_->color){
                            $translate_color_name = table_translate($categorizedProduct_->color, 'color', $language);
                            $color = [
                                'id' => $categorizedProduct_->color->id,
                                'name' => $translate_color_name,
                                'code' => $categorizedProduct_->color->code,
                            ];
                        }else{
                            $color = [];
                        }
                        $firstProducts[] = [
                            'id' => $categorizedProduct_->id,
                            'size' => $categorizedProduct_->size ? $categorizedProduct_->size->name : '',
                            'color' => $color,
                            'sum' => $categorizedProductSum_,
                            'discount' => $product->discount ? $product->discount->percent : null,
                            'price' => $categorizedProduct_->sum,
                            'count' => $categorizedProduct_->count
                        ];
                    }
                }
                foreach (array_unique($colors_array) as $color__) {
                    $productsByColor = [];
                    $colorModel = [];
                    $color_id = '';
                    foreach ($product->categorizedProducts as $categorizedProduct) {
                        if((int)$categorizedProduct->count>0){
                            $is_exist_in_warehouse = true;
                        }
                        if ($categorizedProduct->color) {
                            $color_id = $categorizedProduct->color->id;
                        } else {
                            $color_id = 'no';
                        }
                        if ($color__ == $color_id) {
                            if ($categorizedProduct->color) {
                                $colorModel = $categorizedProduct->color;

                                $translate_color_name_ = table_translate($colorModel, 'color', $language);
                                $color_ = [
                                    'id' => $categorizedProduct->color->id,
                                    'name' => $translate_color_name_,
                                    'code' => $categorizedProduct->color->code,
                                ];
                            } else {
                                $colorModel = [];
                                $color_ = [];
                            }
                            if ($discount) {
                                $categorizedProductSum = $discount->percent ? $categorizedProduct->sum - $categorizedProduct->sum * (int)$discount->percent / 100 : $categorizedProduct->sum;
                            } else {
                                $categorizedProductSum = $categorizedProduct->sum;
                            }
                            $productsByColor[] = [
                                'id' => $categorizedProduct->id,
                                'size' => $categorizedProduct->size ? $categorizedProduct->size->name : '',
                                'sum' => $categorizedProductSum,
                                'color' => $color_,
                                'discount' => $product->discount ? $product->discount->percent : null,
                                'price' => $categorizedProduct->sum,
                                'count' => $categorizedProduct->count
                            ];
                        }
                    }
                    if($color_id != ''){
                        $categorizedByColor[] = [
                            'color' => $colorModel,
                            'products' => $productsByColor
                        ];
                    }
                }
            }
            $images_ = json_decode($product->images);
            $images = [];
            foreach ($images_ as $image_) {
                $images[] = asset('storage/products/' . $image_);
            }
            $translate_product_description = table_translate($product, 'product_description', $language);
            $goods[$key]['id'] = $product->id;

            $translate_product_name = table_translate($product, 'product', $language);
            $goods[$key]['name'] = $translate_product_name;
            $current_category = $this->getProductCategory($product);
            $translate_category_name = table_translate($current_category, 'category', $language);
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
                foreach ($subcategories as $subcategory){
                    $category_ids[] = $subcategory->id;
                }
                $subsubcategories = Category::WhereIn('parent_id', $category_ids)->get();
                foreach ($subsubcategories as $subsubcategory){
                    $category_ids[] = $subsubcategory->id;
                }
                $category_ids[] = $category->id;
                break;
            case 1:
                $category_ids[] = $category->id;
                $subsubcategories = Category::Where('parent_id', $category->id)->get();
                foreach ($subsubcategories as $subsubcategory){
                    $category_ids[] = $subsubcategory->id;
                }
                $category_ids[] = $category->id;
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
            $product_images_base = json_decode($product->images);
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
        $payment = PaymentStatus::first();
        return view('payment.index', ['payment'=>$payment]);
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
