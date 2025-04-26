<?php

namespace App\Http\Controllers;

use App\Constants;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class UsersController extends Controller
{
    public $current_page = 'users';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getCommonData = $this->getCommonData();
        $users = User::orderBy('created_at', 'desc')->get();
        return view('user.index', array_merge([
            'users' => $users, 'current_page'=>$this->current_page
        ], $getCommonData));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $getCommonData = $this->getCommonData();
        return view('user.create', array_merge($getCommonData, ['current_page'=>$this->current_page]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new User();
        $model->first_name = $request->first_name;
        $model->last_name = $request->last_name;
        $model->middle_name = $request->middle_name;
        $model->phone_number = $request->phone_number;
        $letters = range('a', 'z');
        $random_array = [$letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)]];
        $random = implode("", $random_array);
        $file = $request->file('avatar');
        if($request->new_password && $request->new_password != $request->password_confirmation){
            return redirect()->back()->with('error', translate('Your new password confirmation is incorrect'));
        }
        if (isset($file) && $file) {
            $image_name = $random . '' . date('Y-m-dh-i-s') . '.' . $file->extension();
            $file->storeAs('public/user/', $image_name);
            $model->avatar = $image_name;
        }

        $model->gender = $request->gender;
        $model->birth_date = $request->birth_date;
        $model->email =  $request->email;
        $model->password = Hash::make($request->password);
        if (isset($request->is_admin) && $request->is_admin =! 0) {
            $model->is_admin = (int)$request->is_admin;
        }
        $model->save();

        return redirect()->route('user.index')->with('status', translate('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getCommonData = $this->getCommonData();
        $model = User::find($id);
        $year_old = 0;
        if($model->birth_date){
            $now_time = strtotime('now');
            $birth_time = strtotime($model->birth_date);
            $month = date('m', ($now_time));
            $day = date('d', ($now_time));
            $birth_month = date('m', ($birth_time));
            $birth_date = date('d', ($birth_time));
            $year = date('Y', ($now_time));
            $birth_year = date('Y', ($birth_time));
            $year_old = 0;
            if($year > $birth_year){
                $year_old = $year - $birth_year - 1;
                if($month > $birth_month){
                    $year_old = $year_old +1;
                }elseif($month == $birth_month){
                    if($day >= $birth_date){
                        $year_old = $year_old +1;
                    }
                }
            }
        }
        return view('user.show', array_merge([
            'model' => $model,
            'year_old' => $year_old, 'current_page'=>$this->current_page
        ], $getCommonData));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $getCommonData = $this->getCommonData();
        $user = User::find($id);
        return view('user.edit', array_merge([
            'user' => $user, 'current_page'=>$this->current_page
        ], $getCommonData));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = User::find($id);
        if ($request->password && $request->new_password && $request->password_confirmation) {
            if (Hash::check($request->password, $model->password) && $request->new_password == $request->password_confirmation) {
                $model->password = Hash::make($request->new_password);
            }else{
                if(!Hash::check($request->password, $model->password)){
                    return redirect()->back()->with('error', translate('Your password is incorrect'));
                }elseif($request->new_password != $request->password_confirmation){
                    return redirect()->back()->with('error', translate('Your new password confirmation is incorrect'));
                }
            }
        }elseif($request->password && $request->new_password && !$request->password_confirmation){
            return redirect()->back()->with('error', translate('Your new password confirmation is incorrect'));
        }
        $model->first_name = $request->first_name;
        $model->last_name = $request->last_name;
        $model->middle_name = $request->middle_name;
        $model->phone_number = $request->phone_number;
        $letters = range('a', 'z');
        $random_array = [$letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)]];
        $random = implode("", $random_array);
        $file = $request->file('avatar');
        if (isset($file) && $file) {
            if($model->avatar){
                $sms_avatar = storage_path('app/public/user/' . $model->avatar);
            }else{
                $sms_avatar = storage_path('app/public/user/' . 'no');
            }
            if (file_exists($sms_avatar)) {
                unlink($sms_avatar);
            }
            $image_name = $random.''.date('Y-m-dh-i-s').'.'.$file->extension();
            $file->storeAs('public/user/', $image_name);
            $model->avatar = $image_name;
        }
        $model->gender = $request->gender;
        $model->birth_date = $request->birth_date;

        $model->email = $request->email;


        if ($request->is_admin && $request->is_admin =! 0) {
            $model->is_admin = (int)$request->is_admin;
        }
        $model->save();

        return redirect()->route('user.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = User::find($id);
        if ($model->avatar) {
            $sms_avatar = storage_path('app/public/user/'.$model->avatar);
        } else {
            $sms_avatar = 'no';
        }

        if (file_exists($sms_avatar)) {
            unlink($sms_avatar);
        }

        $model->delete();
        return redirect()->route('user.index')->with('status', translate('Successfully deleted'));
    }

    /**
     * Api json
     */

    public function setPersonalInformation(Request $request){

        $user = Auth::user();
        if($request->first_name){
            $user->first_name = $request->first_name;
        }
        if($request->last_name){
            $user->last_name = $request->last_name;
        }
        if($request->phone_number){
            $user->phone_number = $request->phone_number;
        }
        if($request->gender && $request->gender != 'null'){
            $user->gender = $request->gender;
        }
        if($request->old_password || $request->password || $request->password_confirmation){
            if($request->password && $request->password_confirmation){
                if($request->password != $request->password_confirmation){
                    return $this->success('password confirmation is not the same', 400);
                }
            }elseif($request->password || $request->password_confirmation){
                return $this->success('password confirmation is not the same', 400);
            }
            if(!Hash::check($request->old_password, $user->password)){
                return $this->success('It is not your password', 400);
            }
            $user->password = $request->password;
        }
        $file = $request->file("avatar");
        if($file){
            $this->imageSave($file, $user, 'update');
        }
        if($request->old_birth_date && $request->old_birth_date != "null"){
            $user->birth_date = $request->old_birth_date;
        }elseif($request->birth_date && $request->birth_date != "null"){
            $user->birth_date = $request->birth_date;
        }
        $user->save();
        return $this->success('Success', 200, []);
    }

    public function getPersonalInformation(Request $request){
        $user = Auth::user();
        $user_image = null;
        if($user->avatar){
            $sms_avatar = storage_path('app/public/user/' . $user->avatar);
        }else{
            $sms_avatar = storage_path('app/public/user/' . 'no');
        }
        if (file_exists($sms_avatar)) {
            $user_image = asset('storage/user/'.$user->avatar);
        }
        switch ($user->gender){
            case 1:
                $gender = Constants::MALE;
                break;
            case 2:
                $gender = Constants::FEMALE;
                break;
            default:
                $gender = null;
        }
        if($user->birth_date){
            $birth_date = date("Y-m-d", strtotime($user->birth_date));
        }else{
            $birth_date = null;
        }
        $data = [
            "id"=>$user->id,
            "first_name" => $user->first_name??null,
            "last_name" => $user->last_name??null,
            "middle_name" => $user->middle_name??null,
            "phone_number" => $user->phone_number??null,
            "gender" => $gender,
            "email" => $user->email??null,
            "avatar"=>$user_image,
            "birth_date"=>$birth_date,
            "token"=>$user->token
        ];
        return $this->success('Success', 200, $data);
    }

    public function imageSave($file, $user, $text){
        $letters = range('a', 'z');
        $random_array = [$letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)], $letters[rand(0,25)]];
        $random = implode("", $random_array);
        if($text == 'update'){
            if($user->avatar){
                $sms_avatar = storage_path('app/public/user/' . $user->avatar);
            }else{
                $sms_avatar = storage_path('app/public/user/' . 'no');
            }
            if (file_exists($sms_avatar)) {
                unlink($sms_avatar);
            }
        }
        $image_name = $random.''.date('Y-m-dh-i-s').'.'.$file->extension();
        $file->storeAs('public/user/', $image_name);
        $user->avatar = $image_name;
        return $user;
    }

    public function deleteUserImage(){
        $user = Auth::user();
        if($user->avatar){
            $sms_avatar = storage_path('app/public/user/' . $user->avatar);
        }else{
            $sms_avatar = storage_path('app/public/user/' . 'no');
        }
        if (file_exists($sms_avatar)) {
            unlink($sms_avatar);
        }
        return $this->success('Success', 200, []);
    }

    public function getUser(){
        $getCommonData = $this->getCommonData();
        $model = Auth::user();
        $year_old = 0;
        if($model->birth_date){
            $birth_date_array = explode(' ', $model->birth_date);
            $now_time = strtotime('now');
            $birth_time = strtotime($birth_date_array[0]);
            $month = date('m', ($now_time));
            $day = date('d', ($now_time));
            $birth_month = date('m', ($birth_time));
            $birth_date = date('d', ($birth_time));
            $year = date('Y', ($now_time));
            $birth_year = date('Y', ($birth_time));
            $year_old = 0;
            if($year > $birth_year){
                $year_old = $year - $birth_year - 1;
                if($month > $birth_month){
                    $year_old = $year_old +1;
                }elseif($month == $birth_month){
                    if($day >= $birth_date){
                        $year_old = $year_old +1;
                    }
                }
            }
        }
        return view('self-user.show', array_merge([
            'model' => $model,
            'year_old' => $year_old, 'current_page'=>$this->current_page
        ], $getCommonData));
    }

    public function editUser(){
        $getCommonData = $this->getCommonData();
        $user = Auth::user();
        return view('self-user.edit', array_merge([
            'user' => $user, 'current_page'=>$this->current_page
        ], $getCommonData));
    }

}
