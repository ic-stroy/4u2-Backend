<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class SubSubCategoryController extends Controller
{
    public $current_page = 'sub-sub-category';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getCommonData = $this->getCommonData();
        $SubSubCategory = Category::where('step', 2)->orderBy('created_at', 'desc')->get();
        return view('sub-sub-category.index', array_merge(['subsubcategories'=> $SubSubCategory, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $getCommonData = $this->getCommonData();
        $category_ids = Category::select('parent_id')->where('step', 1)->groupBy('parent_id')->distinct()->get()->map(function($subcategory){
            return $subcategory->parent_id;
        });
        $categories = Category::whereIn('id', $category_ids)->get();
        return view('sub-sub-category.create', array_merge(['categories'=>$categories, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $last_category = Category::withTrashed()->orderBy('id', 'desc')->first();
        $model = new Category();
        if($last_category){
            $model->id = (int)$last_category->id + 1;
        }
        $model->name = $request->name;
        $model->parent_id = $request->subcategory_id;
        $model->step = 2;
        $model->save();

        return redirect()->route('subsubcategory.subcategory', $request->category_id)->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getCommonData = $this->getCommonData();
        $model = Category::where('step', 2)->find($id);
        return view('sub-sub-category.show', array_merge(['model'=>$model, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $getCommonData = $this->getCommonData();
        $SubSubCategory = Category::where('step', 2)->find($id);
        $category_ids = Category::select('parent_id')->where('step', 1)->groupBy('parent_id')->distinct()->get()->map(function($subcategory){
            return $subcategory->parent_id;
        });
        $categories = Category::whereIn('id', $category_ids)->get();
        return view('sub-sub-category.edit', array_merge(['subsubcategory'=>$SubSubCategory, 'categories'=>$categories, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Category::where('step', 2)->find($id);
        $model->name = $request->name;
        $model->parent_id = $request->subcategory_id;
        $model->step = 2;
        $model->save();
        return redirect()->route('subsubcategory.subcategory', $request->category_id)->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Category::with([
            'sub_category',
            'sub_category.category',
            'product'
        ])->where('step', 2)->find($id);
        if($model->product) {
            return redirect()->back()->with('error', translate('You cannot delete this category because here is product associated with this size.'));
        }
        if($model->sub_category){
            if($model->sub_category->category){
                $category_id = $model->sub_category->category->id;
            }else{
                $category_id = $model->sub_category->id;
            }
        }else{
            $category_id = $model->id;
        }
        $model->delete();
        return redirect()->route('subsubcategory.subcategory', $category_id)->with('status', translate('Successfully deleted'));
    }

    public function category()
    {
        $getCommonData = $this->getCommonData();
        $category = Category::where('step', 0)->get();
        return view('sub-sub-category.category', array_merge(['categories'=>$category, 'current_page'=>$this->current_page], $getCommonData));
    }

    public function subcategory($id)
    {
        $getCommonData = $this->getCommonData();
        $subcategories = Category::with('subsubcategory_')->where('parent_id', $id)->orderBy('created_at', 'desc')->get();
        $all_sub_sub_categories = [];
        foreach($subcategories as $subcategory){
            $sub_sub_categories = $subcategory->subsubcategory_;
            if(!$sub_sub_categories->isEmpty()){
                $all_sub_sub_categories[$subcategory->id] = $sub_sub_categories;
            }else{
                $all_sub_sub_categories[$subcategory->id] = [];
            }
        }
        return view('sub-sub-category.subcategory', array_merge(['subcategories'=>$subcategories, 'all_sub_sub_categories'=>$all_sub_sub_categories, 'current_page'=>$this->current_page], $getCommonData));
    }

    public function subsubcategory($id)
    {
        $getCommonData = $this->getCommonData();
        $SubSubCategory = Category::where('parent_id', $id)->orderBy('created_at', 'desc')->get();
        return view('sub-sub-category.subsubcategory', array_merge(['subsubcategories'=>$SubSubCategory, 'current_page'=>$this->current_page], $getCommonData));
    }
}
