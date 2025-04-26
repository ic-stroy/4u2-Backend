<?php

namespace App\Http\Controllers;

use App\Models\UserCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getCards(Request $request)
    {
        $user = Auth::user();
        $data = UserCard::where('user_id', $user->id)->get()->map(function($user_card){
            return [
                'id'=>$user_card->id,
                'name'=>$user_card->name??null,
                'user_name'=>$user_card->user_name??null,
                'card_number'=>$user_card->card_number??null,
                'validity_period'=>$user_card->validity_period??null,
                'user_id'=>$user_card->user_id??null,
            ];
        });
        if($data->isNotEmpty()){
            return $this->success("Success", 200, $data->toArray());
        }else{
            return $this->error('No cards', 400);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function storeCard(Request $request)
    {
        $user = Auth::user();
        $user_card = new UserCard();
        $user_card->name = $request->card_name;
        $user_card->user_name = $request->card_user_name;
        $user_card->card_number = (int)$request->card_number;
        $user_card->validity_period = $request->card_validity_period;
        $user_card->user_id = (int)$user->id;
        $user_card->save();
        return $this->success("Success", 200);
    }

    /**
     * Display the specified resource.
     */
    public function showCard(Request $request)
    {
        $user = Auth::user();
        $user_card = UserCard::where('user_id', $user->id)->find($request->id);
        if($user_card){
            $data = [
                'id'=>$user_card->id,
                'name'=>$user_card->name??null,
                'user_name'=>$user_card->user_name??null,
                'card_number'=>$user_card->card_number??null,
                'validity_period'=>$user_card->validity_period??null,
                'user_id'=>$user_card->user_id??null,
            ];
        }
        if(isset($data)){
            return $this->success('Success', 200, $data);
        }else{
            return $this->error("No cards", 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCard(Request $request)
    {
        $user = Auth::user();
        $user_card = UserCard::where('user_id', $user->id)->find($request->id);
        $user_card->name = $request->card_name;
        $user_card->user_name = $request->card_user_name;
        $user_card->card_number = (int)$request->card_number;
        $user_card->validity_period = $request->card_validity_period;
        $user_card->user_id = (int)$user->id;
        $user_card->save();
        return $this->success("Success", 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyCard(Request $request)
    {
        $user = Auth::user();
        $user_card = UserCard::where('user_id', $user->id)->find($request->id);
        if($user_card->order){
            return $this->success(translate('prohibited'), 200);
        }
        $user_card->delete();
        return $this->success("Success", 200);
    }
}
