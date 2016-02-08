<?php namespace App\Http\Controllers;

use App\AdBlockedDates;
use App\Admin;
use App\Approve;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\Cast\Object_;

class AdminController extends Controller {
    protected static $mail ;
    protected static $fridays ;

    public function __construct()
    {
        $this->middleware('admin',["except"=>["Manage_schedule","CheckScheduleExistence","checkScheduleApprove","getSchedule"]]);
        $this->middleware('auth');
        $this->middleware('cycleCheck',["only"=>["Create_members","edit_members","Edit_Islamic_Center_Information","Edit_Members_Information"]]);
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
            if(Auth::user()->role_id == 3){
                $admin = "true";
                return view("admin.create_islamic_center",compact("directors","islamic_center","director_data","admin"));
            }else{
                return view("admin.create_islamic_center",compact("directors","islamic_center","director_data"));
            }
        }else{
            if(Auth::user()->role_id == 3) {
                $admin = "true";
                return view("admin.create_islamic_center", compact("directors", "islamic_center", "director_data", "admin"));
            }else{
                return view("admin.create_islamic_center", compact("directors", "islamic_center", "director_data"));
            }
        }
    }
    /**
     * here for returning view for creating new create members
     * @return \Illuminate\View\create_members
     */
    public function Create_members(){
        $islamic_centers = IslamicCenter::select(["id","name"])->get();

        if(Auth::user()->role_id == 3) {
            $admin = "true";

            return view("admin.create_members",compact("admin" ,"islamic_centers"));
        }else{
            return view("admin.create_members",compact("islamic_centers"));
        }

    }
    /**
     * @return \Illuminate\View\View
     * return admin to create cycle
     */
    public function getCyclePage(){
        if(Auth::user()->role_id == 3) {
            $admin = "true";
            return view("admin.create_cycle",compact("admin"));
        }else{
            return view("admin.create_cycle");
        }

    }
    /**
     * here for returning view for creating new schedule
     * @return \Illuminate\View\schedule
     */
    public function Manage_schedule(){
        if(Auth::user()->role_id == 3){
            $ad = AssociateDirector::whereid(Auth::user()->user_id)->first();
            if($ad->reviewer == 1){
                $admin = "true";
                return view("admin.schedule",compact("admin"));
            }elseif($ad->reviewerschedule == 1) {
                $reviewer = "true";
                return view("admin.schedule",compact("reviewer"));
            }else{
                return Redirect::to("/");
            }
        }elseif(Auth::user()->role_id == 1){
            return view("admin.schedule");
        }else{
            Redirect::to("/");
        }

    }
    /**
     * here for returning view for editing members
     * @return \Illuminate\View\edit_members
     */
    public function Edit_Members_Information(){
        $all = User::getUserNames();
        if(Auth::user()->role_id == 3) {
            $admin = "true";
            return view("admin.edit_members",compact("all","admin"));
        }else{
            return view("admin.edit_members",compact("all"));
        }

    }
    /**
     * here for returning view for edit islamic center
     * @return \Illuminate\View\edit_islamic_center
     */
    public function Edit_Islamic_Center_Information(){
        $all = IslamicCenter::select("id","name")->get();
        if(Auth::user()->role_id == 3) {
            $admin = "true";
            return view("admin.edit_islamic_center",compact("all","admin"));
        }else{
            return view("admin.edit_islamic_center",compact("all"));
        }

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
        $result = Cycle::CreateNewCycle($date , $newDate);
        if(is_numeric($result)){
            $cycle = Cycle::whereid($result)->first();
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

    /**
     * @return mixed
     * return the schedule for the current cycle
     */
    public function getSchedule($data = null)
    {
        $schedule = Schedule::wherecycle_id(Cycle::currentCycle())->get();

        foreach ($schedule as $sch) {
            $user_id = $sch->khateeb_id;

            // getting user data either khateeb or associative director
            $sch->khateeb = User::GetUserDataForSchedule($user_id);

            // now getting friday associated to this row
            $sch->friday = Fridays::find($sch->friday_id);

            // now getting friday associated to this row
            $sch->islamic_center = IslamicCenter::find($sch->ic_id);
        }
        if ($data == null) {
            $schedule = $schedule->toArray();

            $blocked_dates = self::getBlockedDatesEnhancedLikeSchedule();

            return array_merge($blocked_dates, $schedule);
        } else {
            return $schedule ;
        }
    }

    public static function getBlockedDatesEnhancedLikeSchedule(){
        return self::returnScheduleMappedBlockedDates(Schedule::addingBlockedDatesVisitorNames());
    }

    public static function returnScheduleMappedBlockedDates($array){
        foreach($array as $key=>$other){
            // getting user data either khateeb or associative director
            $other["khateeb"] = [];
            $other["khateeb"]["name"] = $other["khateeb_id"] ;
            $other["khateeb"]["email"] =  "not available";
            $other["khateeb"]["address"] = "not available" ;
            $other["khateeb"]["bio"] = "not available" ;
            $other["khateeb"]["created_at"] = "not available" ;
            $other["khateeb"]["edu_background"] = "not available" ;
            $other["khateeb"]["id"] = 5000 ;
            $other["khateeb"]["member_isgh"] = "---------" ;
            $other["khateeb"]["phone"] = "---------"  ;
            $other["khateeb"]["picture_url"] = "not available" ;
            $other["khateeb"]["post_code"] = "not available" ;
            $other["khateeb"]["updated_at"] = "not available" ;

            // now getting friday associated to this row
            $other["friday"] = Fridays::find($other["friday_id"]) ;

            // now getting friday associated to this row
            $other["islamic_center"] = IslamicCenter::find($other["ic_id"]) ;
            $array[$key]["khateeb"] = $other["khateeb"] ;
            $array[$key]["friday"] = $other["friday"] ;
            $array[$key]["islamic_center"] = $other["islamic_center"] ;
        }
        return $array ;


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
                    $schedule->cycle_id =Cycle::currentCycle() ;
                    $schedule->save();
                }else{
                    $schedule = Schedule::wherefriday_id($friday_id)->whereic_id($islamic_center)->wherekhateeb_id($previous_id)->first();
                    $schedule->khateeb_id = $new_id ;
                    $schedule->update() ;
                }
            }
        }

    }



    public function CheckScheduleExistence(){
        $cycle = Cycle::currentCycle();
        $schedule = Schedule::latest()->first();
        if(empty($schedule)){
            return "false";
        }else{
            $cycle_id = $schedule->cycle_id ;
            if($cycle_id == $cycle){
                return "true";
            }
            else{
                return "false";
            }
        }
    }

    public function checkScheduleApprove(){
        $cycle = Cycle::currentCycle();
        $approve = Approve::wherecycle_id($cycle)->first();
        if(!empty($approve)){
            if($approve->approve == 0){
                return "false";
            }else{
                return "true";
            }
        }else{
            return "false";
        }
    }

    public function approveCurrentCycle(){
        $cycle = Cycle::currentCycle();
        $approve = Approve::wherecycle_id($cycle)->first();
        if(empty($approve)){
            $approve = new Approve();
            $approve->cycle_id = $cycle ;
            $approve->approve = 1 ;
            $approve->save();
        }else{
            $approve->approve = 1 ;
            $approve->save();
        }
    }

    public function approveSchedule(){
        // first get the schedule into an array
        $schedule = self::getSchedule("get me schedule only");
        // approve schedule
//        self::approveCurrentCycle();

        if(!empty($schedule)){

            // then i have to get first element in the schedule and check if there are khateebs in the schedule or not
            // match this khateeb to collect him in one array and the add him to another array and unset this element from the array
//            foreach($schedule as $element){
//                $khateeb = $element;
//                $firstkhateeb = [];
//
//                foreach($schedule as $key=>$value){
//                    if($khateeb->khateeb_id === $value->khateeb_id){
//                        array_push($firstkhateeb , $value) ;
//                        unset($schedule[$key]);
//                    }
//                }
//                if(!empty($firstkhateeb)){
//                    self::SendEmailToKhateeb($firstkhateeb);
//                }
//            }

            $islamic_center_schedule = self::getSchedule();
            // here like i made with the khateeb i will with the islamic center to send to the islamic center one email include all data needed
            // for khateebs and any info they need
            foreach($islamic_center_schedule as $element){
                if(!empty($islamic_center_schedule)){
                    $islamic_center = $element;
                    $firstic = [];

                    foreach($islamic_center_schedule as $key=>$value){
                        if($value["islamic_center"]["id"] == $islamic_center["islamic_center"]["id"]){
                            array_push($firstic , $value) ;
                            unset($islamic_center_schedule[$key]);
                        }
                    }
                    if(!empty($firstic)){
                        self::SendEmailToIslamicCenter($firstic);
                    }
                }
            }
        }else{
            return "false";
        }

    }

    /**
     * @param $array_of_ic_to_khateeb
     * here managing data to be sent to view
     */
    public function SendEmailToKhateeb($array_of_ic_to_khateeb){
        $merged_data = ["khateeb_data"=>[],"islamic_centers"=>[]];

        $khateeb_info = [
            "name"=>$array_of_ic_to_khateeb[0]->khateeb->name,
            "email"=>$array_of_ic_to_khateeb[0]->khateeb->email,
        ];
        array_push($merged_data["khateeb_data"],$khateeb_info);

        foreach($array_of_ic_to_khateeb as $ic){
            $ad = Schedule::Return_Associated_ad($ic->islamic_center->id) ;
            if(!empty($ad)){
                $ad = User::GetUserDataForSchedule($ad,3) ;
            }
            if(!empty($ad)){
                $ad_phone = $ad->phone ;
                $ad_email = $ad->email ;
            }else{
                $ad_phone = "no associated ad";
                $ad_email = "no associated ad";
            }

            $element =[
                "date"=>$ic->friday->date ,
                "ic_name"=>$ic->islamic_center->name ,
                "khutbah_start"=>date("G:i",strtotime($ic->islamic_center->khutbah_start)) ,
                "khutbah_end"=>date("G:i",strtotime($ic->islamic_center->khutbah_end)) ,
                "parking_information"=>$ic->islamic_center->parking_information ,
                "address"=>$ic->islamic_center->address ,
                "other_information"=>$ic->islamic_center->other_information,
                "ad_phone"=>$ad_phone,
                "ad_email"=>$ad_email

            ];
            array_push($merged_data["islamic_centers"],$element);
        }
        if(!empty($merged_data)){
            self::sendEmailViewToKhateeb($merged_data);
        }
    }

    /**
     * @param $data
     * here sending data to view
     */
    public function sendEmailViewToKhateeb($data){
        if(!empty($data)){
            dd($data);
            self::$mail = $data["khateeb_data"][0]["email"];

            Mail::send("emails.schedule",["data"=>$data],function($m){
                $m->to(self::$mail);
                $m->subject("Isgh Schedule");
            });
        }
    }

    public function SendEmailToIslamicCenter($array_of_khateebs_to_ic){

        $merged_data = ["ad_data"=>[],"khateebs"=>[]];
        $ad = AssociateDirector::whereid($array_of_khateebs_to_ic[0]["islamic_center"]["director_id"])->first();

        $ad_info = [
            "name"=>$ad["name"],
            "email"=>$ad["email"]
        ];

        array_push($merged_data["ad_data"],$ad_info);

        foreach($array_of_khateebs_to_ic as $khateeb){

            $element =[
                "date"=>$khateeb["friday"]["date"] ,
                "name"=>$khateeb["khateeb"]["name"] ,
                "phone"=>$khateeb["khateeb"]["phone"] ,
                "email"=>$khateeb["khateeb"]["email"]

            ];
            array_push($merged_data["khateebs"],$element);
        }

        if(!empty($merged_data)){
            self::sendEmailViewToIslamicCenter($merged_data);
        }
    }

    public function sendEmailViewToIslamicCenter($data){
        if(!empty($data)){

            self::$mail = $data["ad_data"][0]["email"];
            if(!is_null(self::$mail)){
                Mail::send("emails.schedule_ic",["data"=>$data],function($m){
                    $m->to(self::$mail);
                    $m->subject("Isgh Schedule");
                });
            }

        }
    }

    public function ExportSchedule(){

        $schedule = self::getSchedule();

        $islamic_centers = [];
        foreach($schedule as $element){
            if(!empty($schedule)){
                $islamic_center = $element;
                $firstic = [];

                foreach($schedule as $key=>$value){
                    if($value["islamic_center"]["id"] == $islamic_center["islamic_center"]["id"]){
                        array_push($firstic , $value) ;
                        unset($schedule[$key]);
                    }
                }
                if(!empty($firstic)){
                    array_push($islamic_centers , $firstic);
                }
            }
        }

        $fridays = Fridays::wherecycle_id(Cycle::currentCycle())->get();
        $fridays = Schedule::return_array($fridays ,"date");
        self::$fridays = $fridays ;

        $islamic_centers = array_map(function($item){
            return [
                "islamic_center"=>$item[0]["islamic_center"]["name"],
                "friday_khateeb"=>self::returndata($item)
            ];
        },$islamic_centers);

        $islamic_centers = array_map(function($element){
            return [
                "name"=>$element["islamic_center"],
                "fridays"=>self::returnDatahandeled($element["friday_khateeb"])
            ];
        },$islamic_centers);

        foreach($islamic_centers as $ic){

            foreach($ic["fridays"] as $i){
                usort($ic["fridays"],function( $a, $b ) {
                    foreach($a as $w){
                        foreach($b as $s){
                            return strtotime($w["friday"]) - strtotime($s["friday"]);
                        }
                    }
                });
            }
        }

        $main = "";
        foreach($islamic_centers as $ic){
            $data = "<tr>
                        <td  style='background-color: #808080;color:#ffffff ; text-align: center;padding: 20px;'>".$ic["name"]."</td>";
            foreach($ic["fridays"] as $i){
                $khateebs = "<td style=' text-align: center;'>";
                foreach($i as $w){
                    $khateebs = $khateebs.$w["khateebs"]."</td>" ;
                }
                $data = $data.$khateebs;
            }
            $data = $data."</tr>";
            $main = $main.$data ;
        }

        Excel::create("Schedule", function($excel) use($main) {

            $excel->sheet('Schedule', function($sheet) use($main) {

                $sheet->setStyle(array(
                    'font' => array(
                        'name'      =>  'Calibri',
                        'size'      =>  18,
                        'bold'      =>  false
                    )
                ));
                $sheet->cells('A2:N2', function($cells) {

                    $cells->setBackground('#4CAF50');
                    $cells->setFontColor('#ffffff');
                    $cells->setAlignment("vertical");
                    $cells->setFontSize(22);
                });

                $sheet->cells('A1:M1', function($cells) {
                    $cells->setBorder("thin","thin","thin","thin");
                    $cells->setAlignment("horizontal");
                    $cells->setAlignment("vertical");
                    $cells->setBackground('#808080');
                    $cells->setFontColor('#ffffff');
                    $cells->setFontSize(25);
                    $cells->setFontFamily("italic");
                });

                $sheet->cells('A3:Z19', function($cells) {
                    $cells->setFontSize(15);
                    $cells->setAlignment("horizontal");
                });

                $sheet->setWidth(array(
                    'A'=>25, 'B' => 25, 'C' =>25, 'D' =>25, 'E' =>25, 'F' =>25, 'G' =>25, 'H' =>25, 'I' =>25, 'J' =>25, 'K' =>25, 'L' =>25, 'M' =>25, 'N' =>25,
                ));

                $sheet->loadView('excel.schedule')
                    ->with('data', $main)
                    ->with('fridays', self::$fridays);
            });
        })->export('xlsx');


    }

    public function returndata($item){
        $global_array = [];

        foreach(self::$fridays as $friday){
            $data = array_filter($item ,function($i) use ($friday){
                return $friday == $i["friday"]["date"];
            });

            $data = array_map(function($item){
                return[
                    "friday"=>$item["friday"]["date"] ,
                    "khateeb"=>$item["khateeb"]["name"]
                ];
            },$data);

            array_push($global_array ,["fridays"=>$data]);
        }

        $fridays = [];
        foreach($global_array as $el){
            if(!empty($el)){
                foreach($el["fridays"] as $f){
                    if(!empty($f)){
                        array_push($fridays,$f["friday"]);
                    }
                }
            }else{
                unset($el);
            }
        }


        foreach(self::$fridays as $friday){
            if(!in_array($friday,$fridays)){
                array_push($fridays,$friday);
                array_push($global_array,["fridays"=>[
                    [
                        "friday"=>$friday,
                        "khateeb"=>"--"
                    ]
                ]]);
            }
        }
        return $global_array ;
    }

    public function returnDatahandeled($islamic_centers){
        foreach($islamic_centers as $key=>$ic){
            if(empty($ic["fridays"])){
                unset($islamic_centers[$key]);
            }
        }

        $general = [];
        foreach($islamic_centers as $ic){
            $data = [];
            foreach($ic as $el){
                $khateebs = "";
                $innerArray= "";
                foreach($el as $e1){
                    if($innerArray ==""){
                        $innerArray = $e1["friday"];
                    }
                    $khateebs =  $e1["khateeb"]." , ".$khateebs;
                }
                array_push($data ,["friday"=>$innerArray ,"khateebs"=>$khateebs]);
            }
            array_push($general , $data);
        }

        return $general ;
    }

    public function startSchedule(){
        $blocked = AdBlockedDates::where("cycle_id","=",Cycle::currentCycle())->where("confirm","=",1)->get();
//        if(!empty($blocked)){
//            return "There are blocked dates can not be escaped";
//        }
        $schedule = new \App\Schedule;
        return $schedule->start();
    }

    /**
     * @return array
     * this function resbonsible for returning all blocked data requested
     */
    public function CheckBlockedDates(){
        $requested = Input::get("requested");
        if(empty($requested)){
            $requested = null ;
        }

        $blocked = AdBlockedDates::where("cycle_id","=",Cycle::currentCycle())->get();

        if(!empty($blocked)){
            $blocked = self::mapBlocked($blocked);
            $accepted = array_filter($blocked, function ($block) {
                if ($block["status"] == 2) {
                    return self::mapBlocked($block, $block["islamic_center_data"]["director_id"]);
                }
            });
            $waiting = array_filter($blocked, function ($block) {
                if ($block["status"] == 1) {
                    return self::mapBlocked($block, $block["islamic_center_data"]["director_id"]);
                }
            });
            $rejected = array_filter($blocked, function ($block) {
                if ($block["status"] == 3) {
                    return self::mapBlocked($block, $block["islamic_center_data"]["director_id"]);
                }
            });
            $data = ["accepted"=>self::returnArrayNonReserverdKeys($this->mapBlocked($accepted , "director")) ,
                "waiting"=>self::returnArrayNonReserverdKeys($this->mapBlocked($waiting , "director")) ,
                "rejected"=>self::returnArrayNonReserverdKeys($this->mapBlocked($rejected , "director"))];

            if($requested != null){
                $returned = [];
                foreach ($requested as $r) {
                    if($r == "accepted"){
                        array_push($returned , ["accepted"=>$data["accepted"]]);
                    }elseif($r == "rejected"){
                        array_push($returned , ["rejected"=>$data["rejected"]]);
                    }elseif($r == "waiting"){
                        array_push($returned , ["waiting"=>$data["waiting"]]);
                    }
                }
                return $returned ;

            }else{
                return $data ;
            }

        }else{
            return [] ;
        }

    }

    /**
    this function returns data in new array
     */
    public static function returnArrayNonReserverdKeys($array){
        $newArray = [];
        foreach($array as $arr){
            array_push($newArray , $arr);
        }
        return $newArray ;
    }
    /**
     * @param $blocked_dates
     * @param null $element
     * @return array
     */
    public function mapBlocked($blocked_dates ,$element = null){
        if($element == null){
            $blocked_dates = $blocked_dates->toArray();

            return array_map(function($item){
                return[
                    "record_id_block_date"=>$item["id"],
                    "islamic_center_data"=>IslamicCenter::whereid($item["ic_id"])->select("name","director_id")->first(),
                    "friday_data"=>Fridays::whereid($item["friday_id"])->select("date")->first(),
                    "status"=>$item["confirm"]
                ];
            },$blocked_dates);
        }else{
            if(!empty($blocked_dates)){

                return array_map(function($item){
                    return[
                        "record_id_block_date"=>$item["record_id_block_date"],
                        "visitor_name"=>AdBlockedDates::whereid($item["record_id_block_date"])->select("visitor_name")->first(),
                        "islamic_center_name"=>$item["islamic_center_data"]["name"],
                        "director_name"=>AssociateDirector::whereid($item["islamic_center_data"]["director_id"])->select("name")->first(),
                        "friday_date"=>$item["friday_data"],
                        "status"=>$this->filterStatus($item["status"])
                    ];
                },$blocked_dates);

            }else{
                return $blocked_dates ;
            }

        }

    }

    /**
     * @param $status
     * @return string
     * filter like angular huh for returning status
     */
    public function filterStatus($status){
        if($status == 1 ){
            return "waiting";
        }elseif($status == 2){
            return "accepted";
        }else{
            return "rejected";
        }
    }

    /**
     * update a record in adblockeddatestable
     * @return string
     */
    public function EditBlockedDatesStatus(){
        $id = Input::get("id");
        $status = Input::get("status");
        return AdBlockedDates::updateRecord($id , $status);
    }

}