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
            $subsubcategory = [];
            $subcategory = [];
            $category = [];
            $discount_number = Discount::where('discount_number', $discount_distinct->discount_number)->count();
            $discount_data = Discount::where('discount_number', $discount_distinct->discount_number)->get();
            foreach($discount_data as $discount__data){
                if(!empty($discount__data->category)){
                    if(!in_array($discount__data->category->name, $category)){
                        $category[] = $discount__data->category->name;
                    }
                    $subcategory = [1, 2];
                    $subsubcategory = [1, 2];
                }elseif(!empty($discount__data->subCategory)){
                    if(!empty($discount__data->subCategory->category)){
                        if(!in_array($discount__data->subCategory->category->name, $category)){
                            $category[] = $discount__data->subCategory->category->name;
                        }
                    }
                    if(!in_array($discount__data->subCategory->name, $subcategory)){
                        $subcategory[] = $discount__data->subCategory->name;
                    }
                    $subsubcategory = [1, 2];
                }elseif(!empty($discount__data->subSubCategory)){
                    if(!empty($discount__data->subSubCategory->sub_category)){
                        if(!empty($discount__data->subSubCategory->sub_category->category)){
                            if(!in_array($discount__data->subSubCategory->sub_category->category->name, $category)){
                                $category[] = $discount__data->subSubCategory->sub_category->category->name;
                            }
                        }
                        if(!in_array($discount__data->subSubCategory->sub_category->name, $subcategory)){
                            $subcategory[] = $discount__data->subSubCategory->sub_category->name;
                        }
                    }
                    if(!in_array($discount__data->subSubCategory->name, $subcategory)){
                        $subsubcategory[] = $discount__data->subSubCategory->name;
                    }
                }
            }
            if(count($category) == 1){
                $category = [$category[0]];
            }elseif(count($category) > 1){
                $category = [translate('All categories')];
            }else{
                $category = [''];
            }

            if(count($subcategory) == 1){
                $subcategory = [$subcategory[0]];
            }elseif(count($subcategory) > 1){
                $subcategory = [translate('All subcategories')];
            }else{
                $subcategory = [''];
            }

            if(count($subsubcategory) == 1){
                $subsubcategory = [$subsubcategory[0]];
            }elseif(count($subsubcategory) > 1){
                $subsubcategory = [translate('All subsubcategories')];
            }else{
                $subsubcategory = [''];
            }
            $discounts_data[] = [
                'discount'=>$discount_data,
                'number'=>$discount_number,
                'category'=>$category,
                'subcategory'=>$subcategory,
                'subsubcategory'=>$subsubcategory
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
            $discount = $this->newDiscount($request, $product->category_id);
            $discount->product_id = $product->id;
            $discount->discount_number = $discount_number;
            $discount->save();
        }
        return redirect()->route('discount.index')->with('status', translate('Successfully created'));
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
            if(!empty($sub_sub_categories_id)) {
                $all_category = array_merge($all_category, $sub_sub_categories_id);
            }
            $products = Products::whereIn('category_id', $all_category)->get();
        }elseif(isset($request->category_id) && $request->category_id){
            $all_category[] = (int)$request->category_id;
            $sub_categories_id = Category::where(['step'=> 1, 'parent_id'=>$request->category_id])->pluck('id')->all();
            if(!empty($sub_categories_id)){
                $all_category = array_merge($all_category, $sub_categories_id);
            }
            $sub_sub_categories_id = Category::where('step', 2)->whereIn('parent_id', $sub_categories_id)->pluck('id')->all();
            if(!empty($sub_sub_categories_id)){
                $all_category = array_merge($all_category, $sub_sub_categories_id);
            }
            $products = Products::whereIn('category_id', $all_category)->get();
        }else{
            $categories = Category::where('step', 0)->get();
            foreach($categories as $category){
                foreach($category->subcategory as $subcategory){
                    $all_category[] = $subcategory->id;
                    $sub_categories_id = Category::where(['step'=> 1, 'parent_id'=>$subcategory->id])->pluck('id')->all();
                    if(!empty($sub_categories_id)){
                        $all_category[] = $sub_categories_id;
                    }
                    $sub_sub_categories_id = Category::where('step', 2)->whereIn('parent_id', $sub_categories_id)->pluck('id')->all();
                    if(!empty($sub_sub_categories_id)){
                        $all_category[] = $sub_sub_categories_id;
                    }
                }
                $categories_id[] = $category->id;
            }
            $products = Products::whereIn('category_id', array_merge($all_category, $categories_id))->get();
        }
        return $products;
    }

    public function newDiscount($request, $category_id){
        $discount = new Discount();
        $discount->percent = $request->percent;
        $discount->start_date = $request->start_date;
        $discount->end_date = $request->end_date;
        if(isset($request->subsubcategory_id) && $request->subsubcategory_id != "all" && $request->subsubcategory_id){
            $discount->category_id = $request->subsubcategory_id;
        }elseif(isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id){
            $discount->category_id = $request->subcategory_id;
        }elseif(isset($request->category_id) && $request->category_id){
            $discount->category_id = $request->category_id;
        }else{
            $discount->category_id = $category_id;
        }
        return $discount;
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Discount::select('discount_number')->find($id);
        $discount_number = Discount::where('discount_number', $model->discount_number)->count();
        $discounts = Discount::where('discount_number', $model->discount_number)->get();

        $subcategory = [];
        $subsubcategory = [];
        $category = [];
        foreach($discounts as $discount){
            if(!empty($discount->category)){
                if(!in_array($discount->category->name, $category)){
                    $category[] = $discount->category->name;
                }
                $subcategory = [1, 2];
                $subsubcategory = [1, 2];
            }elseif(!empty($discount->subCategory)){
                if(!empty($discount->subCategory->category)){
                    if(!in_array($discount->subCategory->category->name, $category)){
                        $category[] = $discount->subCategory->category->name;
                    }
                }
                if(!in_array($discount->subCategory->name, $subcategory)){
                    $subcategory[] = $discount->subCategory->name;
                }
                $subsubcategory = [1, 2];
            }elseif(!empty($discount->subSubCategory)){
                if(!empty($discount->subSubCategory->sub_category)){
                    if(!empty($discount->subSubCategory->sub_category->category)){
                        if(!in_array($discount->subSubCategory->sub_category->category->name, $category)){
                            $category[] = $discount->subSubCategory->sub_category->category->name;
                        }
                    }
                    if(!in_array($discount->subSubCategory->sub_category->name, $subcategory)){
                        $subcategory[] = $discount->subSubCategory->sub_category->name;
                    }
                }
                if(!in_array($discount->subSubCategory->name, $subsubcategory)){
                    $subsubcategory[] = $discount->subSubCategory->name;
                }
            }
        }
        if(count($category) == 1){
            $category = [$category[0]];
        }elseif(count($category) > 1){
            $category = [translate('All categories')];
        }else{
            $category = [''];
        }

        if(count($subcategory) == 1){
            $subcategory = [$subcategory[0]];
        }elseif(count($subcategory) > 1){
            $subcategory = [translate('All subcategories')];
        }else{
            $subcategory = [''];
        }

        if(count($subsubcategory) == 1){
            $subsubcategory = [$subsubcategory[0]];
        }elseif(count($subsubcategory) > 1){
            $subsubcategory = [translate('All subsubcategories')];
        }else{
            $subsubcategory = [''];
        }
        $discount_data = [
            'discount'=>$discounts,
            'number'=>$discount_number,
            'category'=>$category,
            'subcategory'=>$subcategory,
            'subsubcategory'=>$subsubcategory
        ];
        return view('discount.show', ['discount_data'=>$discount_data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $discount = Discount::find($id);
        $categories = Category::where('step', 0)->orderBy('id', 'asc')->get();
        $category_id = [];
        $subcategory_id = [];
        $subsubcategory_id = [];
        $quantity = 0;
        $discount_data = Discount::where('discount_number', $discount->discount_number)->get();
        foreach($discount_data as $discount__data){
            $quantity++;
            if(!empty($discount__data->category)){
                if(!in_array($discount__data->category->id, $category_id)){
                    $category_id[] = $discount__data->category->id;
                }
                $subcategory_id = ['a', 'a'];
                $subsubcategory_id = ['a', 'a'];
            }elseif(!empty($discount__data->subCategory)){
                if(!empty($discount__data->subCategory->category)){
                    if(!in_array($discount__data->subCategory->category->id, $category_id)){
                        $category_id[] = $discount__data->subCategory->category->id;
                    }
                }
                if(!in_array($discount__data->subCategory->id, $subcategory_id)){
                    $subcategory_id[] = $discount__data->subCategory->id;
                }
                $subsubcategory_id = ['a', 'a'];
            }elseif(!empty($discount__data->subSubCategory)){
                if(!empty($discount__data->subSubCategory->sub_category)){
                    if(!empty($discount__data->subSubCategory->sub_category->category)){
                        if(!in_array($discount__data->subSubCategory->sub_category->category->id, $category_id)){
                            $category_id[] = $discount__data->subSubCategory->sub_category->category->id;
                        }
                    }
                    if(!in_array($discount__data->subSubCategory->sub_category->id, $subcategory_id)){
                        $subcategory_id[] = $discount__data->subSubCategory->sub_category->id;
                    }
                }
                if(!in_array($discount__data->subSubCategory->id, $subsubcategory_id)){
                    $subsubcategory_id[] = $discount__data->subSubCategory->id;
                }
            }
        }

        if(count($category_id) == 1){
            $category_id = $category_id[0];
        }elseif(count($category_id) > 1){
            $category_id = 'two';
        }else{
            $category_id = '';
        }

        if(count($subcategory_id) == 1){
            $subcategory_id = $subcategory_id[0];
        }elseif(count($subcategory_id) > 1){
            $subcategory_id = 'two';
        }else{
            $subcategory_id = '';
        }

        if(count($subsubcategory_id) == 1){
            $subsubcategory_id = $subsubcategory_id[0];
        }elseif(count($subsubcategory_id) > 1){
            $subsubcategory_id = 'two';
        }else{
            $subsubcategory_id = '';
        }


        return view('discount.edit', [
            'discount'=> $discount,
            'categories'=>$categories,
            'category_id'=>$category_id,
            'subcategory_id'=>$subcategory_id,
            'subsubcategory_id'=>$subsubcategory_id,
            'quantity'=>$quantity
        ]);
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
            $discount = $this->newDiscount($request, $product->category_id);
            $discount->product_id = $product->id;
            $discount->discount_number = $discount_number;
            $discount->save();
        }
        return redirect()->route('discount.index')->with('status', translate('Successfully updated'));
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
        return redirect()->route('discount.index')->with('status', translate('Successfully created'));
    }
}
