<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('subcategory')->where('step', 0)->get();
        foreach($categories as $category){
            $sub_categories = $category->subcategory;
            if(!$sub_categories->isEmpty()){
                $all_categories[$category->id] = $sub_categories;
            }else{
                $all_categories[$category->id] = [];
            }
        };
        return view('sub-category.index', ['all_categories'=> $all_categories, 'categories'=>$categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('step', 0)->get();
        return view('sub-category.create', ['categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Category();
        $last_category = Category::withTrashed()->select('id')->orderBy('id', 'desc')->first();
        if($last_category){
            $model->id = (int)$last_category->id + 1;
        }
        $model->name = $request->name;
        $model->parent_id = $request->category_id;
        $model->step = 1;
        $model->save();
        return redirect()->route('subcategory.index', $request->category_id)->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Category::where('step', 1)->find($id);
        return view('sub-category.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $SubCategory = Category::where('step', 1)->find($id);
        $categories = Category::where('step', 0)->get();
        return view('sub-category.edit', ['subcategory'=>$SubCategory, 'categories'=>$categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Category::where('step', 1)->find($id);
        $model->name = $request->name;
        $model->parent_id = $request->category_id;
        $model->step = 1;
        $model->save();
        return redirect()->route('subcategory.index', $request->category_id)->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Category::with(['subsubcategory', 'product'])->where('step', 1)->find($id);
        if($model){
            if(!$model->subsubcategory->isEmpty()){
                return redirect()->back()->with('error', translate('You cannot delete this category because it has subsubcategories'));
            }
        }
        if($model->product) {
            return redirect()->back()->with('error', translate('You cannot delete this category because here is product associated with this size.'));
        }
        $model->delete();
        return redirect()->route('subcategory.index', $id)->with('status', translate('Successfully deleted'));
    }

    /**
     * json responses
     */

    public function getSubcategory($id)
    {
        $model = Category::with('getTranslatedContent')->where('parent_id', $id)->get()->map(function($query){
            return [
                'id' => $query->id,
                'name' => $query->name,
                'parent_id' => $query->parent_id,
                'step' => $query->step,
            ];
        });

        if(!$model->isEmpty()){
            return response()->json([
                'status'=>true,
                'data'=>$model
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'data'=>[]
            ]);
        }

    }
    public function category()
    {
        $category = Category::where('step', 0)->get();
        return view('sub-category.category', ['categories'=>$category]);
    }

    public function subcategory($id)
    {
        $SubCategory = Category::where('parent_id', $id)->orderBy('created_at', 'desc')->get();
        return view('sub-category.subcategory', ['subcategories'=>$SubCategory]);
    }

}
