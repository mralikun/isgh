<?php namespace App\Http\Controllers;

use App\Admin;
use App\AssociateDirector;
use App\Cycle;
use App\Fridays;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\IslamicCenter;
use App\Khateeb;
use App\Schedule;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
class AdminController extends Controller {

    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('auth');
        $this->middleware('cycleCheck',["only"=>["Create_members","Manage_schedule","edit_members","Edit_Islamic_Center_Information","Edit_Members_Information"]]);
    }
    /**
     * here for returning view for creating new islamic center
     * @return \Illuminate\View\create_islamic_center
     */
    public function Create_Islamic_Center($id = null){
        $directors = DB::select("SELECT associate_director.id , associate_director.name FROM `associate_director`  left JOIN islamic_center on associate_director.id = islamic_center.director_id where islamic_center.director_id IS NULL and associate_director.name !='' ");
        /**
         * Her i want to return the ad attached to the islamic center and his phone and his id
         * admin trying to edit ad information
         */
        // create new islamic center
        if($id != null){
            $islamic_center = IslamicCenter::whereid($id)->first();
            // change the date to timestamp
            $islamic_center->khutbah_start = IslamicCenter::TransformDate($islamic_center->khutbah_start) ;
            $islamic_center->khutbah_end = IslamicCenter::TransformDate($islamic_center->khutbah_end) ;

            $director_id  = $islamic_center->director_id ;
            $director_data = AssociateDirector::whereid($director_id)->select("id","name","phone")->first();
            return view("admin.create_islamic_center",compact("directors","islamic_center","director_data"));
        }else{
            return view("admin.create_islamic_center",compact("directors","islamic_center","director_data"));
        }
    }
    /**
     * here for returning view for creating new create members
     * @return \Illuminate\View\create_members
     */
    public function Create_members(){
        return view("admin.create_members");
    }
    /**
     * @return \Illuminate\View\View
     * return admin to create cycle
     */
    public function getCyclePage(){
        return view("admin.create_cycle");
    }
    /**
     * here for returning view for creating new schedule
     * @return \Illuminate\View\schedule
     */
    public function Manage_schedule(){
        return view("admin.schedule");
    }
    /**
     * here for returning view for editing members
     * @return \Illuminate\View\edit_members
     */
    public function Edit_Members_Information(){
        $all = User::getUserNames();
        return view("admin.edit_members",compact("all"));
    }
    /**
     * here for returning view for edit islamic center
     * @return \Illuminate\View\edit_islamic_center
     */
    public function Edit_Islamic_Center_Information(){
        $all = IslamicCenter::select("id","name")->get();
        return view("admin.edit_islamic_center",compact("all"));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function islamicCenterData($id){
        return IslamicCenter::with("Ad")->whereid($id)->first();
    }

    /**
     * @return mixed
     * this function responsible for adding new user
     * if user exsits return true uif not exist call add new user function
     */
    public function createUser(){
        $username = Input::get("username");
        $user = User::whereusername($username)->first();
        if($user == "" ){
            $input = Input::all();
            $user = new User();
            return $result = $user->addnewuser($input);
        }else{
            return "true";
        }
    }

    /**
     * @param $id
     * @return string
     * delete admin from admins where id = his id
     */
    public function DeleteAdmin($id){
        $user = User::whereid($id)->first();
        if(!empty($user)){
            User::whereid($id)->delete();
            Admin::whereid($user->user_id)->delete();
        }else{
            return "could not find this user";
        }
    }

    /**
     * @return mixed
     * create new islamic center
     */
    public function createIslamicCenter($id=null){
        $input = Input::all();
        //admin create new islamic center
        if($id == null){
            $name = Input::get("name");
            $islamic_center = IslamicCenter::wherename($name)->first();
            if(empty($islamic_center)){
                return IslamicCenter::addNewIslamicCenter($input);
            }else{
                return "false";
            }
        }else{
            return IslamicCenter::EditExistingIslamicCenter($input ,$id);
        }
    }
    /**
     * Here for deleting Khateebs from the system
     */
    public function DeleteKhateeb($id){
        $result = Khateeb::DeleteMembers($id);
        if($result == "true"){
            return "true";
        }else{
            return "false";
        }
    }
    /**
     * Here for deleting Associative Directories from the system
     */
    public function DeleteAd($id){
        $result = AssociateDirector::DeleteMembers($id);
        if($result == "true"){
            return "true";
        }else{
            return "false";
        }
    }
    /**
     * Here for deleting Islamic Centers from the system
     */
    public function DeleteIslamicCenter($id){
        $result = IslamicCenter::DeleteMembers($id);
        if($result == "true"){
            return "true";
        }else{
            return "false";
        }
    }
    /**
     * @return mixed
     * get cell phone for the director
     */
    public function getCellPhone(){
        $id = Input::get("id");
        $phone = AssociateDirector::whereid($id)->first();
        return $phone->phone ;
    }
    /**
     * @param $startDate
     * @param $months
     * @return string
     */
    private function getEndDate($startDate , $months){
        $date = new \DateTime($startDate);
        $interval = new \DateInterval("P".$months."M");
        $date->add($interval);
        return $date->format('Y-m-d');
    }
    /**
     * creating new cycle and fridays
     * take start date and end date
     */
    public function start_New_Cycle(){
        $date = Input::get("cycle_start_date");
        $months = Input::get("months");
        $newDate = $this::getEndDate($date , $months);
        $result = cycle::CreateNewCycle($date , $newDate);
        if(is_numeric($result)){
            $cycle = cycle::whereid($result)->first();
            $final_result = Fridays::addFridays($cycle,$date ,$this::getEndDate($date , $months));
            if($final_result == "true"){
                return redirect("/admin/members/create");
            }else{
                return redirect("/admin/members/create");
            }
        }elseif($result == "false"){
            return redirect("/admin/create_cycle");
        }elseif($result == "Cycle did not finished yet"){
            return redirect("/admin/create_cycle",compact("result"));
        }else{
            $error = "unknown_error";
            return redirect("/admin/create_cycle",compact("error"));
        }
    }


    public function getSchedule(){
        $schedule = Schedule::wherecycle_id(Cycle::currentCycle())->get();

        foreach($schedule as $sch){
            $user_id = $sch->khateeb_id ;

            // getting user data either khateeb or associative director
            $sch->khateeb = User::GetUserDataForSchedule($user_id) ;

            // now getting friday associated to this row
            $sch->friday = Fridays::find($sch->friday_id) ;

            // now getting friday associated to this row
            $sch->islamic_center = IslamicCenter::find($sch->ic_id) ;
        }
        return $schedule ;
    }

}