<?php namespace App\Http\Controllers;

use App\AssociateDirector;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\IslamicCenter;
use App\Khateeb;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;

class AdminController extends Controller {

    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('auth');
    }

    /**
     * here for returning view for creating new islamic center
     * @return \Illuminate\View\create_islamic_center
     */
	public function Create_Islamic_Center($id = null){
        $directors = DB::select("SELECT associate_director.id , associate_director.name FROM `associate_director`  left JOIN islamic_center on associate_director.id = islamic_center.director_id where islamic_center.director_id IS NULL");

        if($id != null){
            $ad = AssociateDirector::whereid($id)->first();
            return view("admin.create_islamic_center",compact("directors","ad"));
        }else{
            return view("admin.create_islamic_center",compact("directors"));
        }

    }


app/Http/Controllers/UserController.php
app/Http/routes.php
app/IslamicCenter.php
    /**
     * here for returning view for creating new create members
     * @return \Illuminate\View\create_members
     */
    public function Create_members(){
        return view("admin.create_members");
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


}
