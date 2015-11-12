<?php namespace App\Http\Controllers;

use App\AdBlockedDates;
use App\AssociateDirector;
use App\cycle;
use App\Fridays;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\IslamicCenter;
use App\Khateeb;
use App\Khateebselectedfridays;
use App\Rating;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class UserController extends Controller {

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('user',["except"=>["getEditProfile","updateProfile"]]);
    }

    /**
     * @return \Illuminate\View\View user blocked_dates
     */
	public function getIslamicCenterBlockedDates(){
        $user_id = Auth::user()->user_id ;
        $role = Auth::user()->role_id ;
        $user_data = User::getUserData($user_id , $role);
        $name = $user_data->name ;
        $cycle = cycle::latest()->first();
        $fridays = Fridays::wherecycle_id($cycle->id)->select("id","date")->get();
        return view("user.blocked_dates",compact("name","role","fridays"));
    }

    /**
     * @return \Illuminate\View\View user rating
     */
	public function getRatingPage(){
        $role = Auth::user()->role_id ;
        return view("user.rating",compact("role"));
    }

    /**
     * @param null $id
     * @return \Illuminate\View\View
     * if user trying to edit his profile it's okay get the user_id and role_id and return his data
     * if admin is trying to edit khateeb profile and edit his data check if the $id is added if so then return the data for this user
     */
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
            $adminEditing = $id;
        }

        // return user data according to user_id and his role
        $result = User::getUserData($user_id , $role);

        $firstTime = "false";

        // check if this is his first time to access his profile by checking email if email is set then this is not his first time if not then this is his first time
        if($result->email == ""){
            $firstTime = "true";
        }

        // $adminEditing = $id; if this variable is set then he is admin accessing user profile to edit him else do not pass the admin editing var
        if(isset($adminEditing)){
             return view("user.edit_profile",compact("firstTime","result","user_id","role","adminEditing"));
        }else {
             return view("user.edit_profile",compact("firstTime","result","user_id","role"));
        }
    }

    /**
     * @return \Illuminate\View\View user profile
     * user accessing his profile return him his data using this method getUserData that takes user_id and role_id and get data
     */
    public function getProfile(){
        $user_id = Auth::user()->user_id ;
        $role = Auth::user()->role_id ;
        $user_info = User::getUserData($user_id , $role);
        return view("user.profile",compact("user_info"));
    }

    /**
     * @param null $id
     * @return array|string
     * after user access and request to update his profile he send a post request to this function
     * id = number admin edit khateeb data
     * id = null khateeb edit his data
     */
    public function updateProfile($id = null){

        // validate fields no thing null if there is null return the null data
       $answer = User::validateAllFields(Input::all());

        //if there is no null data in the data passed
        if($answer == "true"){
            // okay all fields inserted and every thing is okay
            // if admin then id will not equal null
            if($id == null){
                // here if user editing his information
                if(Auth::user()->role_id == 2){
                   return  $result = Khateeb::addFields(Input::all());
                }elseif(Auth::user()->role_id == 3){
                    return $result = AssociateDirector::addFields(Input::all());
                }
            }else{
                // here if admin is editing user information
                if(Auth::user()->role_id == 1){

                    $user = User::whereid($id)->first();
                    $role = $user->role_id ;
                    $user_id = $user->user_id ;

                    // if khateeb
                    if($role == 2){
                        $result = Khateeb::addFields(Input::all());
                        if($result == "true"){
                            return "true";
                        }else{
                            return "false";
                        }
                    // if associative director
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

    // returning first ten records to the rating page
    public function startRate(){
        switch(Auth::user()->role_id){
            case 2 :
                $khateeb_id = Auth::user()->user_id ;
                return DB::select("SELECT islamic_center.director_id as id ,islamic_center.name , rating.khateeb_rate_ad FROM `islamic_center` left JOIN rating on rating.ad_id = islamic_center.director_id and rating.khateeb_id = $khateeb_id or rating.khateeb_id is null");
                break;
            case 3 :
                $ad_id = Auth::user()->user_id ;
                return DB::select("SELECT khateeb.id , khateeb.name , khateeb.picture_url , rating.ad_rate_khateeb FROM `khateeb` left JOIN rating on rating.khateeb_id = khateeb.id where rating.ad_id = $ad_id or rating.khateeb_id is null");
                break ;
            default:
                return "false";
        }
    }

    // adding rate
    public function addRate(){
        $user_who_rate_id = Auth::user()->user_id ;
        $user_who_rate_role = Auth::user()->role_id ;
        $rated_user = Input::get("id");
        $rate = Input::get("rate");
        // khateeb ad rating to ad add rating khateeb
        return Rating::addRate($user_who_rate_id , $user_who_rate_role , $rated_user , $rate);
    }

    /**
     * in this function khateeb or ad can add
     * khateeb : choose his available dates
     * ad : choose islamic center blocked dates
     */
    public function setDates(){
        $role = Auth::user()->role_id ;
        $user_id = Auth::user()->user_id ;

        if($role == 2){
            return $result = Khateebselectedfridays::addAvailableDates(Input::get("dates"),$user_id,$role);
        }elseif($role == 3){
            return $result = AdBlockedDates::addBlockedDates(Input::get("dates"),$user_id,$role);
        }else{
            return false ;
        }
    }


}
