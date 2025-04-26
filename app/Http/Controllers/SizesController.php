<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Sizes;
use Illuminate\Http\Request;

class SizesController extends Controller
{
    public $current_page = 'size';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        translate('You cannot delete this category because here is product associated with this size.');
//        translate('You cannot delete this category because it has subsubcategories');
//        translate('You cannot delete this category because here is product associated with this size.');
//        translate('You cannot delete this size because here is product associated with this size.');
//        translate('You cannot delete this product because here is some products in warehouse');
//        translate('You cannot delete this color because here is product associated with this color.');
//        translate('You cannot delete this product because here is product associated with an order.');
//        translate('You cannot delete this category because it has subcategories');
//        translate('You cannot delete this category because it has products');
//        translate('You cannot delete this address because here is order associated with this address.');
//        translate('You cannot delete this card because here is order associated with this card.');
        $getCommonData = $this->getCommonData();
        $sizes = Sizes::orderBy('created_at', 'desc')->get();
        return view('sizes.index', array_merge(['sizes'=> $sizes, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $getCommonData = $this->getCommonData();
        $categories = Category::select('id', 'name')->where('step', 0)->get();
        return view('sizes.create', array_merge(['categories' => $categories, 'current_page'=>$this->current_page], $getCommonData));
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
        return redirect()->route('size.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getCommonData = $this->getCommonData();
        $model = Sizes::find($id);
        return view('sizes.show', array_merge(['model'=>$model, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $getCommonData = $this->getCommonData();
        $size = Sizes::find($id);
        $categories = Category::select('id', 'name')->where('step', 0)->get();
        return view('sizes.edit', array_merge(['size'=> $size, 'categories' => $categories, 'current_page'=>$this->current_page], $getCommonData));
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
        return redirect()->route('size.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Sizes::with('CharacterizedProducts')->find($id);
        if($model->CharacterizedProducts){
            return redirect()->back()->with('error', translate('You cannot delete this size because here is product associated with this size.'));
        }
        $model->delete();
        return redirect()->route('size.index')->with('status', translate('Successfully deleted'));
    }
}
