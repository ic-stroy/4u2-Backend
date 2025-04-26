<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    public $current_page = 'discount';

    public function index()
    {
        $getCommonData = $this->getCommonData();
        $discounts_distinct = Discount::distinct('discount_number')->get();
        $discounts_data = [];
        foreach ($discounts_distinct as $discount_distinct) {
            $subsubcategory = [];
            $subcategory = [];
            $category = [];
            $discount_data = Discount::with([
                'category',
                'subCategory',
                'subCategory.category',
                'subSubCategory',
                'subSubCategory.sub_category',
                'subSubCategory.sub_category.category'
            ])->where('discount_number', $discount_distinct->discount_number)->get();
            $discount_number = count($discount_data);
            foreach($discount_data as $discount__data){
                if($discount__data->category){
                    if(!in_array($discount__data->category->name, $category)){
                        $category[] = $discount__data->category->name;
                    }
                    $subcategory = [1, 2];
                    $subsubcategory = [1, 2];
                }elseif($discount__data->subCategory){
                    if($discount__data->subCategory->category){
                        if(!in_array($discount__data->subCategory->category->name, $category)){
                            $category[] = $discount__data->subCategory->category->name;
                        }
                    }
                    if(!in_array($discount__data->subCategory->name, $subcategory)){
                        $subcategory[] = $discount__data->subCategory->name;
                    }
                    $subsubcategory = [1, 2];
                }elseif($discount__data->subSubCategory){
                    if($discount__data->subSubCategory->sub_category){
                        if($discount__data->subSubCategory->sub_category->category){
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
        return view('discount.index', array_merge(['discounts_data'=> $discounts_data, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $getCommonData = $this->getCommonData();
        $categories = Category::where('step', 0)->orderBy('id', 'asc')->get();
        return view('discount.create', array_merge(['categories'=>$categories, 'current_page'=>$this->current_page], $getCommonData));
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
        if($products->isEmpty()){
            return redirect()->back()->with('error', translate('There is no product in this category'));
        }
        if(isset($request->subsubcategory_id) && $request->subsubcategory_id != "all" && $request->subsubcategory_id){
            $category_id_ = $request->subsubcategory_id;
        }elseif(isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id){
            $category_id_ = $request->subcategory_id;
        }elseif(isset($request->category_id) && $request->category_id){
            $category_id_ = $request->category_id;
        }else{
            $category_id_ = '';
        }
        $discounts_data = $products->map(function($product) use($discount_number, $request, $category_id_) {
            return [
                'percent' => $request->percent,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'category_id' => $category_id_,
                'product_id' => $product->id,
                'discount_number' => $discount_number
            ];
        });
        DB::table('discounts')->insert($discounts_data->toArray());
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
            $categories_id = Category::with('subcategory')->pluck('id')->all();
            $products = Products::whereIn('category_id', $categories_id)->get();
        }
        return $products;
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getCommonData = $this->getCommonData();
        $model = Discount::select('discount_number')->find($id);
        $discount_number = Discount::where('discount_number', $model->discount_number)->count();
        $discounts = Discount::with([
            'category',
            'subCategory',
            'subCategory.category',
            'subSubCategory',
            'subSubCategory.sub_category',
            'subSubCategory.sub_category.category'
        ])->where('discount_number', $model->discount_number)->get();
        $subcategory = [];
        $subsubcategory = [];
        $category = [];
        foreach($discounts as $discount){
            if($discount->category){
                if(!in_array($discount->category->name, $category)){
                    $category[] = $discount->category->name;
                }
                $subcategory = [1, 2];
                $subsubcategory = [1, 2];
            }elseif($discount->subCategory){
                if($discount->subCategory->category){
                    if(!in_array($discount->subCategory->category->name, $category)){
                        $category[] = $discount->subCategory->category->name;
                    }
                }
                if(!in_array($discount->subCategory->name, $subcategory)){
                    $subcategory[] = $discount->subCategory->name;
                }
                $subsubcategory = [1, 2];
            }elseif($discount->subSubCategory){
                if($discount->subSubCategory->sub_category){
                    if($discount->subSubCategory->sub_category->category){
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
        return view('discount.show', array_merge(['discount_data'=>$discount_data, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $getCommonData = $this->getCommonData();
        $discount = Discount::find($id);
        $categories = Category::where('step', 0)->orderBy('id', 'asc')->get();
        $category_id = [];
        $subcategory_id = [];
        $subsubcategory_id = [];
        $quantity = 0;
        $discount_data = Discount::with([
            'category',
            'subCategory',
            'subCategory.category',
            'subSubCategory',
            'subCategory.sub_category',
            'subCategory.sub_category.category',
        ])->where('discount_number', $discount->discount_number)->get();
        foreach($discount_data as $discount__data){
            $quantity++;
            if($discount__data->category){
                if(!in_array($discount__data->category->id, $category_id)){
                    $category_id[] = $discount__data->category->id;
                }
                $subcategory_id = ['a', 'a'];
                $subsubcategory_id = ['a', 'a'];
            }elseif($discount__data->subCategory){
                if($discount__data->subCategory->category){
                    if(!in_array($discount__data->subCategory->category->id, $category_id)){
                        $category_id[] = $discount__data->subCategory->category->id;
                    }
                }
                if(!in_array($discount__data->subCategory->id, $subcategory_id)){
                    $subcategory_id[] = $discount__data->subCategory->id;
                }
                $subsubcategory_id = ['a', 'a'];
            }elseif($discount__data->subSubCategory){
                if($discount__data->subSubCategory->sub_category){
                    if($discount__data->subSubCategory->sub_category->category){
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


        return view('discount.edit', array_merge([
            'discount'=> $discount,
            'categories'=>$categories,
            'category_id'=>$category_id,
            'subcategory_id'=>$subcategory_id,
            'subsubcategory_id'=>$subsubcategory_id,
            'quantity'=>$quantity, 'current_page'=>$this->current_page
        ], $getCommonData));
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
        if($products->isEmpty()){
            return redirect()->back()->with('error', translate('There is no product in this category'));
        }
        $current_discount = Discount::find($id);
        $current_discount_group = Discount::where('discount_number', $current_discount->discount_number)->delete();
        if(isset($request->subsubcategory_id) && $request->subsubcategory_id != "all" && $request->subsubcategory_id){
            $category_id_ = $request->subsubcategory_id;
        }elseif(isset($request->subcategory_id) && $request->subcategory_id != "all" && $request->subcategory_id){
            $category_id_ = $request->subcategory_id;
        }elseif(isset($request->category_id) && $request->category_id){
            $category_id_ = $request->category_id;
        }else{
            $category_id_ = $category_id;
        }
        $discounts_data = $products->map(function($product) use($discount_number, $request, $category_id_) {
            return [
                'percent' => $request->percent,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'category_id' => $category_id_,
                'product_id' => $product->id,
                'discount_number' => $discount_number
            ];
        });
        DB::table('discounts')->insert($discounts_data->toArray());
        return redirect()->route('discount.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $current_discount = Discount::find($id);
        Discount::where('discount_number', $current_discount->discount_number)->delete();
        return redirect()->route('discount.index')->with('status', translate('Successfully created'));
    }
}
