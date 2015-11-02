<?php namespace App\Http\Controllers;

use App\AssociateDirector;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Khateeb;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class UserController extends Controller {



    public function __construct(){
        $this->middleware('auth');
        $this->middleware('user',["except"=>["getEditProfile"]]);
    }

	public function getIslamicCenterBlockedDates(){
        return view("user.blocked_dates");
    }

	public function getRatingPage(){
        return view("user.rating");
    }

    public function getEditProfile($id = null){

        if($id == null){
            // if he is user trying to edit his information
            $user_id = Auth::user()->user_id ;
            $role = Auth::user()->role_id ;
        }else{
            // if he is admin editing user information
            $user_id = $id ;
            $user = User::whereid($user_id)->first();
            $role = $user->role_id ;
            $user_id = $user->user_id ;
            $adminEditing = $user_id;
        }

        $result = User::getUserData($user_id , $role);

        $firstTime = "false";

        if($result->email == ""){
            $firstTime = "true";
        }
        if(isset($adminEditing)){
             return view("user.edit_profile",compact("firstTime","result","user_id","role","adminEditing"));
        }else {
             return view("user.edit_profile",compact("firstTime","result","user_id","role"));
        }
    }

    public function getProfile(){
        $user_id = Auth::user()->user_id ;
        $role = Auth::user()->role_id ;
        $user_info = User::getUserData($user_id , $role);
        return view("user.profile",compact($user_info));
    }


    public function updateProfile($id = null){

       $answer = User::validateAllFields(Input::all());

        if($answer == "true"){
            // okay all fields inserted and every thing is okay
            // if admin then id will not equal null
            if($id == null){
                // here if user editing his information
                if(Auth::user()->role_id == 2){
                    $result = Khateeb::addFields(Input::all());
                    if($result == "true"){
                        return "true";
                    }else{
                        return "false";
                    }
                }elseif(Auth::user()->role_id == 3){
                    $result = AssociateDirector::addFields(Input::all());
                    if($result == "true"){
                        return "true";
                    }else{
                        return "false";
                    }
                }
            }else{
                return $id ;
                // here if admin is editing user information
                if(Auth::user()->role_id == 1){
                    return $id ;
                    $user = User::whereid($id)->first();
                    $role = $user->role_id ;
                    $user_id = $user->user_id ;

                    if($role == 2){
                        $result = Khateeb::addFields(Input::all());
                        if($result == "true"){
                            return "true";
                        }else{
                            return "false";
                        }
                    }elseif($role == 3){
                        $result = AssociateDirector::addFields(Input::all());
                        if($result == "true"){
                            return "true";
                        }else{
                            return "false";
                        }
                    }else{
                        return "error";
                    }

                }else{
                    return "error";
                }
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
