<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Events\PostNotification;
use App\Models\Cities;
use App\Models\Language;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
//    public function changeLanguage(Request $request)
//    {
//        $request->session()->put('locale', $request->locale);
//        $language = Language::where('code', $request->locale)->first();
//        //  flash(translate('Language changed to ') . $language->name)->success();
//    }
    public function index(){
        $ordered_orders = Order::where('status', Constants::ORDERED)->count();
        $performed_orders = Order::where('status', Constants::PERFORMED)->count();
        $cancelled_orders = Order::where('status', Constants::CANCELLED)->count();
        $accepted_orders = Order::where('status', Constants::ACCEPTED_BY_RECIPIENT)->count();
        return view('index', [
            'ordered_orders'=>$ordered_orders,
            'performed_orders'=>$performed_orders,
            'cancelled_orders'=>$cancelled_orders,
            'accepted_orders'=>$accepted_orders
        ]);
    }
    public function welcome(){

        return view('welcome');
    }

    public function setCities(){
        if(!Cities::withTrashed()->exists()){
            $response = Http::get(asset("assets/json/cities.json"));
            $cities = json_decode($response);
            foreach ($cities as $city){
                if(!Cities::where('name', $city->region)->exists()){
                    $model_region = new Cities();
                    $model_region->name = $city->region;
                    $model_region->type = 'region';
                    $model_region->parent_id = 0;
                    $model_region->lng = $city->long;
                    $model_region->lat = $city->lat;
                    $model_region->save();
                    foreach ($city->cities as $city_district){
                        $model = new Cities();
                        $model->name = $city_district->name;
                        $model->type = 'district';
                        $model->parent_id = $model_region->id;
                        $model->lng = $city_district->long;
                        $model->lat = $city_district->lat;
                        $model->save();
                    }
                }else{
                    $model_region = Cities::where('name', $city->region)->first();
                    $model_region->lng = $city->long;
                    $model_region->lat = $city->lat;
                    $model_region->save();
                }
            }
        }

    }
//    public function test(){
//        event(new PostNotification("xurshid kurra"));
//        return redirect()->back();
//    }
}
