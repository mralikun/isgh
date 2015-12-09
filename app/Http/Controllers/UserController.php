<?php namespace App\Http\Controllers;

use App\AdBlockedDates;
use App\AdChooseTheirIc;
use App\AssociateDirector;
use App\Cycle;
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
	public function AvailableDates(){
        $user_id = Auth::user()->user_id ;
        $role = Auth::user()->role_id ;
        $user_data = User::getUserData($user_id , $role);
        $name = $user_data->name ;
        $cycle = cycle::latest()->first();
        $fridays = Fridays::wherecycle_id($cycle->id)->select("id","date")->get();

        if(Auth::user()->role_id == 2){
            $fridays_choosen = Khateebselectedfridays::wherecycle_id($cycle->id)->wherekhateeb_id(Auth::user()->id)->whererole_id($role)->select("friday_id")->get();
            return view("user.available_dates",compact("name","role","fridays","fridays_choosen"));
        }elseif(Auth::user()->role_id == 3){
            // return selected fridays to give khutbah in any other islamic center
                $fridays_choosen = Khateebselectedfridays::wherecycle_id($cycle->id)->wherekhateeb_id(Auth::user()->id)->whererole_id($role)->select("friday_id")->get();

            // Return choosen fridays that Ad will to give khutbah in
                $fridays_Ad_want_to_give_Khutbah_in = AdChooseTheirIc::getChoosenFridays($user_id , $cycle->id);

            // Return Fridays that didn't choosen either give khutbah in ( his islamic center , other islamic centers )
                $fridays_Ad_didnot_choose = AdChooseTheirIc::UnChoosenFridays($user_id , $cycle->id ,$fridays_choosen , $fridays_Ad_want_to_give_Khutbah_in);

            // if this ad have islamic center attached to him then git the id and th name
                $islamic_center_data = IslamicCenter::whereid(Auth::user()->user_id)->with("Ad")->first();

            if(empty($islamic_center_data)){
                // here ad does not attached to islamic center
                $islamic_center_existence = false ;
                return view("user.available_dates",compact("name","role","fridays","fridays_choosen","islamic_center_existence" , "fridays_Ad_want_to_give_Khutbah_in" , "fridays_Ad_didnot_choose"));
            }else{
                //else this ad is attached to islamic center return that it's already exists
                $islamic_center_existence = true ;
                $islamic_center = IslamicCenter::wheredirector_id($user_id)->select("id","name")->first();

                return view("user.available_dates",compact("name","role","fridays","fridays_choosen","islamic_center","islamic_center_existence", "fridays_Ad_want_to_give_Khutbah_in" , "fridays_Ad_didnot_choose"));
            }

        }else{
            return view("user.available_dates",compact("name","role","fridays","fridays_choosen"));
        }

    }


    /**
     * return islamic center blocked dates
     */
    public function getIslamicCenterBlockedDates(){
        $user_id = Auth::user()->user_id ;
        $role = Auth::user()->role_id ;
        $user_data = User::getUserData($user_id , $role);
        $name = $user_data->name ;
        $cycle = cycle::latest()->first();
        $fridays = Fridays::wherecycle_id($cycle->id)->select("id","date")->get();
        if(Auth::user()->role_id == 3) {
            $fridays_choosen = AdBlockedDates::wherecycle_id($cycle->id)->whereic_id($user_id)->select("friday_id")->get();

            // if this ad have islamic center attached to him then get the id and the name
            $islamic_center_data = IslamicCenter::whereid(Auth::user()->user_id)->with("Ad")->first();

            if(empty($islamic_center_data)){
                // here ad doesnot attached to islamic center
                $islamic_center_existence = false ;
                return view("user.blocked_dates",compact("name","role","fridays","fridays_choosen","islamic_center_existence"));
            }else{
                //else this ad is attached to islamic center return that it's already exists
                $islamic_center_existence = true ;
                $islamic_center = IslamicCenter::wheredirector_id($user_id)->select("id","name")->first();

                return view("user.blocked_dates",compact("name","role","fridays","fridays_choosen","islamic_center","islamic_center_existence"));
            }
        }
    }

    /**
     * post request to save islamic center blocked dates
     */
    public function setIslamicCenterBlockedDates($ic_id){
        return $result = AdBlockedDates::addBlockedDates(Input::get("dates"),$ic_id);
    }

    /**
     * @return \Illuminate\View\View user rating
     */
	public function getRatingPage(){
        $role = Auth::user()->role_id ;
        return view("user.rating",compact("role"));
    }

    /**
     * @return \Illuminate\View\View user Give Khutbah In My IC
     */
	public function GiveKhutbahInMyIC(){
        $user_data = User::getUserData(Auth::user()->user_id , 3);
        $name = $user_data->name ;

        $cycle = cycle::latest()->first();
        $fridays = Fridays::wherecycle_id($cycle->id)->select("id","date")->get();
        $fridays_choosen = AdChooseTheirIc::wherecycle_id($cycle->id)->wheread_id(Auth::user()->user_id)->get();
        $fridays_choosen_other_ic = Khateebselectedfridays::wherecycle_id($cycle->id)->wherekhateeb_id(Auth::user()->user_id)->whererole_id(3)->select("friday_id")->get();
        return view("user.ad_same_ic",compact("name","fridays","fridays_choosen","fridays_choosen_other_ic"));
    }

    /**
     * @return \Illuminate\View\View user Give Khutbah In My IC
     */
	public function GiveKhutbahInOtherIC(){
        $user_data = User::getUserData(Auth::user()->user_id , 3);
        $name = $user_data->name ;

        $cycle = cycle::latest()->first();
        $fridays = Fridays::wherecycle_id($cycle->id)->select("id","date")->get();
        $fridays_choosen_my_ic = AdChooseTheirIc::wherecycle_id($cycle->id)->wheread_id(Auth::user()->user_id)->get();
        $fridays_choosen = Khateebselectedfridays::wherecycle_id($cycle->id)->wherekhateeb_id(Auth::user()->user_id)->whererole_id(3)->select("friday_id")->get();
        return view("user.ad_other_ics",compact("name","fridays","fridays_choosen","fridays_choosen_my_ic"));
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
       $answer = User::validateAllFields(Input::all(),$id);

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
                $khateeb_id = Auth::user()->id ;
                return DB::select("SELECT islamic_center.director_id as id ,islamic_center.name , rating.khateeb_rate_ad FROM `islamic_center` left JOIN rating on rating.ad_id = islamic_center.director_id and rating.khateeb_id = $khateeb_id or rating.khateeb_id is null and name !=''");
                break;
            case 3 :
                $ad_id = Auth::user()->id ;
                return DB::select("SELECT khateeb.id , khateeb.name , khateeb.picture_url , rating.ad_rate_khateeb FROM `khateeb` left JOIN rating on rating.khateeb_id = khateeb.id where rating.ad_id = $ad_id or rating.khateeb_id is null and name !='' ");
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
    public function setDates($id = null){
        if($id == null){
            $role = Auth::user()->role_id ;
            $user_id = Auth::user()->user_id ;

            if($role == 2){
                return $result = Khateebselectedfridays::addAvailableDates(Input::get("dates"),$user_id,$role);
            }elseif($role == 3){
                return $result = Khateebselectedfridays::addAvailableDates(Input::get("dates"),$user_id,$role);
            }else{
                return false ;
            }
        }else{
            $role = Auth::user()->role_id ;
            $user_id = Auth::user()->user_id ;
            return $result = Khateebselectedfridays::addAvailableDates(Input::get("dates"),$user_id,$role);
        }

    }


    public function same_islamic_center(){
        return AdChooseTheirIc::AddFridays(Input::get("dates"));
    }
}