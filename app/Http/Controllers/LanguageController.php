<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Models\Language;
use App\Models\Translation;
use App\Models\LanguageTranslation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Utils\Paginate;
class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function changeLanguage(Request $request)
    {
        if(isset($request->locale)){
            $request->session()->put('locale', $request->locale);
            $language = Language::where('code', $request->locale)->first();
        }
        //  flash(translate('Language changed to ') . $language->name)->success();
    }


    public function index()
    {

        $languages = Language::orderBy('id', 'ASC')->get();
        return view('language.index', [
            'languages' => $languages,
        ]);
    }

    public function show(Request $request, $id)
    {
        $sort_search = null;
        $language = Language::findOrFail($id);
        $lang_keys = Translation::with('getModel')->where('lang', $language->code)->get();
        if ($request->has('search')) {
            $sort_search = $request->search;
            $lang_keys = $lang_keys->where('lang_key', request()->input('search'));
        }
        return view('language.show', [
            'language' => $language,
            'lang_keys' => $lang_keys,
            'sort_search' => $sort_search,
        ]);
    }




    public function translation_save(Request $request)
    {
        $language = Language::findOrFail($request->id);
        foreach ($request->values as $key => $value) {
            $translation_def = Translation::where('lang_key', $key)->where('lang', $language->code)->first();
            if ($translation_def == null) {
                $translation_def = new Translation;
                $translation_def->lang = $language->code;
                $translation_def->lang_key = $key;
                $translation_def->lang_value = $value;
                $translation_def->save();
            } else {
                $translation_def->lang_value = $value;
                $translation_def->save();
            }
        }

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function languageEdit($id)
    {

        $language = Language::findOrFail(decrypt($id));
        return view('language.edit', [
            'language'=>$language,
        ]);

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request ,$id)
    {

        $language = Language::where('id', $id)->first();
        $language->name = $request->name;
        if ($language->save()) {

            if (LanguageTranslation::where('language_id', $language->id)->where('lang', default_language())->first()) {
                $languages = Language::get();
                foreach ($languages as $language) {
                    $language_translations = LanguageTranslation::firstOrNew(['lang' => $language->code, 'language_id' => $language->id]);
                    $language_translations->name = $request->name;
                    $language_translations->save();
                }
            }
            return redirect()->route('language.index');
        }
    }

    public function languageDestroy($id)
    {
        $language = Language::findOrFail($id);
        if (env('DEFAULT_LANGUAGE', 'ru') == $language->code) {
            return back();
        } else {
            $language->delete();
        }
        return redirect()->route('language.index');
    }





    public function updateValue(Request $request)
    {
        $tr = new GoogleTranslate;
        return GoogleTranslate::trans($request->status, $request->code);
    }

}
