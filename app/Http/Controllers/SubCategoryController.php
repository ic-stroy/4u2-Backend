<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public $types_sex = [
        'No sex type', 'Unisex', 'Men', 'Women', 'Boys and girls', 'Boys', 'Girls'
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $SubCategory = Category::where('step', 1)->orderBy('created_at', 'desc')->get();
        return view('sub-category.index', ['subcategories'=> $SubCategory]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('step', 0)->get();
        return view('sub-category.create', ['categories'=>$categories, 'types_sex'=>$this->types_sex]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Category();
        $model->name = $request->name;
        $model->parent_id = $request->category_id;
        $model->step = 1;
        $model->save();
        return redirect()->route('subcategory.subcategory', $request->category_id)->with('status', translate('Successfully created'));
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
        return view('sub-category.edit', ['subcategory'=>$SubCategory, 'types_sex'=>$this->types_sex, 'categories'=>$categories]);
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
        return redirect()->route('subcategory.subcategory', $request->category_id)->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Category::where('step', 1)->find($id);
        $model->delete();
        return redirect()->route('subcategory.subcategory', $id)->with('status', translate('Successfully deleted'));
    }

    /**
     * json responses
     */

    public function getSubcategory($id)
    {
        $model = Category::where('parent_id', $id)->get();
        if(isset($model) && count($model)>0){
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
