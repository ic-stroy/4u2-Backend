<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Category;
use App\Models\CharacterizedProducts;
use App\Models\Coupon;
use App\Models\Discount;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts_distinct = Discount::distinct('discount_number')->get();
        $discounts_data = [];
        foreach ($discounts_distinct as $discount_distinct) {
            $discount_number = Discount::where('discount_number', $discount_distinct->discount_number)->get()->count();
            $discounts_data[] = [
                'discount'=>$discount_distinct,
                'number'=>$discount_number
            ];
        }
        return view('discount.index', ['discounts_data'=> $discounts_data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('step', 0)->orderBy('id', 'asc')->get();
        return view('discount.create', ['categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $discount_ = Discount::orderBy('discount_number', 'desc')->first();
        if($discount_){
            $discount_number = $discount_->discount_number + 1;
        }else{
            $discount_number = 1;
        }
        $products = $this->getProducts($request);
        foreach($products as $product){
            $discount = $this->newDiscount($request);
            $discount->product_id = $product->id;
            $discount->discount_number = $discount_number;
            $discount->save();
        }
        return redirect()->route('discount.index')->with('status', __('Successfully created'));
    }

    public function getProducts($request){
        $all_category = [];
        if(isset($request->subsubcategory_id) && $request->subsubcategory_id != "all" && $request->subsubcategory_id){
            $all_category[] = $request->subsubcategory_id;
        }elseif(isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id){
            $all_category[] = $request->subcategory_id;
            $sub_sub_categories_id = Category::where(['step'=> 2, 'parent_id'=>$request->subcategory_id])->pluck('id')->all();
            $all_category[] = $sub_sub_categories_id;
        }else{
            $category = Category::where('step', 0)->find($request->category_id);
            foreach($category->subcategory as $subcategory){
                $all_category[] = $subcategory->id;
                $sub_categories_id = Category::where(['step'=> 1, 'parent_id'=>$subcategory->id])->pluck('id')->all();
                $all_category[] = $sub_categories_id;
                $sub_sub_categories_id = Category::where('step', 2)->whereIn('parent_id', $sub_categories_id)->pluck('id')->all();
                $all_category[] = $sub_sub_categories_id;
            }
        }
        $products = Products::whereIn('category_id', $all_category)->get();
        return $products;
    }

    public function newDiscount($request){
        $discount = new Discount();
        $discount->percent = $request->percent;
        $discount->start_date = $request->start_date;
        $discount->end_date = $request->end_date;
        if(isset($request->subsubcategory_id) && $request->subsubcategory_id != "all" && $request->subsubcategory_id){
            $discount->category_id = $request->subsubcategory_id;
        }elseif(isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id){
            $discount->category_id = $request->subcategory_id;
        }else{
            $discount->category_id = $request->category_id;
        }
        return $discount;
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Discount::find($id);
        $discount_number = Discount::where('discount_number', $model->discount_number)->get()->count();
        $discounts_data = [
            'discount'=>$model,
            'number'=>$discount_number
        ];
        if($model->category){
            $category = $model->category->name;
            $subcategory = '';
        }elseif($model->subCategory){
            $category = $model->subCategory->category?$model->subCategory->category->name:'';
            $subcategory = $model->subCategory->name;
        }else{
            $category = '';
            $subcategory = '';
        }
        return view('discount.show', ['model'=>$model, 'discounts_data'=>$discounts_data, 'category'=>$category, 'subcategory'=>$subcategory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $discount = Discount::find($id);
        $categories = Category::where('step', 0)->orderBy('id', 'asc')->get();
        if(!empty($discount->category)){
            $category_id = $discount->category->id;
            $subcategory_id = '';
            $subsubcategory_id = '';
        }elseif(!empty($discount->subCategory)){
            if(!empty($discount->subCategory->category)){
                $category_id = $discount->subCategory->category->id;
            }
            $subcategory_id = $discount->subCategory->id;
            $subsubcategory_id = '';
        }elseif(!empty($discount->subSubCategory)){
            if(!empty($discount->subSubCategory->sub_category)){
                if(!empty($discount->subSubCategory->sub_category->category)){
                    $category_id = $discount->subSubCategory->sub_category->category->id;
                }
                $subcategory_id = $discount->subSubCategory->sub_category->id;
            }
            $subsubcategory_id = $discount->subSubCategory->id;
        }else{
            $category_id = '';
            $subcategory_id = '';
            $subsubcategory_id = '';
        }
        return view('discount.edit', ['discount'=> $discount, 'categories'=>$categories, 'category_id'=>$category_id, 'subcategory_id'=>$subcategory_id, 'subsubcategory_id'=>$subsubcategory_id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $discount_ = Discount::orderBy('discount_number', 'desc')->first();
        if($discount_){
            $discount_number = $discount_->discount_number + 1;
        }else{
            $discount_number = 1;
        }
        $products = $this->getProducts($request);
        $current_discount = Discount::find($id);
        $current_discount_group = Discount::where('discount_number', $current_discount->discount_number)->get();
        foreach ($current_discount_group as $currentDiscount){
            $currentDiscount->delete();
        }
        foreach($products as $product){
            $discount = $this->newDiscount($request);
            $discount->product_id = $product->id;
            $discount->discount_number = $discount_number;
            $discount->save();
        }
        return redirect()->route('discount.index')->with('status', __('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $current_discount = Discount::find($id);
        $current_discount_group = Discount::where('discount_number', $current_discount->discount_number)->get();
        foreach ($current_discount_group as $currentDiscount){
            $currentDiscount->delete();
        }
        return redirect()->route('discount.index')->with('status', __('Successfully created'));
    }
}
