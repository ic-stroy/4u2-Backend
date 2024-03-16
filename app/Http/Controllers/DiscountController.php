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
        $subsubcategory = '';
        $subcategory = '';
        $category = '';
        foreach ($discounts_distinct as $discount_distinct) {
            $discount_number = Discount::where('discount_number', $discount_distinct->discount_number)->get()->count();
            $discount_data = Discount::where('discount_number', $discount_distinct->discount_number)->get();
            foreach($discount_data as $discount__data){
                if(!empty($discount__data->category)){
                    $category = $discount__data->category->name;
                }elseif(!empty($discount__data->subCategory)){
                    if(!empty($discount__data->subCategory->category)){
                        $category = $discount__data->subCategory->category->name;
                    }
                    $subcategory = $discount__data->subCategory->name;
                }elseif(!empty($discount__data->subSubCategory)){
                    if(!empty($discount__data->subSubCategory->sub_category)){
                        if(!empty($discount__data->subSubCategory->sub_category->category)){
                            $category = $discount__data->subSubCategory->sub_category->category->name;
                        }
                        $subcategory = $discount__data->subSubCategory->sub_category->name;
                    }
                    $subsubcategory = $discount__data->subSubCategory->name;
                }
            }
            $discounts_data[] = [
                'discount'=>$discount_data,
                'number'=>$discount_number
            ];
        }
        return view('discount.index', ['discounts_data'=> $discounts_data, 'subsubcategory'=> $subsubcategory, 'subcategory'=> $subcategory, 'category'=> $category]);
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
        if(isset($request->product_id) && $request->product_id != "all" && $request->product_id){
            $products = Products::where('id', $request->product_id)->get();
        }elseif(isset($request->subsubcategory_id) && $request->subsubcategory_id != "all" && $request->subsubcategory_id){
            $all_category[] = $request->subsubcategory_id;
            $products = Products::whereIn('category_id', $all_category)->get();
        }elseif(isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id){
            $all_category[] = $request->subcategory_id;
            $sub_sub_categories_id = Category::where(['step'=> 2, 'parent_id'=>$request->subcategory_id])->pluck('id')->all();
            $all_category[] = $sub_sub_categories_id;
            $products = Products::whereIn('category_id', $all_category)->get();
        }elseif(isset($request->category_id) && $request->category_id != "all" && $request->category_id){
            $category = Category::where('step', 0)->find($request->category_id);
            foreach($category->subcategory as $subcategory){
                $all_category[] = $subcategory->id;
                $sub_categories_id = Category::where(['step'=> 1, 'parent_id'=>$subcategory->id])->pluck('id')->all();
                $all_category[] = $sub_categories_id;
                $sub_sub_categories_id = Category::where('step', 2)->whereIn('parent_id', $sub_categories_id)->pluck('id')->all();
                $all_category[] = $sub_sub_categories_id;
            }
            $products = Products::whereIn('category_id', $all_category)->get();
        }
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
        $discounts = Discount::where('discount_number', $model->discount_number)->get();
        $discounts_data = [
            'discounts'=>$discounts,
            'number'=>$discount_number
        ];
        $category = !empty($model->category)?$model->category->name:'';
        $subcategory = !empty($model->subCategory)?$model->subCategory->name:'';
        $subsubcategory = !empty($model->subSubCategory)?$model->subSubCategory->name:'';
        return view('discount.show', ['model'=>$model, 'discounts_data'=>$discounts_data, 'category'=>$category, 'subcategory'=>$subcategory, 'subsubcategory'=>$subsubcategory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $discount = Discount::find($id);
        $categories = Category::where('step', 0)->orderBy('id', 'asc')->get();
        $category_id = '';
        $subcategory_id = '';
        $subsubcategory_id = '';
        if(!empty($discount->category)){
            $category_id = $discount->category->id;
        }elseif(!empty($discount->subCategory)){
            if(!empty($discount->subCategory->category)){
                $category_id = $discount->subCategory->category->id;
            }
            $subcategory_id = $discount->subCategory->id;
        }elseif(!empty($discount->subSubCategory)){
            if(!empty($discount->subSubCategory->sub_category)){
                if(!empty($discount->subSubCategory->sub_category->category)){
                    $category_id = $discount->subSubCategory->sub_category->category->id;
                }
                $subcategory_id = $discount->subSubCategory->sub_category->id;
            }
            $subsubcategory_id = $discount->subSubCategory->id;
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
