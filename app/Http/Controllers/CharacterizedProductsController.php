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

            $category_ = '';
            $sub_category_ = '';
            $sub_sub_category_ = '';
            foreach($products as $product){
                if(!empty($product->category)){
                    $category_ = $product->category->name;
                }elseif(!empty($product->subCategory)){
                    $category_ = !empty($product->subCategory->category)?$product->subCategory->category->name:'';
                    $sub_category_ = $product->subCategory->name;
                }elseif(!empty($product->subSubCategory)){
                    if(!empty($product->subSubCategory->sub_category)){
                        $category_ = !empty($product->subSubCategory->sub_category->category)?$product->subSubCategory->sub_category->category->name:'';
                        $sub_category_ = $product->subSubCategory->sub_category->name;
                    }
                    $sub_sub_category_ = $product->subSubCategory->name;
                }
                $all_products[$category->id][$product->id] = ['product'=>$product, 'sub_sub_category_'=>$sub_sub_category_, 'sub_category_'=>$sub_category_, 'category_'=>$category_];
            }
        }

        return view('characterized-products.index', ['all_products'=> $all_products, 'categories'=> $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Products::all();
        $colors = Color::all();
        return view('characterized-products.create', ['colors'=> $colors, 'products'=> $products]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new CharacterizedProducts();
        $model->product_id = $request->product_id;
        $model->sum = $request->sum;
        $model->size_id = $request->size_id;
        $model->color_id = $request->color_id;
        $model->count = $request->count;
        $model->save();
        return redirect()->route('characterizedProducts.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = CharacterizedProducts::find($id);
        $colors_array = json_decode($model->colors_id);
        $colors = Color::select('name', 'code')->whereIn('id', $colors_array??[])->get();
        return view('characterized-products.show', ['model'=>$model, 'colors'=>$colors]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $characterized_product = CharacterizedProducts::find($id);
        if(isset($characterized_product->product)){
            $product = $characterized_product->product;
            $current_category = $this->getProductCategory($characterized_product->product);
            $sizes = Sizes::select('id', 'name', 'category_id')->where('category_id', $current_category->id)->get();
        }else{
            $current_category = 'no';
            $sizes = 'no';
            $product = 'no';
        }
        $colors = Color::all();
        return view('characterized-products.edit', ['characterized_product'=> $characterized_product, 'sizes'=> $sizes, 'current_category'=> $current_category, 'colors'=> $colors, 'product'=> $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = CharacterizedProducts::find($id);
        $model->product_id = $request->product_id;
        $model->sum = $request->sum;
        $model->size_id = $request->size_id;
        $model->color_id = $request->color_id;
        $model->count = $request->count;
        $model->save();
        return redirect()->route('characterizedProducts.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = CharacterizedProducts::find($id);
        $model->delete();
        return redirect()->route('characterizedProducts.index')->with('status', translate('Successfully deleted'));
    }

    public function category()
    {
        $category = Category::where('step', 0)->get();
        return view('characterized-products.category', ['categories'=>$category]);
    }

    public function product($id)
    {
        $category = Category::find($id);
        $subcategories = $category->subcategory;
        $category_ids = [];
        foreach ($subcategories as $subcategory){
            $category_ids[] = $subcategory->id;
        }
        $subsubcategories = Category::WhereIn('parent_id', $category_ids)->get();
        foreach ($subsubcategories as $subsubcategory){
            $category_ids[] = $subsubcategory->id;
        }
        $category_ids[] = $category->id;
        $products = Products::whereIn('category_id', $category_ids)->get();
        return view('characterized-products.products', ['products'=>$products]);
    }
    public function characterizedProduct($id){
        $characterized_products = CharacterizedProducts::where('product_id', $id)->get();
        $product = Products::select('id', 'name')->find($id);
        return view('characterized-products.characterizedproduct', ['characterized_products'=>$characterized_products, 'product'=>$product]);
    }
    public function createCharacterizedProduct($id){
        $colors = Color::all();
        $product = Products::find($id);
        $current_category = $this->getProductCategory($product);
        $characterized_products = CharacterizedProducts::where('product_id', $id)->get();
        return view('characterized-products.create_characterized_product', ['characterized_products'=>$characterized_products, 'product'=>$product, 'colors'=>$colors, 'current_category'=>$current_category]);
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
}
