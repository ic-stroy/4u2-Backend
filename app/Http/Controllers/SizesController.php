<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Sizes;
use Illuminate\Http\Request;

class SizesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sizes = Sizes::orderBy('created_at', 'desc')->get();
        return view('sizes.index', ['sizes'=> $sizes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::select('id', 'name')->where('step', 0)->get();
        return view('sizes.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $last_size = Sizes::withTrashed()->orderBy('id', 'desc')->first();
        $model = new Sizes();
        if($last_size){
            $model->id = (int)$last_size->id + 1;
        }
        $model = new Sizes();
        $model->name = $request->name;
        $model->category_id = $request->category_id;
        $model->save();
        return redirect()->route('size.index')->with('status', __('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Sizes::find($id);
        return view('sizes.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $size = Sizes::find($id);
        $categories = Category::select('id', 'name')->where('step', 0)->get();
        return view('sizes.edit', ['size'=> $size, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Sizes::find($id);
        $model->name = $request->name;
        $model->category_id = $request->category_id;
        $model->save();
        return redirect()->route('size.index')->with('status', __('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Sizes::find($id);
        $model->delete();
        return redirect()->route('size.index')->with('status', __('Successfully deleted'));
    }
}
