<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function error(string $message, int $error_type, array $data = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message ?? 'error occured'
        ], $error_type, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }
    public function success(string $message, int $error_type, array $data = null)
    {
        if ($data) {
            return response()->json([
                'data' => $data ?? NULL,
                'status' => true,
                'message' => $message ?? 'success'
            ], 200, [], JSON_INVALID_UTF8_SUBSTITUTE); // $error_type
        }
        return response()->json([
            'status' => true,
            'message' => $message ?? 'success'
        ], 200, [], JSON_INVALID_UTF8_SUBSTITUTE); // $error_type

    }

    public function setRandom(){
        $letters = range('a', 'z');
        $random_array = [$letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)]];
        $random = implode("", $random_array);
        return $random;
    }

    public function getCommonData(){
        $current_user = Auth::user();
        $locale = app()->getLocale();
        $languages = Language::get();
        return [
            "current_user" => $current_user,
            "locale" => $locale,
            "languages" => $languages,
        ];
    }
}
