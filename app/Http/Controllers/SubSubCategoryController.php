<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class SubSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $SubSubCategory = Category::where('step', 2)->orderBy('created_at', 'desc')->get();
        return view('sub-sub-category.index', ['subsubcategories'=> $SubSubCategory]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subcategories = Category::select('parent_id')->where('step', 1)->groupBy('parent_id')->distinct()->get();
        foreach ($subcategories as $subcategory){
            $category_ids[] = $subcategory->parent_id;
        }
        $categories = Category::whereIn('id', $category_ids)->get();
        return view('sub-sub-category.create', ['categories'=>$categories]);
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
        $model = Category::where('step', 2)->find($id);
        return view('sub-sub-category.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $SubSubCategory = Category::where('step', 2)->find($id);
        $subcategories = Category::select('parent_id')->where('step', 1)->groupBy('parent_id')->distinct()->get();
        foreach ($subcategories as $subcategory){
            $category_ids[] = $subcategory->parent_id;
        }
        $categories = Category::whereIn('id', $category_ids)->get();
        return view('sub-sub-category.edit', ['subsubcategory'=>$SubSubCategory, 'categories'=>$categories]);
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
        $model = Category::where('step', 2)->find($id);
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
        $category = Category::where('step', 0)->get();
        return view('sub-sub-category.category', ['categories'=>$category]);
    }

    public function subcategory($id)
    {
        $subcategories = Category::where('parent_id', $id)->orderBy('created_at', 'desc')->get();


//        $categories = Category::where('step', 0)->get();
        $all_sub_sub_categories = [];
        foreach($subcategories as $subcategory){
            $sub_sub_categories = $subcategory->subsubcategory_;
            if(!$sub_sub_categories->isEmpty()){
                $all_sub_sub_categories[$subcategory->id] = $sub_sub_categories;
            }else{
                $all_sub_sub_categories[$subcategory->id] = [];

            }
        }


        return view('sub-sub-category.subcategory', ['subcategories'=>$subcategories, 'all_sub_sub_categories'=>$all_sub_sub_categories]);
    }

    public function subsubcategory($id)
    {
        $SubSubCategory = Category::where('parent_id', $id)->orderBy('created_at', 'desc')->get();
        return view('sub-sub-category.subsubcategory', ['subsubcategories'=>$SubSubCategory]);
    }
}
