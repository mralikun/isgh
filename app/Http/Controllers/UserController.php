<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Khateeb;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class UserController extends Controller {


	public function getIslamicCenterBlockedDates(){
        return view("user.blocked_dates");
    }

	public function getRatingPage(){
        return view("user.rating");
    }

    public function getEditProfile(){
        $user_id = Auth::user()->user_id ;
        $role = Auth::user()->role_id ;
        $result = User::getUserData($user_id , $role);

        $firstTime = "false";
        if($result->email == ""){
            $firstTime = "true";
        }

        return view("user.edit_profile" , compact("firstTime" , "result"));
    }

    public function getProfile(){
        $user_id = Auth::user()->user_id ;
        $role = Auth::user()->role_id ;
        $user_info = User::getUserData($user_id , $role);
        return view("user.profile",compact($user_info));
    }


    public function updateProfile(){
        $answer = User::validateAllFields(Input::all());

        if($answer == "true"){
            // okay all fields inserted and every thing is okay
            if(Auth::user()->role_id == 2){
                $result = Khateeb::addFields(Input::all());
                if($result == "true"){
                    return "true";
                }else{
                    return "false";
                }
            }elseif(Auth::user()->role_id == 3){
                return "ad";
            }
        }else{
            // there are some fields did not inserted correctly
            return $answer ;
        }
    }

    // getting role for this user in my website
    public function onlineUserRole(){
        return User::getRole();
    }
}
