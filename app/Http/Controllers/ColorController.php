<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;


class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        translate('Your password is incorrect');
        translate('Your new password confirmation is incorrect');
        translate('You cannot delete this category because it has subsubcategories');
        translate('You cannot delete this category because here is product associated with this size.');
        translate('You cannot delete this size because here is product associated with this size.');
        translate('You cannot delete this product because here is some products in warehouse');
        translate('You cannot delete this color because here is product associated with this color.');
        translate('You cannot delete this category because it has subcategories');
        translate('You cannot delete this category because it has products');
        translate('You cannot delete this product because here is product associated with an order.');
        $color = Color::orderBy('created_at', 'desc')->get();
        return view('color.index', ['colors'=> $color]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('color.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required',
            'code'=>'required'
        ], [
            'name.required'=>translate('Color name required'),
            'code.required'=>translate('Please select a color'),
        ]);
        $model = new Color();
        $model->name = $request->name;
        $model->code = $request->code;
        $model->save();
        return redirect()->route('color.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Color::find($id);
        return view('color.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $color = Color::find($id);
        return view('color.edit', ['color'=> $color]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name'=>'required',
            'code'=>'required'
        ], [
            'name.required'=>translate('Color name required'),
            'code.required'=>translate('Please select a color'),
        ]);
        $model = Color::find($id);
        $model->name = $request->name;
        $model->code = $request->code;
        $model->save();
        return redirect()->route('color.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Color::find($id);
        if($model->CharacterizedProducts){
            return redirect()->back()->with('error', translate('You cannot delete this color because here is product associated with this color.'));
        }
        $model->delete();
        return redirect()->route('color.index')->with('status', translate('Successfully deleted'));
    }
}
