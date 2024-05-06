<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\CharacterizedProducts;
use App\Models\Color;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Products;
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
        $model->company = $request->company;
        $model->status = $request->status;
        $model->sum = $request->sum;
        $model->description = $request->description;
        $images = $request->file('images');
        $model->images = $this->imageSave($model, $images, 'store');
        $model->save();
        return redirect()->route('product.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Products::find($id);
        $category_array = [];
        if(!empty($model->category)){
            $category_ = $model->category->name;
            $category_array = [$category_];
        }elseif(!empty($model->subCategory)){
            $category_ = !empty($model->subCategory->category)?$model->subCategory->category->name:'';
            $sub_category_ = $model->subCategory->name;
            if($category_ != ''){
                $category_array = [$category_, $sub_category_];
            }else{
                $category_array = [$sub_category_];
            }
        }elseif(!empty($model->subSubCategory)){
            $sub_sub_category_ = $model->subSubCategory->name;
            if(!empty($model->subSubCategory->sub_category)){
                $category_ = !empty($model->subSubCategory->sub_category->category)?$model->subSubCategory->sub_category->category->name:'';
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
        if(isset($product->subCategory->id)){
            $category_product = $product->subCategory;
            $is_category = 2;
            $current_category = isset($category_product->category)?$category_product->category:'no';
            $current_sub_category_id = isset($category_product->id)?$category_product->id:'no';
            $current_sub_sub_category_id = 'no';
        }elseif(isset($product->category->id)){
            $category_product = $product->category;
            $is_category = 1;
            $current_category = $category_product;
            $current_sub_category_id = 'no';
            $current_sub_sub_category_id = 'no';
        }elseif(isset($product->subSubCategory->id)) {
            $category_product = $product->subSubCategory;
            $is_category = 3;
            if(isset($category_product->sub_category)){
                if(isset($category_product->sub_category->category)){
                    $current_category = $category_product->sub_category->category;
                }else{
                    $current_category = 'no';
                }
                $current_sub_category_id = isset($category_product->sub_category->id) ? $category_product->sub_category->id : 'no';
            }else{
                $current_category = 'no';
                $current_sub_category_id = 'no';
            }
            $current_sub_sub_category_id = isset($category_product->id) ? $category_product->id : 'no';
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
        $model->description = $request->description;
        $model->sum = $request->sum;
        $images = $request->file('images');
        $model->images = $this->imageSave($model, $images, 'update');
        $model->save();
        return redirect()->route('product.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Products::find($id);
        if(isset($model->images)){
            $images = json_decode($model->images);
            foreach ($images as $image){
                $avatar_main = storage_path('app/public/products/'.$image);
                if(file_exists($avatar_main)){
                    unlink($avatar_main);
                }
            }
        }
        $model->delete();
        return redirect()->route('product.index')->with('status', translate('Successfully deleted'));
    }


    public function getProductCategory($product){
        if(isset($product->subSubCategory->id)){
            $category_product = $product->subSubCategory;
            $is_category = 3;
        }elseif(isset($product->subCategory->id)){
            $category_product = $product->subCategory;
            $is_category = 2;
        }elseif(isset($product->category->id)){
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
                $current_category = isset($category_product->category)?$category_product->category:'no';
                break;
            case 3:
                $current_category = isset($category_product->sub_category->category)?$category_product->sub_category->category:'no';
                break;
            default:
                $current_category = 'no';
        }
        return $current_category;
    }

    public function getProductCategoryLink($product){
            if(isset($product->subSubCategory->id)){
                $category_product = $product->subSubCategory;
                $is_category = 3;
            }elseif(isset($product->subCategory->id)){
                $category_product = $product->subCategory;
                $is_category = 2;
            }elseif(isset($product->category->id)){
                $category_product = $product->category;
                $is_category = 1;
            }else{
                $category_product = 'no';
                $is_category = 0;
            }
            switch ($is_category){
                case 1:
                    $category_product_id = $category_product->id-1;
                    $current_category_link = [
                        'name'=>$category_product->name,
                        'link'=>"/products/$category_product_id"
                    ];
                    $current_sub_category_link = [];
                    $current_sub_sub_category_link = [];
                    break;
                case 2:
                    $current_category = isset($category_product->category->id)?$category_product->category:'no';
                    $category_product_id = $current_category->id-1;
                    if($current_category != 'no'){
                        $current_category_link = [
                            "name"=>$current_category->name,
                            "link"=>"/products/$category_product_id"
                        ];
                    }else{
                        $current_category_link = [];
                    }
                    $current_sub_category_link = [
                        "name"=>$category_product->name,
                        "link"=>"/sub-category-products/$category_product->id"
                    ];
                    $current_sub_sub_category_link = [];
                    break;
                case 3:
                    $current_category = isset($category_product->sub_category->category->id)?$category_product->sub_category->category:'no';
                    $category_product_id = $current_category->id-1;
                    if($current_category != 'no'){
                        $current_category_link = [
                            "name"=>$current_category->name,
                            "link"=>"/products/$category_product_id"
                        ];
                    }else{
                        $current_category_link = [];
                    }
                    $sub_current_category = isset($category_product->sub_category->id)?$category_product->sub_category:'no';
                    if($sub_current_category != 'no'){
                        $current_sub_category_link = [
                            "name"=>$sub_current_category->name,
                            "link"=>"/sub-category-products/$sub_current_category->id"
                        ];
                    }else{
                        $current_sub_category_link = [];
                    }
                    $current_sub_sub_category_link = [
                        "name"=>$category_product->name,
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
            if(isset($product->images) && !is_array($product->images)){
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
            if(count($category->subcategory)>0){
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

    public function getProducts()
    {
        $products = Products::take(10)->get();
        $goods = [];
        foreach ($products as $key => $product) {
            $discount = $product->discount;
            $colors_array = [];
            if (isset($product->categorizedProducts)) {
                foreach ($product->categorizedProducts as $categorizedProduct) {
                    $colors_array[] = $categorizedProduct->color->id;
                }
                foreach (array_unique($colors_array) as $color) {
                    $productsByColor = [];
                    foreach ($product->categorizedProducts as $categorizedProduct){
                        if($color ==  $categorizedProduct->color->id){
                            $colorModel = $categorizedProduct->color;
                            if(!empty($discount)){
                                $categorizedProductSum = $discount->percent?$categorizedProduct->sum - $categorizedProduct->sum*(int)$discount->percent/100:$categorizedProduct->sum;
                            }else{
                                $categorizedProductSum = $categorizedProduct->sum;
                            }
                            $productsByColor[] = [
                                'size' => $categorizedProduct->size ? $categorizedProduct->size->name:'',
                                'sum' => $categorizedProductSum,
                                'discount' => !empty($product->discount)?$product->discount->percent:null,
                                'price'=>$categorizedProduct->sum,
                                'count' => $categorizedProduct->count
                            ];
                        }
                    }
                    $categorizedByColor[] = [
                        'color'=>$colorModel,
                        'products'=>$productsByColor
                    ];
                }
            }
            $images_ = json_decode($product->images);
            $images = [];
            foreach ($images_ as $image_) {
                $images[] = asset('storage/products/' . $image_);
            }
            $goods[$key]['id'] = $product->id;
            $goods[$key]['name'] = $product->name ?? null;
            $current_category = $this->getProductCategory($product);
            $goods[$key]['category'] = $current_category->name??null;
            $goods[$key]['description'] = $product->description ?? null;
            if(!empty($discount)){
                $goods[$key]['sum'] = $discount->percent?$product->sum - $product->sum*(int)$discount->percent/100:$product->sum;
            }else{
                $goods[$key]['sum'] = $product->sum ?? null;
            }
            $goods[$key]['price'] = $product->sum;
            $goods[$key]['discount'] = !empty($product->discount)?$product->discount->percent:null;
            $goods[$key]['company'] = $product->company ?? null;
            $goods[$key]['characters'] = $categorizedByColor??[];
            $goods[$key]['images'] = $images ?? [];
            $goods[$key]['basket_button'] = false;
            $goods[$key]['created_at'] = $product->created_at ?? null;
            $goods[$key]['updated_at'] = $product->updated_at ?? null;
        }
        $response = [
            'status'=>true,
            'data'=>$goods
        ];
        return response()->json($response, 200);
    }

    public function getAllProducts()
    {
        $products = Products::all();
        $goods = [];
        foreach ($products as $key => $product) {
            $discount = $product->discount;
            $colors_array = [];
            if (isset($product->categorizedProducts)) {
                foreach ($product->categorizedProducts as $categorizedProduct) {
                    $colors_array[] = $categorizedProduct->color->id;
                }
                foreach (array_unique($colors_array) as $color) {
                    $productsByColor = [];
                    foreach ($product->categorizedProducts as $categorizedProduct){
                        if($color ==  $categorizedProduct->color->id){
                            $colorModel = $categorizedProduct->color;
                            if(!empty($discount)){
                                $categorizedProductSum = $discount->percent?$categorizedProduct->sum - $categorizedProduct->sum*(int)$discount->percent/100:$categorizedProduct->sum;
                            }else{
                                $categorizedProductSum = $categorizedProduct->sum;
                            }
                            $productsByColor[] = [
                                'size' => $categorizedProduct->size ? $categorizedProduct->size->name:'',
                                'sum' => $categorizedProductSum,
                                'discount' => !empty($product->discount)?$product->discount->percent:null,
                                'price'=>$categorizedProduct->sum,
                                'count' => $categorizedProduct->count
                            ];
                        }
                    }
                    $categorizedByColor[] = [
                        'color'=>$colorModel,
                        'products'=>$productsByColor
                    ];
                }
            }
            $images_ = json_decode($product->images);
            $images = [];
            foreach ($images_ as $image_) {
                $images[] = asset('storage/products/' . $image_);
            }
            $goods[$key]['id'] = $product->id;
            $goods[$key]['name'] = $product->name ?? null;
            $current_category = $this->getProductCategory($product);
            $goods[$key]['category'] = $current_category->name??null;
            $goods[$key]['description'] = $product->description ?? null;
            if(!empty($discount)){
                $goods[$key]['sum'] = $discount->percent?$product->sum - $product->sum*(int)$discount->percent/100:$product->sum;
            }else{
                $goods[$key]['sum'] = $product->sum ?? null;
            }
            $goods[$key]['price'] = $product->sum;
            $goods[$key]['discount'] = !empty($product->discount)?$product->discount->percent:null;
            $goods[$key]['company'] = $product->company ?? null;
            $goods[$key]['characters'] = $categorizedByColor??[];
            $goods[$key]['images'] = $images ?? [];
            $goods[$key]['basket_button'] = false;
            $goods[$key]['created_at'] = $product->created_at ?? null;
            $goods[$key]['updated_at'] = $product->updated_at ?? null;
        }
        $response = [
            'status'=>true,
            'data'=>$goods
        ];
        return response()->json($response, 200);
    }

    public function getProduct($id)
    {
        $product = Products::find($id);
        $discount = $product->discount;
        if (isset($product->categorizedProducts)) {
            $colors_array = [];
            foreach ($product->categorizedProducts as $categorizedProduct_) {
                $colors_array[] = $categorizedProduct_->color->id;
                if($colors_array[0] == $categorizedProduct_->color->id){
                    if(!empty($discount)){
                        $categorizedProductSum_ = $discount->percent?$categorizedProduct_->sum - $categorizedProduct_->sum*(int)$discount->percent/100:$categorizedProduct_->sum;
                    }else{
                        $categorizedProductSum_ = $categorizedProduct_->sum;
                    }
                    $firstColorProducts[] = [
                        'id'=>$categorizedProduct_->id,
                        'size'=>$categorizedProduct_->size ? $categorizedProduct_->size->name:'',
                        'color'=>[
                            'id'=>$categorizedProduct_->color->id,
                            'name'=>$categorizedProduct_->color->name,
                            'code'=>$categorizedProduct_->color->code,
                        ],
                        'sum' => $categorizedProductSum_,
                        'discount' => !empty($product->discount)?$product->discount->percent:null,
                        'price'=>$categorizedProduct_->sum,
                        'count' => $categorizedProduct_->count
                    ];
                }
            }
            foreach (array_unique($colors_array) as $color) {
                $productsByColor = [];
                foreach ($product->categorizedProducts as $categorizedProduct){
                    if($color == $categorizedProduct->color->id){
                        $colorModel = $categorizedProduct->color;
                        if(!empty($discount)){
                            $categorizedProductSum = $discount->percent?$categorizedProduct->sum - $categorizedProduct->sum*(int)$discount->percent/100:$categorizedProduct->sum;
                        }else{
                            $categorizedProductSum = $categorizedProduct->sum;
                        }
                        $productsByColor[] = [
                            'id' => $categorizedProduct->id,
                            'size' => $categorizedProduct->size ? $categorizedProduct->size->name:'',
                            'color'=>[
                                'id'=>$categorizedProduct->color->id,
                                'name'=>$categorizedProduct->color->name,
                                'code'=>$categorizedProduct->color->code,
                            ],
                            'sum' => $categorizedProductSum,
                            'discount' => !empty($product->discount)?$product->discount->percent:null,
                            'price'=>$categorizedProduct->sum,
                            'count' => $categorizedProduct->count
                        ];
                    }
                }
                $categorizedByColor[] = [
                    'color'=>$colorModel,
                    'products'=>$productsByColor
                ];
            }
        }
        $good = [];
        if(isset($product->id)) {
            $images_ = json_decode($product->images);
            $images = [];
            foreach($images_ as $image_) {
                $images[] = asset('storage/products/' . $image_);
            }
            $good['id'] = $product->id;
            $good['name'] = $product->name ?? null;
            $current_category = $this->getProductCategory($product);
            $good['category'] = $current_category->name ?? null;
            $good['description'] = $product->description ?? null;
            if(!empty($discount)){
                $good['sum'] = $discount->percent?$product->sum - $product->sum*(int)$discount->percent/100:$product->sum;
            }else{
                $good['sum'] = $product->sum ?? null;
            }
            $good['price'] = $product->sum;
            $good['discount'] = !empty($product->discount)?$product->discount->percent:null;
            $good['company'] = $product->company ?? null;
            $good['characters'] = $categorizedByColor ?? [];
            $good['first_color_products'] = $firstColorProducts ?? [];
            $good['images'] = $images ?? [];
            $good['basket_button'] = false;
            $good['category_link'] = $this->getProductCategoryLink($product)[0];
            $good['sub_category_link'] = $this->getProductCategoryLink($product)[1];
            $good['sub_sub_category_link'] = $this->getProductCategoryLink($product)[2];
            $good['created_at'] = $product->created_at ?? null;
            $good['updated_at'] = $product->updated_at ?? null;
        }
        $response = [
            'status'=>true,
            'data'=>$good
        ];
        return response()->json($response, 200);
    }

    public function getCharacterizedProduct(Request $request)
    {
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
                    $category_name = null;
                    $product_ = Products::find($product->product_id);
                    if($product_){
                        $discount = $product_->discount;
                        if($product->sum){
                            if(!empty($discount)){
                                $categorizedProductSum = $discount->percent?$product->sum - (int)$product->sum*(int)$discount->percent/100:$product->sum;
                                $categorizedAllProductSum = $discount->percent?$product->sum*(int)$selected_product['count'] - (int)$product->sum*$selected_product['count']*(int)$discount->percent/100:$product->sum*(int)$selected_product['count'];
                            }else{
                                $categorizedProductSum = $product->sum;
                                $categorizedAllProductSum = $product->sum*(int)$selected_product['count'];
                            }
                        }else{
                            if(!empty($discount)){
                                $categorizedProductSum = $discount->percent?$product_->sum - $product_->sum*(int)$discount->percent/100:$product_->sum;
                                $categorizedAllProductSum = $discount->percent?$product_->sum*(int)$selected_product['count'] - $product_->sum*(int)$selected_product['count']*(int)$discount->percent/100:$product_->sum;
                            }else{
                                $categorizedAllProductSum = $product_->sum*(int)$selected_product['count'];
                                $categorizedProductSum = $product_->sum;
                            }
                        }
                        $images_ = json_decode($product_->images);
                        if(count($images_)>0){
                            $images = asset('storage/products/'.$images_[0]);
                        }else{
                            $images = '';
                        }
                        $company_name = $product_->company??null;
                        $category_name = !empty($product_->category)?$product_->category->name:null;
                    }
                    $all_sum = $all_sum + $categorizedAllProductSum??$product->sum*(int)$selected_product['count'];
                    $good[] = [
                        'id'=>$product->id,
                        'product_id'=>$product_->id,
                        'name'=>$product_->name,
                        'images'=>$images,
                        'company'=>$company_name,
                        'category'=>$category_name,
                        'size_id'=>$product->size_id,
                        'color'=>$product->color,
                        'count'=>$product->count,
                        'discount' => !empty($product->discount)?$product->discount->percent:null,
                        'sum'=>$categorizedProductSum??$product->sum,
                        'price'=>$product->sum??$product_->sum,
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
            $order_coupon_price = ($price/100)*($coupon->percent);
        }elseif($coupon->price){
            $order_coupon_price = $coupon->price;
        }
        return $order_coupon_price;
    }

    public function getFavouriteProducts(Request $request)
    {
        $good = [];
        $selected_products_id = $request->selected_products_id;
        foreach($selected_products_id as $selected_product_id){
            $images = null;
            $company_name = null;
            $category_name = null;
            $product = Products::find($selected_product_id);
            if($product){
                $discount = $product->discount;
                if($product->sum){
                    if(!empty($discount)){
                        $ProductSum = $discount->percent?$product->sum - $product->sum*(int)$discount->percent/100:$product->sum;
                    }else{
                        $ProductSum = $product->sum;
                    }
                }
                $images_ = json_decode($product->images);
                if(count($images_)>0){
                    $images = asset('storage/products/'.$images_[0]);
                }else{
                    $images = '';
                }
                $company_name = $product->company??null;
                $category_name = !empty($product->category)?$product->category->name:null;
                $good[] = [
                    'id'=>$product->id,
                    'name'=>$product->name,
                    'images'=>$images,
                    'company'=>$company_name,
                    'category'=>$category_name,
                    'size_id'=>$product->size_id,
                    'color'=>$product->color,
                    'count'=>$product->count,
                    'discount' => !empty($discount)?$discount->percent:null,
                    'sum'=>$ProductSum,
                    'price'=>$product->sum,
                ];
            }
        }
        $response = [
            'status'=>true,
            'data'=>$good
        ];
        return response()->json($response, 200);
    }

    public function BestSeller()
    {
        $products = Products::orderBy('id', 'DESC')->get();
        $goods = [];
        foreach ($products as $key => $product){
            $discount = $product->discount;
            $colors_array = [];
            if (isset($product->categorizedProducts)) {
                foreach ($product->categorizedProducts as $categorizedProduct) {
                    $colors_array[] = $categorizedProduct->color->id;
                }
                foreach (array_unique($colors_array) as $color) {
                    $productsByColor = [];
                    foreach ($product->categorizedProducts as $categorizedProduct){
                        if($color ==  $categorizedProduct->color->id){
                            $colorModel = $categorizedProduct->color;
                            if(!empty($discount)){
                                $categorizedProductSum = $discount->percent?$categorizedProduct->sum - $categorizedProduct->sum*(int)$discount->percent/100:$categorizedProduct->sum;
                            }else{
                                $categorizedProductSum = $categorizedProduct->sum;
                            }
                            $productsByColor[] = [
                                'size' => $categorizedProduct->size ? $categorizedProduct->size->name:'',
                                'sum' => $categorizedProductSum,
                                'discount' => !empty($product->discount)?$product->discount->percent:null,
                                'price'=>$categorizedProduct->sum,
                                'count' => $categorizedProduct->count
                            ];
                        }
                    }
                    $categorizedByColor[] = [
                        'color'=>$colorModel,
                        'products'=>$productsByColor
                    ];
                }
            }
            $images_ = json_decode($product->images);
            $images = [];
            foreach ($images_ as $image_){
                $images[] = asset('storage/products/'.$image_);
            }
            $goods[$key]['id'] = $product->id;
            $goods[$key]['name'] = $product->name??null;
            $current_category = $this->getProductCategory($product);
            $goods[$key]['category'] = $current_category->name??null;
            $goods[$key]['description'] = $product->description ?? null;
            if(!empty($discount)){
                $goods[$key]['sum'] = $discount->percent?$product->sum - $product->sum*(int)$discount->percent/100:$product->sum;
            }else{
                $goods[$key]['sum'] = $product->sum ?? null;
            }
            $goods[$key]['price'] = $product->sum;
            $goods[$key]['discount'] = !empty($product->discount)?$product->discount->percent:null;
            $goods[$key]['company'] = $product->company??null;
            $goods[$key]['characters'] = $categorizedByColor??[];
            $goods[$key]['images'] = $images??[];
            $goods[$key]['basket_button'] = false;
            $goods[$key]['created_at'] = $product->created_at??null;
            $goods[$key]['updated_at'] = $product->updated_at??null;
        }
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

    public function getProductsByCategories(){
        $categories = Category::where('step', 0)->get();
        $data = $this->getProductsByAllCategories($categories);
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
//    public function getProductsBySubSubCategories($id){
//        $categories = Category::where('step', 2)->get();
//        $data = $this->getProductsByAllCategories($categories);
//        return response()->json([
//            'status'=>true,
//            'message'=>'Success',
//            'data'=>$data
//        ]);
//    }

    public function getProductsByAllCategories($categories){
        $data = [];
        foreach ($categories as $category){
            $all_products = [];
            $category_ids = $this->getCategoriesId($category);
            $products = Products::select('id', 'name', 'category_id', 'images', 'sum', 'description')->whereIn('category_id', $category_ids)->get();
            foreach ($products as $product){
                $images = [];
                if(is_array($product->images)){
                    foreach ($product->images as $image){
                        $images[] = asset('storage/products/'.$image);
                    }
                }else{
                    foreach (json_decode($product->images) as $image){
                        $images[] = asset('storage/products/'.$image);
                    }
                }
                $all_products[] = [
                  'id'=>$product->id,
                  'name'=>$product->name,
                  'category_id'=>$product->category_id,
                  'images'=>$images,
                  'sum'=>$product->sum,
                  'description'=>$product->description,
                  'basket_button'=>false
                ];
            }
            $data[] = [
                'category'=>[
                    'id'=>$category->id,
                    'name'=>$category->name,
                ],
                'products'=>$all_products
            ];
        }
        return $data;
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
        if(isset($product->images) && !is_array($product->images)){
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
}
