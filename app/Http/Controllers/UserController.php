<?php namespace App\Http\Controllers;

use App\AdBlockedDates;
use App\AdChooseTheirIc;
use App\AdKhateebsPhoto;
use App\AssociateDirector;
use App\Cycle;
use App\Fridays;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\IslamicCenter;
use App\Khateeb;
use App\Khateebselectedfridays;
use App\Rating;
use App\Schedule;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class UserController extends Controller {

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('user',["except"=>["getEditProfile","updateProfile"]]);
        $this->middleware('cycleCheck');
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
            $fridays_choosen = AdBlockedDates::wherecycle_id($cycle->id)->whereic_id(Schedule::Return_Associated_Islamic_Center($user_id))->select("friday_id")->get();


            $fridays_choosen_my_ic = AdChooseTheirIc::wherecycle_id($cycle->id)->wheread_id(Auth::user()->user_id)->select("friday_id")->get();
            $fridays_choosen_other_ic = Khateebselectedfridays::wherecycle_id($cycle->id)->wherekhateeb_id(Auth::user()->id)->whererole_id(3)->select("friday_id")->get();

            // if this ad have islamic center attached to him then get the id and the name
            $islamic_center_data = IslamicCenter::wheredirector_id(Auth::user()->user_id)->with("Ad")->first();

            if(empty($islamic_center_data)){
                // here ad doesnot attached to islamic center
                $islamic_center_existence = false ;
                return view("user.blocked_dates",compact("name","role","fridays","fridays_choosen","islamic_center_existence","fridays_choosen_my_ic","fridays_choosen_other_ic"));
            }else{
                //else this ad is attached to islamic center return that it's already exists
                $islamic_center_existence = true ;
                $islamic_center = IslamicCenter::wheredirector_id($user_id)->select("id","name")->first();

                return view("user.blocked_dates",compact("name","role","fridays","fridays_choosen","islamic_center","islamic_center_existence","fridays_choosen_my_ic","fridays_choosen_other_ic"));
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
        if($role == 3){
            $pic = AdKhateebsPhoto::wheread_id(Auth::user()->user_id)->first();
            if(empty($pic)){
                $photo = "false" ;
            }else{
                $photo = "true" ;
            }
            return view("user.rating",compact("role","photo"));
        }
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
        $fridays_choosen_other_ic = Khateebselectedfridays::wherecycle_id($cycle->id)->wherekhateeb_id(Auth::user()->id)->whererole_id(3)->select("friday_id")->get();
        $blocked_dates = AdBlockedDates::wherecycle_id($cycle->id)->whereic_id(Auth::user()->user_id)->select("friday_id")->get();

        return view("user.ad_same_ic",compact("name","fridays","fridays_choosen","fridays_choosen_other_ic","blocked_dates"));
    }

    /**
     * @return \Illuminate\View\View user Give Khutbah In My IC
     */
	public function GiveKhutbahInOtherIC(){
        $user_data = User::getUserData(Auth::user()->user_id , 3);
        $name = $user_data->name ;

        $cycle = cycle::latest()->first();
        $fridays = Fridays::wherecycle_id($cycle->id)->select("id","date")->get();
        $fridays_choosen_my_ic = AdChooseTheirIc::wherecycle_id($cycle->id)->wheread_id(Auth::user()->user_id)->select("friday_id")->get();
        $fridays_choosen = Khateebselectedfridays::wherecycle_id($cycle->id)->wherekhateeb_id(Auth::user()->id)->whererole_id(3)->select("friday_id")->get();
        $blocked_dates = AdBlockedDates::wherecycle_id($cycle->id)->whereic_id(Auth::user()->user_id)->select("friday_id")->get();

        return view("user.ad_other_ics",compact("name","fridays","fridays_choosen","fridays_choosen_my_ic","blocked_dates"));
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
                 //return DB::select("SELECT khateeb.id , khateeb.name , khateeb.picture_url , rating.ad_rate_khateeb FROM `khateeb` left JOIN rating on rating.khateeb_id = khateeb.id where rating.ad_id = $ad_id or rating.khateeb_id is null and name !='' ");
                return AdKhateebsPhoto::khateebsForRating();
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
        if(Auth::user()->role_id == 2 ){
            return Rating::addRate($user_who_rate_id , $user_who_rate_role , $rated_user , $rate);
        }else{
            // true if he rating ic false rate khateeb
            if(Input::get("islamic_center") == "true"){
                return Rating::addRateToIslamic_center($user_who_rate_id , $user_who_rate_role , $rated_user , $rate);
//                $user = User::whereid(Input::get("id"))->first();
//                $user = AssociateDirector::whereid($user->user_id)->first();
//
//                $new_rate = new IslamicCenter() ;

            }else{
                // ad is rating khateeb ( ad or khateeb )
                return Rating::addRate($user_who_rate_id , $user_who_rate_role , $rated_user , $rate);
            }
        }

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


    // get all islamic centers to rate them for the ad
    public function getIcRating(){
        // return all islamic centers do not have this director id associated to them
        return IslamicCenter::where("director_id" ,"!=",Auth::user()->user_id)->select("id","name")->get();
    }

    // ad upload his picture
    public function adUploadPicture(){

        if (Input::hasFile('prof_pic')) {
            $file = Input::file('prof_pic');
            $file_original_name = $file->getClientOriginalName();
            $filename = str_random(32) . $file->getClientOriginalName();

            $destination = public_path() . '/images/khateeb_pictures/';

            $slider = Input::file("prof_pic")->getRealPath();

            if(Input::file('prof_pic')->move($destination, $filename)){
                $pic = new AdKhateebsPhoto();
                $pic->photo_url = $filename ;
                $pic->ad_id = Auth::user()->user_id ;

                $pic->save();

                return "true";

            }else{
                return "false";
            }
        }else{
            return "false";
        }

    }

    // return all khateebs +ad_khateebs to be rated from the ad that he is a khateeb
    public function khateebsForRating(){
        return AdKhateebsPhoto::khateebsForRating();
    }

    // return all islamic centers to be rated from the ad that he is a khateeb
    public function return_islamic_centers_for_Rating(){
        return IslamicCenter::return_islamic_centers_for_Rating();
    }

    /**
     * @param $friday_id
     * @param $islamic_center
     * @return array
     * return khateebs selected this friday to give khutbah and did not choosen
     * check if khateebs give this islamic center more than zero
     */
    public function availableThisFriday($friday_id,$islamic_center){
        // here i want get all khateebs will give khutbah this friday = (khateebs_will_give_khutbah)
            $schedule_khateebs = Schedule::wherefriday_id($friday_id)->select("khateeb_id")->get();
            $schedule_khateebs = Schedule::return_array($schedule_khateebs,"khateeb_id");

        // here i want to return available khateebs this friday  = (all_khateebs)
            if(!empty($schedule_khateebs)){
                $all_khateebs_choosed_this_friday = Khateebselectedfridays::Get_Khateebs_Choosed_that_Friday($friday_id,$schedule_khateebs);
            }else{
                $all_khateebs_choosed_this_friday = Khateebselectedfridays::Get_Khateebs_Choosed_that_Friday($friday_id);
            }

        // then filter the result and return khateebs that gave this islamic center higher from 0
        $ad_id = User::whereid(Schedule::Return_Associated_ad($islamic_center))->first();
            if(!empty($all_khateebs_choosed_this_friday)){
                $khateebs_allowed = Rating::wheread_id($ad_id->user_id)->whereIn("khateeb_id",Schedule::return_array($all_khateebs_choosed_this_friday,"khateeb_id"))->wherecycle_id(cycle::currentCycle())->where("ad_rate_khateeb","!=",0)->where("khateeb_rate_ad","!=",0)->get();
                $khateebs_allowed = $khateebs_allowed->toArray();
            }else{
                $khateebs_allowed = [];
            }

        if(!empty($khateebs_allowed)){
            // mapping data before sending it to the front
            return array_map(function($item){
                $user = User::whereid($item["khateeb_id"])->first();
                if($user->role_id == 2){
                    return[
                        "id"=>$item["khateeb_id"],
                        "name"=>User::GetUserDataForSchedule($item["khateeb_id"])->name
                    ];
                }else{
                    return[
                        "id"=>$item["khateeb_id"],
                        "name"=>User::GetUserDataForSchedule($item["khateeb_id"])->name
                    ];
                }
            },$khateebs_allowed);
        }else{
            return $khateebs_allowed ;
        }

    }

    /**
     * here editing schedule if there is a previous khateeb remove him and add new
     * if  there is no khateebs add new khateeb for this islamic center
     */
    public function EditSchedule(){
        $arrays = Input::get("data");
        if(!empty($arrays)){
            foreach($arrays as $array){
                $friday_id = $array["friday_id"];
                $islamic_center = $array["islamic_center"];
                $previous_id = $array["prev_value"];
                $new_id = $array["current"];

                if($previous_id == 0){
                    $schedule = new Schedule();
                    $schedule->friday_id =$friday_id ;
                    $schedule->ic_id = $islamic_center ;
                    $schedule->khateeb_id =$new_id ;
                    $schedule->cycle_id =cycle::currentCycle() ;
                    $schedule->save();
                }else{
                    $schedule = Schedule::wherefriday_id($friday_id)->whereic_id($islamic_center)->wherekhateeb_id($previous_id)->first();
                    $schedule->khateeb_id = $new_id ;
                    $schedule->update() ;
                }
            }
        }

    }


    /**
     *
     */

}