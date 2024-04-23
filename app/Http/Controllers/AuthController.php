<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\EskizToken;
use App\Models\User;
use App\Models\UserVerify;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function PhoneRegister(Request $request){
        date_default_timezone_set("Asia/Tashkent");
        $fields = $request->validate([
            'phone'=>'required|string'
        ]);
        $client = new Client();
        $eskiz_token = EskizToken::first();
        $user_verify = UserVerify::withTrashed()->where('phone_number', (int)$fields['phone'])->first();
        $random = rand(100000, 999999);
        if(!isset($user_verify->id)){
            $user_verify = new UserVerify();
            $user_verify->phone_number = (int)$request->phone;
            $user_verify->status_id = 1;
        }elseif(isset($user_verify->deleted_at)){
            $user_verify->status_id = 1;
            $user_verify->deleted_at = NULL;
        }
        $token_options = [
            'multipart' => [
                [
                    'name' => 'email',
                    'contents' => 'easysolutiongroupuz@gmail.com'
                ],
                [
                    'name' => 'password',
                    'contents' => '4TYvyjOof4CmOUk5CisHHUzzQ5Mcn1mirx0VBuQV'
                ]
            ]
        ];
        if(!isset($eskiz_token->expire_date)){
            $guzzle_request = new GuzzleRequest('POST', 'https://notify.eskiz.uz/api/auth/login');
            $res = $client->sendAsync($guzzle_request, $token_options)->wait();
            $res_array = json_decode($res->getBody());
            $eskizToken = new EskizToken();
            $eskizToken->token = $res_array->data->token;
            $eskizToken->expire_date = strtotime('+28 days');
            $eskizToken->save();
        }elseif(strtotime('now') > (int)$eskiz_token->expire_date){
            $guzzle_request = new GuzzleRequest('POST', 'https://notify.eskiz.uz/api/auth/login');
            $res = $client->sendAsync($guzzle_request, $token_options)->wait();
            $res_array = json_decode($res->getBody());
            $eskizToken = EskizToken::first();
            $eskizToken->token = $res_array->data->token;
            $eskizToken->expire_date = strtotime('+28 days');
            $eskizToken->save();
        }
        $eskiz_token = '';
        $eskiz_token = EskizToken::first();
        $options = [
            'headers' => [
                'Accept'        => 'application/json',
                'Authorization' => "Bearer $eskiz_token->token",
            ],
            'multipart' => [
                [
                    'name' => 'mobile_phone',
                    'contents' => $request->phone
                ],
                [
                    'name' => 'message',
                    'contents' => translate('4u2 - Sizni bir martalik tasdiqlash kodingiz').': '.$random
                ],
                [
                    'name' => 'from',
                    'contents' => '4546'
                ],
            ]
        ];
        $guzzle_request = new GuzzleRequest('POST', 'https://notify.eskiz.uz/api/message/sms/send');
        $res = $client->sendAsync($guzzle_request, $options)->wait();
        $result = $res->getBody();
        $result = json_decode($result);
        if(isset($result)){
            $user_verify->verify_code = $random;
            $user_verify->save();
            return response()->json([
                'status'=>true,
                'message'=>'Success',
                'Verify_code'=>$random
            ], 200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>'Fail message not sent. Try again',
            ], 400);
        }
    }

    public function PhoneVerify(Request $request){
        date_default_timezone_set("Asia/Tashkent");
        $fields = $request->validate([
            'phone_number'=>'required',
            'verify_code'=>'required',
        ]);
        $model = UserVerify::withTrashed()->where('phone_number', (int)$fields['phone_number'])->first();
        if(isset($model->id)){
            if(strtotime('-7 minutes') > strtotime($model->updated_at)){
                $model->verify_code = rand(100000, 999999);
                $model->save();
                return response()->json([
                    'status'=>false,
                    'message'=>'Your sms code expired. Resend sms code',
                ], 400);
            }
            if(isset($model->deleted_at)){
                $model->deleted_at = NULL;
            }
            if($model->verify_code == $fields['verify_code']){
                $is_registred = false;
                $user = User::withTrashed()->find($model->user_id);
                if(!isset($user->id)){
                    $new_user = new User();
                    $new_user->phone_number = (int)$fields['phone_number'];
                    $new_user->save();
                    $model->user_id = $new_user->id;
                    $model->save();
                    $new_user->email = $model->phone_number;
                    $new_user->password = Hash::make($model->verify_code);
                    $token = $new_user->createToken('myapptoken')->plainTextToken;
                    $new_user->token = $token;
                    $new_user->save();
                    $message = 'Success';
                    return response()->json([
                        'status'=>true,
                        'message'=>$message,
                        'data'=>['token'=>$token, 'is_registred'=>$is_registred]
                    ], 201);
                }else{
                    $is_registred = true;
                    if(isset($user->deleted_at)){
                        $user->deleted_at = NULL;
                    }
                    $user->email = $model->phone_number;
                    $user->phone_number = (int)$fields['phone_number'];
                    $user->password = Hash::make($model->verify_code);
                    $token = $user->createToken('myapptoken')->plainTextToken;
                    $user->token = $token;
                    $user->save();
                    $model->save();
                    $message = 'Success';
                    return response()->json([
                        'status'=>true,
                        'message'=>$message,
                        'data'=>['token'=>$token, 'is_registred'=>$is_registred]
                    ], 200);
                }
            }else{
                $message = "Failed your token didn't match";
                return response()->json([
                    'status'=>false,
                    'message'=>$message,
                ], 400);
            }
        }else{
            $message = "Failed your token didn't match";
            return response()->json([
                'status'=>false,
                'message'=>$message,
            ], 400);
        }
    }

    public function register(Request $request)
    {
        $user = Auth::user();
        $fields = $request->validate([
            'name' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);
        $user->password = bcrypt($fields['password']);
        $user->first_name = $fields['name'];
        $user->save();
        $data = [
            'user' => $user,
            'token' => $user->token??null
        ];
        return response()->json([
            'status'=>true,
            'message'=>'Success',
            'data'=>$data
        ], 200);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        $user = User::where('email', $fields['email'])->first();
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'status'=>false,
                'message'=>'Password or phone number is incorrect'
            ], 401);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        $user->token = $token;
        $user->save();
        $data = [
            'user' => $user,
            'token' => $token
        ];
        return response()->json([
            'status'=>true,
            'message'=>'Success',
            'data'=>$data
        ], 200);
    }

    public function googleLoginOrRegister(Request $request){
        $fields = $request->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'required|string',
            'password' => 'required',
            'picture' => 'required|string'
        ]);
        $user = User::where('email', $fields['email'])->first();
        $is_registered = 0;
        if(!isset($user->id)) {
            $is_registered = 1;
            $user = new User();
        }
        $user->first_name = $fields['first_name'];
        $user->last_name = $fields['last_name'];
        $user->email = $fields['email'];
        $user->password = $fields['password'];
        $user->avatar = $fields['picture'];
        $user->save();
        $token = $user->createToken('myapptoken')->plainTextToken;
        $user->token = $token;
        $user->save();
        $first_name = $user->first_name?$user->first_name.' ':'';
        $last_name = $user->last_name?$user->last_name.' ':'';
        $middle_name = $user->middle_name?$user->middle_name:'';
        $full_name = $first_name.''.$last_name.''.$middle_name;
        $data = [
            'full_name' => $full_name,
            'email' => $user->email??'',
            'avatar' => $user->avatar??'',
            'token' => $token??''
        ];
        return response()->json([
            'status'=>true,
            'message'=>'Success',
            'is_registered'=>$is_registered,
            'data'=>$data
        ], 200);
    }

    public function getUser(){
        $user = Auth::user();
        $user_image = null;
        if(isset($user->avatar)){
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
            "phone_number" => $user->phone_number??null,
            "gender" => $gender,
            "email" => $user->email??null,
            "avatar"=>$user_image,
            "birth_date"=>$birth_date,
            "token"=>$user->token??null
        ];
        return response()->json([
            'status'=>true,
            'message'=>'Success',
            'data'=>$data
        ], 200);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status'=>true,
            'message'=>'Success',
            'data'=>[]
        ], 200);
    }
}
