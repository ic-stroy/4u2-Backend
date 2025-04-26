<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CharacterizedProducts;
use App\Models\Color;
use App\Models\Products;
use App\Models\Sizes;
use Illuminate\Http\Request;

class CharacterizedProductsController extends Controller
{

    public $current_page = 'warehouse';
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

        return view('characterized-products.index', array_merge(['all_products'=> $all_products, 'categories'=> $categories, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $getCommonData = $this->getCommonData();
        $products = Products::get();
        $colors = Color::get();
        return view('characterized-products.create', array_merge(['colors'=> $colors, 'products'=> $products, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new CharacterizedProducts();
        $model->product_id = $request->product_id;
        $model->sum = $request->sum;
        if($request->size_id){
            $model->size_id = $request->size_id;
        }elseif($request->size_name){
            $size = Sizes::withTrashed()->where('name', $request->size_name)->whereNULL('category_id')->first();
            if(!$size){
                $size = new Sizes();
                $size->name = $request->size_name;
                $size->save();
            }elseif($size->deleted_at){
                $size->deleted_at = NULL;
                $size->save();
            }
            $model->size_id = $size->id;
        }
        if($request->color_id){
            $model->color_id = $request->color_id;
        }
        $model->count = $request->count;
        $model->save();
        return redirect()->route('characterizedProducts.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getCommonData = $this->getCommonData();
        $model = CharacterizedProducts::with([
            'product',
            'product.category',
            'product.subCategory.category',
            'product.subSubCategory',
            'product.subSubCategory.sub_category',
            'product.subSubCategory.sub_category.category',
        ])->find($id);
        $colors_array = json_decode($model->colors_id);
        $colors = Color::select('name', 'code')->whereIn('id', $colors_array??[])->get();
        $category_ = '';
        $category_array = [];
        $sub_category_ = '';
        $sub_sub_category_ = '';
        if($product = $model->product){
            if($product->category){
                $category_ = $product->category->name;
                $category_array = [$category_];
            }elseif($product->subCategory){
                $category_ = $product->subCategory->category?$product->subCategory->category->name:'';
                $sub_category_ = $product->subCategory->name;
                if($category_ != ''){
                    $category_array = [$category_, $sub_category_];
                }else{
                    $category_array = [$sub_category_];
                }
            }elseif($product->subSubCategory){
                $sub_sub_category_ = $product->subSubCategory->name;
                if($product->subSubCategory->sub_category){
                    $category_ = $product->subSubCategory->sub_category?$product->subSubCategory->sub_category->category->name:'';
                    $sub_category_ = $product->subSubCategory->sub_category->name;
                    if($category_ != ''){
                        $category_array = [$category_, $sub_category_, $sub_sub_category_];
                    }else{
                        $category_array = [$sub_category_, $sub_sub_category_];
                    }
                }else{
                    $category_array = [$sub_sub_category_];
                }
            }
        }

        return view('characterized-products.show', array_merge(['model'=>$model, 'colors'=>$colors, 'category_array'=>$category_array, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $getCommonData = $this->getCommonData();
        $characterized_product = CharacterizedProducts::with([
            'product',
            'product.subSubCategory',
            'product.subCategory',
            'product.category',
            'product.subSubCategory.sub_category',
            'product.subCategory.category'
        ])->find($id);
        if($characterized_product->product){
            $product = $characterized_product->product;
            $current_category = $this->getProductCategory($characterized_product->product);
            $sizes = Sizes::select('id', 'name', 'category_id')->where('category_id', $current_category->id)->get();
        }else{
            $current_category = 'no';
            $sizes = 'no';
            $product = 'no';
        }
        $colors = Color::get();
        return view('characterized-products.edit', array_merge(['characterized_product'=> $characterized_product, 'sizes'=> $sizes, 'current_category'=> $current_category, 'colors'=> $colors, 'product'=> $product, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = CharacterizedProducts::find($id);
        $model->product_id = $request->product_id;
        $model->sum = $request->sum;
        if($request->size_id){
            $model->size_id = $request->size_id;
        }elseif($request->size_name){
            $size = Sizes::withTrashed()->where('name', $request->size_name)->whereNULL('category_id')->first();
            if(!$size){
                $size = new Sizes();
                $size->name = $request->size_name;
                $size->save();
            }elseif($size->deleted_at){
                $size->deleted_at = NULL;
                $size->save();
            }
            $model->size_id = $size->id;
        }else{
            $size_quantity = Sizes::where('id', $model->size_id)->whereNULL('category_id')->count();
            if($size_quantity == 1){
                $size = Sizes::where('id', $model->size_id)->whereNULL('category_id')->first();
                $size->delete();
            }
            $model->size_id = NULL;
        }
        if($request->color_id){
            $model->color_id = $request->color_id;
        }else{
            $model->color_id = NULL;
        }
        $model->count = $request->count;
        $model->save();
        return redirect()->route('characterizedProducts.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = CharacterizedProducts::with('order_detail')->find($id);
        if($model->order_detail){
            return redirect()->back()->with('error', translate('You cannot delete this product because here is product associated with an order.'));
        }
        $size_quantity = Sizes::whereNULL('category_id')->where('id', $model->size_id)->count();
        if($size_quantity == 1){
            $size = Sizes::where('id', $model->size_id)->whereNULL('category_id')->first();
            $size->delete();
        }
        $model->delete();
        return redirect()->route('characterizedProducts.index')->with('status', translate('Successfully deleted'));
    }

    public function category()
    {
        $getCommonData = $this->getCommonData();
        $category = Category::where('step', 0)->get();
        return view('characterized-products.category', array_merge(['categories'=>$category, 'current_page'=>$this->current_page], $getCommonData));
    }

    public function product($id)
    {
        $getCommonData = $this->getCommonData();
        $category = Category::with([
            'subcategory',
            'subcategory.products',
            'subcategory.subsubcategory',
            'subcategory.subsubcategory.products',
        ])->find($id);
        if($category){
            $products = $category->subcategory->flatMap(function($subcategory){
                $subcategory->flatMap(function($subsubcategory){
                    return $subsubcategory->products;
                });
                return $subcategory->products;
            });
        }
        if($category->products->isNotEmpty()){
            $products->merge($category->products);
        }
        return view('characterized-products.products', array_merge(['products'=>$products, 'current_page'=>$this->current_page], $getCommonData));
    }
    public function characterizedProduct($id){
        $getCommonData = $this->getCommonData();
        $characterized_products = CharacterizedProducts::where('product_id', $id)->get();
        $product = Products::select('id', 'name')->find($id);
        return view('characterized-products.characterizedproduct', array_merge(['characterized_products'=>$characterized_products, 'product'=>$product, 'current_page'=>$this->current_page], $getCommonData));
    }
    public function createCharacterizedProduct($id){
        $getCommonData = $this->getCommonData();
        $colors = Color::get();
        $product = Products::find($id);
        $current_category = $this->getProductCategory($product);
        return view('characterized-products.create_characterized_product', array_merge(['product'=>$product, 'colors'=>$colors, 'current_category'=>$current_category, 'current_page'=>$this->current_page], $getCommonData));
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
                $current_category = $category_product->sub_category->category?$category_product->sub_category->category:'no';
                break;
            default:
                $current_category = 'no';
        }
        return $current_category;
    }
}
