<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Models\Language;
use App\Models\Translation;
use App\Models\LanguageTranslation;
class LanguageController extends Controller
{
    public $current_page = 'language';
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
        $getCommonData = $this->getCommonData();
        $languages = Language::orderBy('id', 'ASC')->get();
        return view('language.index', array_merge([
            'languages' => $languages, 'current_page'=>$this->current_page
        ], $getCommonData));
    }

    public function show(Request $request, $id)
    {
        $getCommonData = $this->getCommonData();
        $sort_search = null;
        $language = Language::findOrFail($id);
        $lang_keys = Translation::with('getModel')->where('lang', $language->code)->get();
        if ($request->has('search')) {
            $sort_search = $request->search;
            $lang_keys = $lang_keys->where('lang_key', request()->input('search'));
        }
        return view('language.show', array_merge([
            'language' => $language,
            'lang_keys' => $lang_keys,
            'sort_search' => $sort_search, 'current_page'=>$this->current_page
        ], $getCommonData));
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
        $getCommonData = $this->getCommonData();
        $language = Language::findOrFail(decrypt($id));
        return view('language.edit', array_merge([
            'language'=>$language, 'current_page'=>$this->current_page
        ], $getCommonData));

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
