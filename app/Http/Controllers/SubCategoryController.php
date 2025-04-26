<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public $current_page = 'sub-category';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getCommonData = $this->getCommonData();
        $categories = Category::with('subcategory')->where('step', 0)->get();
        foreach($categories as $category){
            $sub_categories = $category->subcategory;
            if(!$sub_categories->isEmpty()){
                $all_categories[$category->id] = $sub_categories;
            }else{
                $all_categories[$category->id] = [];
            }
        };
        return view('sub-category.index', array_merge(['all_categories'=> $all_categories, 'categories'=>$categories, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $getCommonData = $this->getCommonData();
        $categories = Category::where('step', 0)->get();
        return view('sub-category.create', array_merge(['categories'=>$categories, 'current_page'=>$this->current_page], $getCommonData));
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
        $getCommonData = $this->getCommonData();
        $model = Category::where('step', 1)->find($id);
        return view('sub-category.show', array_merge(['model'=>$model, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $getCommonData = $this->getCommonData();
        $SubCategory = Category::where('step', 1)->find($id);
        $categories = Category::where('step', 0)->get();
        return view('sub-category.edit', array_merge(['subcategory'=>$SubCategory, 'categories'=>$categories, 'current_page'=>$this->current_page], $getCommonData));
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
        $getCommonData = $this->getCommonData();
        $category = Category::where('step', 0)->get();
        return view('sub-category.category', array_merge(['categories'=>$category, 'current_page'=>$this->current_page], $getCommonData));
    }

    public function subcategory($id)
    {
        $getCommonData = $this->getCommonData();
        $SubCategory = Category::where('parent_id', $id)->orderBy('created_at', 'desc')->get();
        return view('sub-category.subcategory', array_merge(['subcategories'=>$SubCategory, 'current_page'=>$this->current_page], $getCommonData));
    }

}
