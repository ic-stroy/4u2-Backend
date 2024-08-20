<?php

namespace App\Http\Controllers;

use App\Models\CategoryTranslations;
use App\Models\CompanyTranslations;
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
        // dd($request->all());
        $type=$request->type;
        $id=$request->language_id;
        $language = Language::findOrFail($id);
       // $lang_keys = Translation::where('lang', env('DEFAULT_LANGUAGE', 'en'))->get();
        $sort_search = null;
        switch ($type){
            case 'city':
                $lang_keys = CityTranslations::where('lang', env('DEFAULT_LANGUAGE', 'uz'))->get();
                if ($request->has('search')) {
                    $sort_search = $request->search;
                    // dd($sort_search);
                    // $lang_keys = $lang_keys->where('lang_key', 'like', '%' . $sort_search . '%');
                    $lang_keys = $lang_keys->where('lang_key', request()->input('search'));
                    // dd(request()->input('search'));
                }
                return view('language.table_show', ['lang_keys'=>$lang_keys, 'language'=>$language , 'sort_search' => $sort_search, 'type'=>$type]);
                break;
            case 'category':
                $lang_keys = CategoryTranslations::where('lang', env('DEFAULT_LANGUAGE', 'uz'))->get();
                if ($request->has('search')) {
                    $sort_search = $request->search;
                    // dd($sort_search);
                    // $lang_keys = $lang_keys->where('lang_key', 'like', '%' . $sort_search . '%');
                    $lang_keys = $lang_keys->where('lang_key', request()->input('search'));
                    // dd(request()->input('search'));
                }
                return view('language.table_show', ['lang_keys'=>$lang_keys, 'language'=>$language , 'sort_search' => $sort_search, 'type'=>$type]);
                break;
            case 'color':
                $lang_keys = ColorTranslations::where('lang', env('DEFAULT_LANGUAGE', 'uz'))->get();
                if ($request->has('search')) {
                    $sort_search = $request->search;
                    // dd($sort_search);
                    // $lang_keys = $lang_keys->where('lang_key', 'like', '%' . $sort_search . '%');
                    $lang_keys = $lang_keys->where('lang_key', request()->input('search'));
                    // dd(request()->input('search'));
                }
                return view('language.table_show', ['lang_keys'=>$lang_keys, 'language'=>$language , 'sort_search' => $sort_search, 'type'=>$type]);
                break;
            case 'product':
                $lang_keys = ProductTranslations::where('lang', env('DEFAULT_LANGUAGE', 'uz'))->get();
                if ($request->has('search')) {
                    $sort_search = $request->search;
                    // dd($sort_search);
                    // $lang_keys = $lang_keys->where('lang_key', 'like', '%' . $sort_search . '%');
                    $lang_keys = $lang_keys->where('lang_key', request()->input('search'));
                    // dd(request()->input('search'));
                }
                return view('language.table_show', ['lang_keys'=>$lang_keys, 'language'=>$language , 'sort_search' => $sort_search, 'type'=>$type]);
                break;
            default:
        }
    }


    public function translation_save(Request $request)
    {
        // dd($request->all());
        switch ($request->type){
            case 'city':
                $language = Language::findOrFail($request->id);
                foreach ($request->values as $key => $value) {
                    // dd($value);
                    $translation_def = CityTranslations::where('city_id', $key)->where('lang', $language->code)->first();
                    if ($translation_def) {
                        $translation_def->name = $value;
                        $translation_def->save();
                    }
                }

                return back();
                break;
            case 'category':
                $language = Language::findOrFail($request->id);
                foreach ($request->values as $key => $value) {
                    // dd($value);
                    $translation_def = CategoryTranslations::where('category_id', $key)->where('lang', $language->code)->first();
                    if ($translation_def) {
                        $translation_def->name = $value;
                        $translation_def->save();
                    }
                }

                return back();
                break;
            case 'color':
                $language = Language::findOrFail($request->id);
                foreach ($request->values as $key => $value) {
                    // dd($key);
                    $translation_def = ColorTranslations::where('color_id', $key)->where('lang', $language->code)->first();
                    if ($translation_def) {
                        $translation_def->name = $value;
                        $translation_def->save();
                    }
                }

                return back();
                break;
            case 'product':
                $language = Language::findOrFail($request->id);
                    foreach ($request->values as $key => $value) {
                        // dd($value);
                        $translation_def = ProductTranslations::where('product_id', $key)->where('lang', $language->code)->first();
                        if ($translation_def) {
                            $translation_def->name = $value;
                            $translation_def->save();
                        }
                    }

                    return back();
                    break;
            default:
        }

    }
}
