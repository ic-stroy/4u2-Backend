<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\ColorTranslations;
use App\Models\Language;
use Illuminate\Http\Request;


class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        foreach (Language::all() as $language) {
            $color_translations = ColorTranslations::firstOrNew(['lang' => $language->code, 'color_id' => $model->id]);
            $color_translations->lang = $language->code;
            $color_translations->name = $model->name;
            $color_translations->color_id = $model->id;
            $color_translations->save();
        }
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
        if($request->name != $model->name){
            foreach (Language::all() as $language) {
                $color_translations = ColorTranslations::firstOrNew(['lang' => $language->code, 'color_id' => $model->id]);
                $color_translations->lang = $language->code;
                $color_translations->name = $request->name;
                $color_translations->color_id = $model->id;
                $color_translations->save();
            }
        }
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
        foreach (Language::all() as $language) {
            $color_translations = ColorTranslations::where(['lang' => $language->code, 'color_id' => $model->id])->get();
            foreach ($color_translations as $color_translation){
                $color_translation->delete();
            }
        }
        $model->delete();
        return redirect()->route('color.index')->with('status', translate('Successfully deleted'));
    }
}
