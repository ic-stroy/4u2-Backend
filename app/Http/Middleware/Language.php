<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App;
use Session;
use Config;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(session()->has('locale')){
            $locale = session('locale');
        }else{
            $locale = env('DEFAULT_LANGUAGE','ru');
        }
        \Illuminate\Support\Facades\App::setLocale($locale);
        $request->session()->put('locale', $locale);
        return $next($request);
    }
}
