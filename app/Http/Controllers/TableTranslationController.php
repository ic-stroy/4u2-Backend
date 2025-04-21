<?php

namespace App\Http\Controllers;

use App\Models\CategoryTranslations;
use App\Models\ProductDescriptionTranslations;
use App\Models\ProductTranslations;
use App\Models\SizeTranslations;
use App\Models\RoleTranslations;
use App\Models\ColorTranslations;
use App\Models\WarehouseTranslations;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Translation;
use App\Models\CityTranslations;
use Illuminate\Support\Facades\DB;


class TableTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('language.tables');
    }

    public function show($type){
        $languages = Language::orderBy('id', 'ASC')->get();
        return view('language.table_lang', ['type'=>$type, 'languages'=>$languages]);
    }

    public function tableShow(Request $request ){
        $type = $request->type;
        $id = $request->language_id;
        $search = $request->search;
        $language = Language::findOrFail($id);
        $sort_search = null;
        $typeClassMap = [
            'city' => CityTranslations::class,
            'category' => CategoryTranslations::class,
            'color' => ColorTranslations::class,
            'product' => ProductTranslations::class,
        ];
        
        $lang_keys = match ($type) {
            'city', 'category', 'color', 'product' => $typeClassMap[$type]::with('getModel')->where('lang', $language->code)->get(),
            default => collect(),
        };
        if ($search) {
            $lang_keys = $lang_keys->where('key', $search);
        }
        return view('language.table_show', ['lang_keys'=>$lang_keys, 'language'=>$language , 'sort_search' => $sort_search, 'type'=>$type]);
    }


    public function translation_save(Request $request)
    {
        $type = $request->type;
        if($type){
            $language = Language::findOrFail($request->id);
            $typeMap = [
                'city' => ['model' => CityTranslations::class, 'column' => 'city_id'],
                'category' => ['model' => CategoryTranslations::class, 'column' => 'category_id'],
                'color' => ['model' => ColorTranslations::class, 'column' => 'color_id'],
                'product' => ['model' => ProductTranslations::class, 'column' => 'product_id'],
            ];
            if (isset($typeMap[$type])) {
                $model = $typeMap[$type]['model'];
                $column = $typeMap[$type]['column'];
                $translates_id = $request->values;
                foreach ($translates_id as $key => $value) {
                    $translation = $model::where($column, $key)->where('lang', $language->code)->first();
                    if ($translation) {
                        $translation->name = $value;
                        $translation->save();
                    }
                }
            }
        }
        return redirect()->back();

    }
}
