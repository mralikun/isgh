<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Khateeb extends Model {

    protected $table = "khateeb";

    protected $fillable = ["id", "name", "email", "phone", "address", "bio","edu_background","member_isgh","post_code","picture_url"];

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     | @param $info
     | @return string
     | take the data khateeb sent to update his profile and add this data to his profile which returns
     | the result which is true or false .
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function addFields($info){
        // admin editing user
        if(isset($info["userID"])){
            $user_id =  $info["userID"];
            $user = User::whereid($user_id)->first();
            $khateeb_id = $user->user_id ;
            $khateeb_user_id = $user_id ;
        }else{
            $khateeb_id =  Auth::user()->user_id;
            $khateeb_user_id = Auth::user()->id ;
        }

        $khateeb = Khateeb::whereid($khateeb_id)->first();

        $khateeb->name = $info["name"];
        $khateeb->email = $info["email"];
        $khateeb->phone = $info["cell_phone"];
        $khateeb->address = $info["address"];
        $khateeb->bio = $info["bio"];
        $khateeb->edu_background = $info["edu_background"];
        $khateeb->post_code = $info["postal_code"];

        // checking if khateeb has old picture and replace it
        $old_picture_url = $khateeb->picture_url ;

        if(array_key_exists("profile_picture",$info)){

            // section for adding image
                $file = $info["profile_picture"];

                $filename = str_random(32) . $file->getClientOriginalName();

                $destination = public_path() . '/images/khateeb_pictures/';

                if($info["profile_picture"]->move($destination, $filename)){
                    $khateeb->picture_url = $filename;
                    $user = User::whereid($khateeb_user_id)->first() ;
                    $user->email = $khateeb->email ;
                    $user->save();
                    // checking if khateeb has old picture and replace it
                    $khateeb->save();
                    if($old_picture_url != ""){
                        \File::Delete(public_path()."/images/khateeb_pictures/".$old_picture_url);
                    }

                    return "true";
                }

        }else{
            $user =  User::whereid($khateeb_user_id)->first() ;
            $user->email = $khateeb->email ;
            $user->save();

            $khateeb->picture_url = $old_picture_url ;
            $khateeb->save();
            return "true";
        }

    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /*

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     | @param $khateeb_id
     | @return string
     | this section resbonsible for deleting khateebs
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function DeleteMembers($khateeb_id){
        $role = 2 ;
        $result = User::deleteUser($khateeb_id , 2 ) ;
        if($result != "false"){
            if(Khateeb::destroy($result)){
                return "true";
            }else{
                return "false" ;
            }
        }else{
            return "false";
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // here i will check if this khateebs gave another islamic center 7 and also the islamic center gave them 7
    // these function must take khateebs array and the current islamic center and return valid khateebs that can be added
    /**
     | @param $ad_id
     | @param $khateebs_available
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public static function Check_Khateeb_Gave_7_To_IslamicCenter($ad_id , $khateebs_available){

        $Available_Khateebs_For_This_IC = [];

        foreach($khateebs_available as $khateeb){

            $rules = ["ad_rate_khateeb"=>7 ,"khateeb_rate_ad"=>7 ];

            // here get me the distance between current khateeb and current islamic center
            $distance_between_khateeb_islamicCenter = DB::table('rating')->where('khateeb_id',"=", $khateeb)->where("ad_id" ,"=", $ad_id)->select("distance")->first();

            if($distance_between_khateeb_islamicCenter != null){
                $current_distance = $distance_between_khateeb_islamicCenter->distance ;

                // now get me the islamic center this khateeb give and take 7-7 ordered by distance
                $data = DB::table('rating')->where('khateeb_id',"=", $khateeb)->where("ad_id" ,"!=", $ad_id)->where($rules)->orderBy("distance","asc")->first();

                // now i will check if associated islamic center not blocked this friday
                if(!empty($data)){
                    $distance_other_ic = $data->distance ;
                    // check the distance with current ic with distance with the lowest distance
                    // if current distance less than or equal $distance_other_ic then apply matching
                    if($current_distance <= $distance_other_ic){
                        if(Schedule::Check_Khateeb_Gived_IC_From_4_Weeks($ad_id , $khateeb , Schedule::$current_friday) == true) {
                            array_push($Available_Khateebs_For_This_IC, $khateeb);
                        }
                    }
                    // else if current distance larger than $distance_other_ic then do not apply the matching process
                }else{
                    if(Schedule::Check_Khateeb_Gived_IC_From_4_Weeks($ad_id , $khateeb , Schedule::$current_friday) == true){
                        array_push($Available_Khateebs_For_This_IC,$khateeb);
                    }
                }
            }

        }

        // no khateebs available could be added because they have another islamic centers with the same rating 7-7 but lower distance
        if(!empty($Available_Khateebs_For_This_IC)){

            /**
             * now we have our array of available khateebs and we can add them to the array of assignment but we still need to check current places available in this0
             * current islamic center
             */
            $islamic_center_available_places = Schedule::Return_Associated_Islamic_Center($ad_id);
            $ic_id_available_places = Schedule::Get_Islamic_Center_Available_Places($islamic_center_available_places);


            // now i'm going to check how many khateebs i have and start assignment proccess
            $count = count($Available_Khateebs_For_This_IC);

            // in case of $count of khateebs i have more than available places it's good start assigning
            if($count > $ic_id_available_places){
                // here the count of khateebs available more than available places i have
                // get current friday
                $current_friday = Schedule::$current_friday ;
                // loop and assign , count may be 5 and available places only 3
                $schedule = new Schedule();

               for($i = 0 ; $i < $ic_id_available_places ; $i++){
                    $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id),"khateeb_id"=>$Available_Khateebs_For_This_IC[$i] ];
                    array_push(Schedule::$schedule,$assign) ;
                    $schedule->remove_khateeb_from_current_khateebs($Available_Khateebs_For_This_IC[$i]);
                    Schedule::update_Islamic_Center_Available_Places(Schedule::Return_Associated_Islamic_Center($ad_id),1);
                }

            }elseif($count == $ic_id_available_places){
                // get current friday
                    $current_friday = Schedule::$current_friday ;
                // loop and assign
                    $schedule = new Schedule();
                    foreach($Available_Khateebs_For_This_IC as $khateeb){
                        $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id),"khateeb_id"=>$khateeb ];
                        array_push(Schedule::$schedule,$assign) ;
                        $schedule->remove_khateeb_from_current_khateebs($khateeb);
                        Schedule::update_Islamic_Center_Available_Places($islamic_center_available_places,1);
                    }
            }else{
                // here we are lower than what we want now i have to add what i have in the
                // know i will go to 4-7 and make same process
                // get data and assiging khateebs rated this islamic center from 4-7 and he rated the from 4-7
                // First here i must assign the khateebs to the islamic center
                    $current_friday = Schedule::$current_friday ;
                    $schedule = new Schedule();
                    for($i = 0 ; $i < $count ; $i++){
                        $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id),"khateeb_id"=>$Available_Khateebs_For_This_IC[$i] ];
                        array_push(Schedule::$schedule,$assign) ;
                        $schedule->remove_khateeb_from_current_khateebs($Available_Khateebs_For_This_IC[$i]);
                    }

                // second reduce available places in islamic center by the count
                    Schedule::update_Islamic_Center_Available_Places($islamic_center_available_places,$count);

                // how much khateebs we need in the next step
                    $remainder = $ic_id_available_places-$count ;

                // here i will search in the other khateebs within my static variable khateebs after removing added
                    self::Get_Khateebs_Gave_this_Islamic_Center_4_7($ad_id , $remainder );
            }
        }else{
            self::Get_Khateebs_Gave_this_Islamic_Center_4_7($ad_id );
        }
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @param $remainder
     * @param $ad_id
     * this function responsible for getting khateebs gived islamic center between 4-7 and islamic center between 4-7 and not 7 - 7
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function Get_Khateebs_Gave_this_Islamic_Center_4_7($ad_id ,$remainder = null ){

        if(is_null($remainder)){
            $islamic_center_available_places = Schedule::Return_Associated_Islamic_Center($ad_id);
            $remainder = Schedule::Get_Islamic_Center_Available_Places($islamic_center_available_places);
        }

        $shortest_khateebs_to_islamic_center = [];
        $shortest_khateebs_to_islamic_center_alternative = [];
        // return available khateebs in this friday not assigned to any islamic center
        $khateebs = Schedule::return_array(Schedule::$khateebs , "khateeb_id");

        foreach($khateebs as $khateeb){

            $rules = ["ad_rate_khateeb"=>[4-7] ,"khateeb_rate_ad"=>[4,7] ];

            // this is the available khateebs this friday not assigned until now to islamic centers
            // now get me the distance between this khateeb and this ad

            $distance_between_khateeb_islamicCenter = DB::table('rating')->where('khateeb_id',"=", $khateeb)->where("ad_id" ,"=", $ad_id)->select("distance")->latest()->first();

            if($distance_between_khateeb_islamicCenter != null) {
                $distance = $distance_between_khateeb_islamicCenter->distance;

                // now get me all khateebs gave any other islamic center 4-6 and islamic center gave him 4-6 but not 7 - 7
                $data = DB::table('rating')
                    ->where('khateeb_id', "=", $khateeb)
                    ->where("ad_id", "!=", $ad_id)
                    ->whereBetween('khateeb_rate_ad', array(4, 7))
                    ->whereBetween('ad_rate_khateeb', array(4, 7))->
                    where(function ($query) {
                        $query->where('khateeb_rate_ad', '!=', 7)
                            ->orWhere('ad_rate_khateeb', '!=', 7);
                    })->orderBy("distance", "asc")->first();

                // now check if minimum path to islamic center and not to his islamic center
                if (!empty($data)) {
                    $distance_new_islamic_center = $data->distance;
                    /**
                     * | if $distance < $distance_new_islamic_center then this islamic center best for him
                     * | if $distance > $distance_new_islamic_center then do not add him there is other islamic center best for him
                     */
                    if ($distance <= $distance_new_islamic_center) {
                        if(Schedule::Check_Khateeb_Gived_IC_From_4_Weeks($ad_id , $khateeb , Schedule::$current_friday) == true) {
                            array_push($shortest_khateebs_to_islamic_center, $khateeb);
                        }
                    } else {
                        if(Schedule::Check_Khateeb_Gived_IC_From_4_Weeks($ad_id , $khateeb , Schedule::$current_friday) == true) {
                            array_push($shortest_khateebs_to_islamic_center_alternative, $khateeb);
                        }

                    }
                }else{
                    array_push($shortest_khateebs_to_islamic_center, $khateeb);
                }
            }
        }

        if(!empty($shortest_khateebs_to_islamic_center)){

            // count available khateebs
                $count = count($shortest_khateebs_to_islamic_center);
            // if the array have alot of records i only want some of them
                if($count < $remainder){

                    // here we are lower than what we want now i have to add what i have in the
                    // know i will go to 4-7 and make same process
                    // get data and assiging khateebs rated this islamic center from 1-3 and he rated the from 1-3
                    // First here i must assign the khateebs to the islamic center
                    $current_friday = Schedule::$current_friday ;
                    $schedule = new Schedule();

                    for($i = 0 ; $i < $count ; $i++){
                        $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id),"khateeb_id"=>$shortest_khateebs_to_islamic_center[$i] ];
                        array_push(Schedule::$schedule,$assign) ;
                        // second reduce available places in islamic center by the count
                        Schedule::update_Islamic_Center_Available_Places(Schedule::Return_Associated_Islamic_Center($ad_id),1);
                        $schedule->remove_khateeb_from_current_khateebs($shortest_khateebs_to_islamic_center[$i]);
                    }

                    // how much khateebs we need in the next step
                    $remainder = $remainder-$count ;

                    // here i will search in the other khateebs within my static variable khateebs after removing added

                    self::Get_Khateebs_Gave_Islamic_Center_7_1_and_ic_7_4($ad_id ,$remainder );

                }elseif($count == $remainder){

                    // get current friday
                    $current_friday = Schedule::$current_friday ;
                    // loop and assign
                    $schedule = new Schedule();

                    foreach($shortest_khateebs_to_islamic_center as $khateeb){
                        $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id),"khateeb_id"=>$khateeb ];
                        array_push(Schedule::$schedule,$assign) ;
                        $schedule->remove_khateeb_from_current_khateebs($khateeb);
                    }
                // here mean $count > $remainder
                }else{

                    // $count > $remainder array of khateebs more than what i need
                    $current_friday = Schedule::$current_friday ;
                    // loop and assign , count may be 5 and available places only 3
                    $schedule = new Schedule();

                    for($i = 0 ; $i < $remainder ; $i++){
                        $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id) , "khateeb_id"=>$shortest_khateebs_to_islamic_center[$i] ];
                        array_push(Schedule::$schedule,$assign) ;
                        $schedule->remove_khateeb_from_current_khateebs($shortest_khateebs_to_islamic_center[$i]);
                        Schedule::update_Islamic_Center_Available_Places(Schedule::Return_Associated_Islamic_Center($ad_id),1);
                    }

                }

        }elseif(!empty($shortest_khateebs_to_islamic_center_alternative)){

            // count available khateebs
                $count = count($shortest_khateebs_to_islamic_center_alternative);
                // if the array have alot of records i only want some of them
                if($count < $remainder){
                    // here we are lower than what we want now i have to add what i have in the
                    // know i will go to 4-7 and make same process
                    // get data and assiging khateebs rated this islamic center from 1-3 and he rated the from 1-3
                    // First here i must assign the khateebs to the islamic center
                    $current_friday = Schedule::$current_friday ;

                    for($i = 0 ; $i < $count ; $i++){
                        $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id),"khateeb_id"=>$shortest_khateebs_to_islamic_center_alternative[$i] ];
                        array_push(Schedule::$schedule,$assign) ;
                        // second reduce available places in islamic center by the count
                        Schedule::update_Islamic_Center_Available_Places($shortest_khateebs_to_islamic_center_alternative[$i],1);
                    }

                    // how much khateebs we need in the next step
                    $remainder = $remainder-$count ;
                    $schedule = new Schedule();
                    $schedule->remove_khateeb_from_current_khateebs($shortest_khateebs_to_islamic_center_alternative);
                    // here i will search in the other khateebs within my static variable khateebs after removing added

                    self::Get_Khateebs_Gave_Islamic_Center_7_1_and_ic_7_4($ad_id ,$remainder );

                }elseif($count == $remainder){
                    // get current friday
                    $current_friday = Schedule::$current_friday ;
                    // loop and assign
                    $schedule = new Schedule();

                    foreach($shortest_khateebs_to_islamic_center as $khateeb){
                        $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id),"khateeb_id"=>$khateeb ];
                        array_push(Schedule::$schedule,$assign) ;
                        $schedule->remove_khateeb_from_current_khateebs($khateeb);
                    }
                    // here mean $count > $remainder
                }else{
                    // $count > $remainder array of khateebs more than what i need
                    $current_friday = Schedule::$current_friday ;
                    // loop and assign , count may be 5 and available places only 3
                    $schedule = new Schedule();

                    for($i = 0 ; $i < $remainder ; $i++){
                        $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id) , "khateeb_id"=>$shortest_khateebs_to_islamic_center_alternative[$i] ];
                        array_push(Schedule::$schedule,$assign) ;
                        $schedule->remove_khateeb_from_current_khateebs($shortest_khateebs_to_islamic_center_alternative[$i]);
                        Schedule::update_Islamic_Center_Available_Places(Schedule::Return_Associated_Islamic_Center($ad_id),1);
                    }
                }

            }else{

                self::Get_Khateebs_Gave_Islamic_Center_7_1_and_ic_7_4($ad_id,$remainder);
            }

    }

    private static function Get_Khateebs_Gave_Islamic_Center_7_1_and_ic_7_4($ad_id ,$remainder = null ){

        if(is_null($remainder)){
            $islamic_center_available_places = Schedule::Return_Associated_Islamic_Center($ad_id);
            $remainder = Schedule::Get_Islamic_Center_Available_Places($islamic_center_available_places);
        }

        $shortest_khateebs_to_islamic_center = [];
        $shortest_khateebs_to_islamic_center_alternative = [];
        // return available khateebs in this friday not assigned to any islamic center
        $khateebs = Schedule::return_array(Schedule::$khateebs , "khateeb_id");

        foreach($khateebs as $khateeb){
            // this is the available khateebs this friday not assigned until now to islamic centers
            // now get me the distance between this khateeb and this ad
            $distance_between_khateeb_islamicCenter = DB::table('rating')->where('khateeb_id',"=", $khateeb)->where("ad_id" ,"=", $ad_id)->select("distance")->latest()->first();
            if($distance_between_khateeb_islamicCenter != null){
                $distance = $distance_between_khateeb_islamicCenter->distance ;

                // now get me all khateebs gave any other islamic center 1-6 and islamic center gave him 4-6 but not 7 - 7
                $data = DB::table('rating')
                    ->where('khateeb_id',"=", $khateeb)
                    ->where("ad_id" ,"!=", $ad_id)
                    ->whereBetween('khateeb_rate_ad', array(1,7))
                    ->whereBetween('ad_rate_khateeb', array(4,7))->
                    where(function($query)
                    {
                        $query->where('khateeb_rate_ad', '!=', 7)
                            ->orWhere('ad_rate_khateeb', '!=', 7);
                    })->orderBy("distance","asc")->first();

                // now check if minimum path to islamic center and not to his islamic center
                if(!empty($data)){
                    $distance_new_islamic_center = $data->distance ;
                    /**
                    | if $distance < $distance_new_islamic_center then this islamic center best for him
                    | if $distance > $distance_new_islamic_center then do not add him there is other islamic center best for him
                     */
                    if($distance <= $distance_new_islamic_center){
                        if(Schedule::Check_Khateeb_Gived_IC_From_4_Weeks($ad_id , $khateeb , Schedule::$current_friday) == true) {
                            array_push($shortest_khateebs_to_islamic_center, $khateeb);
                        }
                    }else{
                        if(Schedule::Check_Khateeb_Gived_IC_From_4_Weeks($ad_id , $khateeb , Schedule::$current_friday) == true) {
                            array_push($shortest_khateebs_to_islamic_center_alternative, $data);
                        }
                    }
                }else{
                    array_push($shortest_khateebs_to_islamic_center_alternative, $data);
                }
            }

        }

        if(!empty($shortest_khateebs_to_islamic_center)){
            // count available khateebs
            $count = count($shortest_khateebs_to_islamic_center);
            // if the array have alot of records i only want some of them
            if($count < $remainder){
                // here we are lower than what we want now i have to add what i have in the
                // know i will go to 4-7 and make same process
                // get data and assiging khateebs rated this islamic center from 1-3 and he rated the from 1-3
                // First here i must assign the khateebs to the islamic center
                $current_friday = Schedule::$current_friday ;
                for($i = 0 ; $i < $count ; $i++){
                    $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id),"khateeb_id"=>$shortest_khateebs_to_islamic_center[$i] ];
                    array_push(Schedule::$schedule,$assign) ;
                    // second reduce available places in islamic center by the count
                    Schedule::update_Islamic_Center_Available_Places($shortest_khateebs_to_islamic_center[$i],1);
                }

                // how much khateebs we need in the next step
                $remainder = $remainder-$count ;
                $schedule = new Schedule();
                $schedule->remove_khateeb_from_current_khateebs($shortest_khateebs_to_islamic_center);
                // here i will search in the other khateebs within my static variable khateebs after removing added
                self::Get_Khateebs_Gave_Islamic_Center_7_1_and_ic_7_1($ad_id , $remainder);

            }elseif($count == $remainder){
                // get current friday
                $current_friday = Schedule::$current_friday ;
                // loop and assign
                $schedule = new Schedule();

                foreach($shortest_khateebs_to_islamic_center as $khateeb){
                    $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id),"khateeb_id"=>$khateeb ];
                    array_push(Schedule::$schedule,$assign) ;
                    $schedule->remove_khateeb_from_current_khateebs($khateeb);
                }
                // here mean $count > $remainder
            }else{
                // $count > $remainder array of khateebs more than what i need
                $current_friday = Schedule::$current_friday ;
                // loop and assign , count may be 5 and available places only 3
                $schedule = new Schedule();

                for($i = 0 ; $i < $remainder ; $i++){
                    $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id) , "khateeb_id"=>$shortest_khateebs_to_islamic_center[$i] ];
                    array_push(Schedule::$schedule,$assign) ;
                    $schedule->remove_khateeb_from_current_khateebs($shortest_khateebs_to_islamic_center[$i]);
                    Schedule::update_Islamic_Center_Available_Places(Schedule::Return_Associated_Islamic_Center($ad_id),1);
                }
            }
        }else{

            self::Get_Khateebs_Gave_Islamic_Center_7_1_and_ic_7_1($ad_id,$remainder );
        }


    }


    private static function Get_Khateebs_Gave_Islamic_Center_7_1_and_ic_7_1($ad_id ,$remainder = null ){

        if(is_null($remainder)){
            $islamic_center_available_places = Schedule::Return_Associated_Islamic_Center($ad_id);
            $remainder = Schedule::Get_Islamic_Center_Available_Places($islamic_center_available_places);
        }

        $shortest_khateebs_to_islamic_center = [];
        $shortest_khateebs_to_islamic_center_alternative = [];
        // return available khateebs in this friday not assigned to any islamic center
        $khateebs = Schedule::return_array(Schedule::$khateebs , "khateeb_id");

        foreach($khateebs as $khateeb){
            // this is the available khateebs this friday not assigned until now to islamic centers
            // now get me the distance between this khateeb and this ad
            $distance_between_khateeb_islamicCenter = DB::table('rating')->where('khateeb_id',"=", $khateeb)->where("ad_id" ,"=", $ad_id)->select("distance")->latest()->first();
            if($distance_between_khateeb_islamicCenter != null){
                $distance = $distance_between_khateeb_islamicCenter->distance ;

                // now get me all khateebs gave any other islamic center 1-6 and islamic center gave him 4-6 but not 7 - 7
                $data = DB::table('rating')
                    ->where('khateeb_id',"=", $khateeb)
                    ->where("ad_id" ,"!=", $ad_id)
                    ->whereBetween('khateeb_rate_ad', array(1,7))
                    ->whereBetween('ad_rate_khateeb', array(1,7))->
                    where(function($query)
                    {
                        $query->where('khateeb_rate_ad', '!=', 7)
                            ->orWhere('ad_rate_khateeb', '!=', 7);
                    })->orderBy("distance","asc")->first();

                // now check if minimum path to islamic center and not to his islamic center
                if(!empty($data)){
                    $distance_new_islamic_center = $data->distance ;
                    /**
                    | if $distance < $distance_new_islamic_center then this islamic center best for him
                    | if $distance > $distance_new_islamic_center then do not add him there is other islamic center best for him
                     */
                    if($distance <= $distance_new_islamic_center){
                        if(Schedule::Check_Khateeb_Gived_IC_From_4_Weeks($ad_id , $khateeb , Schedule::$current_friday) == true) {
                            array_push($shortest_khateebs_to_islamic_center, $khateeb);
                        }
                    }else{
                        if(Schedule::Check_Khateeb_Gived_IC_From_4_Weeks($ad_id , $khateeb , Schedule::$current_friday) == true) {
                            array_push($shortest_khateebs_to_islamic_center_alternative, $data);
                        }
                    }
                }else{
                    array_push($shortest_khateebs_to_islamic_center_alternative, $data);
                }
            }
        }

        if(!empty($shortest_khateebs_to_islamic_center)){
            // count available khateebs
            $count = count($shortest_khateebs_to_islamic_center);
            // if the array have alot of records i only want some of them
            if($count < $remainder){
                // here we are lower than what we want now i have to add what i have in the
                // know i will go to 4-7 and make same process
                // get data and assiging khateebs rated this islamic center from 1-3 and he rated the from 1-3
                // First here i must assign the khateebs to the islamic center
                $current_friday = Schedule::$current_friday ;
                for($i = 0 ; $i < $count ; $i++){
                    $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id),"khateeb_id"=>$shortest_khateebs_to_islamic_center[$i] ];
                    array_push(Schedule::$schedule,$assign) ;
                    // second reduce available places in islamic center by the count
                    Schedule::update_Islamic_Center_Available_Places($shortest_khateebs_to_islamic_center[$i],1);
                }

                // how much khateebs we need in the next step
                $remainder = $remainder-$count ;
                $schedule = new Schedule();
                $schedule->remove_khateeb_from_current_khateebs($shortest_khateebs_to_islamic_center);
                // here i will search in the other khateebs within my static variable khateebs after removing added
                self::Get_Khateebs_Gave_Islamic_Center_0_7($ad_id,$remainder );
            }elseif($count == $remainder){
                // get current friday
                $current_friday = Schedule::$current_friday ;
                // loop and assign
                $schedule = new Schedule();

                foreach($shortest_khateebs_to_islamic_center as $khateeb){
                    $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id),"khateeb_id"=>$khateeb ];
                    array_push(Schedule::$schedule,$assign) ;
                    $schedule->remove_khateeb_from_current_khateebs($khateeb);
                }
                // here mean $count > $remainder
            }else{
                // $count > $remainder array of khateebs more than what i need
                $current_friday = Schedule::$current_friday ;
                // loop and assign , count may be 5 and available places only 3
                $schedule = new Schedule();

                for($i = 0 ; $i < $remainder ; $i++){
                    $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id) , "khateeb_id"=>$shortest_khateebs_to_islamic_center[$i] ];
                    array_push(Schedule::$schedule,$assign) ;
                    $schedule->remove_khateeb_from_current_khateebs($shortest_khateebs_to_islamic_center[$i]);
                    Schedule::update_Islamic_Center_Available_Places(Schedule::Return_Associated_Islamic_Center($ad_id),1);
                }

            }
        }else{

            self::Get_Khateebs_Gave_Islamic_Center_0_7($ad_id,$remainder);
        }
    }


    private static function Get_Khateebs_Gave_Islamic_Center_0_7($ad_id , $remainder = null){

        if(is_null($remainder)){
            $islamic_center_available_places = Schedule::Return_Associated_Islamic_Center($ad_id);
            $remainder = Schedule::Get_Islamic_Center_Available_Places($islamic_center_available_places);
        }

        $shortest_khateebs_to_islamic_center = [];
        $shortest_khateebs_to_islamic_center_alternative = [];
        // return available khateebs in this friday not assigned to any islamic center
        $khateebs = Schedule::return_array(Schedule::$khateebs , "khateeb_id");

        foreach($khateebs as $khateeb){
            // this is the available khateebs this friday not assigned until now to islamic centers
            // now get me the distance between this khateeb and this ad
            $distance_between_khateeb_islamicCenter = DB::table('rating')->where('khateeb_id',"=", $khateeb)->where("ad_id" ,"=", $ad_id)->select("distance")->latest()->first();
            if($distance_between_khateeb_islamicCenter != null){
                $distance = $distance_between_khateeb_islamicCenter->distance ;

                // now get me all khateebs gave any other islamic center 1-6 and islamic center gave him 4-6 but not 7 - 7
                $data = DB::table('rating')
                    ->where('khateeb_id',"=", $khateeb)
                    ->where("ad_id" ,"!=", $ad_id)
                    ->whereBetween('khateeb_rate_ad', array(0,7))
                    ->where('ad_rate_khateeb',"=", 0)->
                    where(function($query)
                    {
                        $query->where('khateeb_rate_ad', '!=', 7)
                            ->orWhere('ad_rate_khateeb', '!=', 7);
                    })->orderBy("distance","asc")->first();

                // now check if minimum path to islamic center and not to his islamic center
                if(!empty($data)){
                    $distance_new_islamic_center = $data->distance ;
                    /**
                    | if $distance < $distance_new_islamic_center then this islamic center best for him
                    | if $distance > $distance_new_islamic_center then do not add him there is other islamic center best for him
                     */
                    if($distance <= $distance_new_islamic_center){
                        if(Schedule::Check_Khateeb_Gived_IC_From_4_Weeks($ad_id , $khateeb , Schedule::$current_friday) == true) {
                            array_push($shortest_khateebs_to_islamic_center, $khateeb);
                        }
                    }else{
                        if(Schedule::Check_Khateeb_Gived_IC_From_4_Weeks($ad_id , $khateeb , Schedule::$current_friday) == true) {
                            array_push($shortest_khateebs_to_islamic_center_alternative, $data);
                        }
                    }
                }else{
                    array_push($shortest_khateebs_to_islamic_center_alternative, $data);
                }
            }

        }

        if(!empty($shortest_khateebs_to_islamic_center)){
            // count available khateebs
            $count = count($shortest_khateebs_to_islamic_center);
            // if the array have alot of records i only want some of them
            if($count < $remainder){
                // here we are lower than what we want now i have to add what i have in the
                // know i will go to 4-7 and make same process
                // get data and assiging khateebs rated this islamic center from 1-3 and he rated the from 1-3
                // First here i must assign the khateebs to the islamic center
                $current_friday = Schedule::$current_friday ;
                for($i = 0 ; $i < $count ; $i++){
                    $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id),"khateeb_id"=>$shortest_khateebs_to_islamic_center[$i] ];
                    array_push(Schedule::$schedule,$assign) ;
                    // second reduce available places in islamic center by the count
                    Schedule::update_Islamic_Center_Available_Places($shortest_khateebs_to_islamic_center[$i],1);
                }

                // how much khateebs we need in the next step
                $remainder = $remainder-$count ;
                $schedule = new Schedule();
                $schedule->remove_khateeb_from_current_khateebs($shortest_khateebs_to_islamic_center);
                // here i will search in the other khateebs within my static variable khateebs after removing added
                self::Get_Khateebs_Gave_Islamic_Center_7_0($ad_id,$remainder);
            }elseif($count == $remainder){
                // get current friday
                $current_friday = Schedule::$current_friday ;
                // loop and assign
                $schedule = new Schedule();

                foreach($shortest_khateebs_to_islamic_center as $khateeb){
                    $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id),"khateeb_id"=>$khateeb ];
                    array_push(Schedule::$schedule,$assign) ;
                    $schedule->remove_khateeb_from_current_khateebs($khateeb);
                }
                // here mean $count > $remainder
            }else{
                // $count > $remainder array of khateebs more than what i need
                $current_friday = Schedule::$current_friday ;
                // loop and assign , count may be 5 and available places only 3
                $schedule = new Schedule();

                for($i = 0 ; $i < $remainder ; $i++){
                    $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id) , "khateeb_id"=>$shortest_khateebs_to_islamic_center[$i] ];
                    array_push(Schedule::$schedule,$assign) ;
                    $schedule->remove_khateeb_from_current_khateebs($shortest_khateebs_to_islamic_center[$i]);
                    Schedule::update_Islamic_Center_Available_Places(Schedule::Return_Associated_Islamic_Center($ad_id),1);
                }
            }

        }else{
            self::Get_Khateebs_Gave_Islamic_Center_7_0($ad_id,$remainder );
        }
    }

    private static function Get_Khateebs_Gave_Islamic_Center_7_0($ad_id , $remainder = null){
        if(is_null($remainder)){
            $islamic_center_available_places = Schedule::Return_Associated_Islamic_Center($ad_id);
            $remainder = Schedule::Get_Islamic_Center_Available_Places($islamic_center_available_places);
        }
        $shortest_khateebs_to_islamic_center = [];
        $shortest_khateebs_to_islamic_center_alternative = [];
        // return available khateebs in this friday not assigned to any islamic center
        $khateebs = Schedule::return_array(Schedule::$khateebs , "khateeb_id");

        foreach($khateebs as $khateeb){
            // this is the available khateebs this friday not assigned until now to islamic centers
            // now get me the distance between this khateeb and this ad
            $distance_between_khateeb_islamicCenter = DB::table('rating')->where('khateeb_id',"=", $khateeb)->where("ad_id" ,"=", $ad_id)->select("distance")->latest()->first();
            if($distance_between_khateeb_islamicCenter != null){
                $distance = $distance_between_khateeb_islamicCenter->distance ;

                // now get me all khateebs gave any other islamic center 1-6 and islamic center gave him 4-6 but not 7 - 7
                $data = DB::table('rating')
                    ->where('khateeb_id',"=", $khateeb)
                    ->where("ad_id" ,"!=", $ad_id)
                    ->whereBetween('ad_rate_khateeb', array(0,7))
                    ->where('khateeb_rate_ad',"=", 0)->
                    where(function($query)
                    {
                        $query->where('khateeb_rate_ad', '!=', 7)
                            ->orWhere('ad_rate_khateeb', '!=', 7);
                    })->orderBy("distance","asc")->first();

                // now check if minimum path to islamic center and not to his islamic center
                if(!empty($data)){
                    $distance_new_islamic_center = $data->distance ;
                    /**
                    | if $distance < $distance_new_islamic_center then this islamic center best for him
                    | if $distance > $distance_new_islamic_center then do not add him there is other islamic center best for him
                     */
                    if($distance <= $distance_new_islamic_center){
                        if(Schedule::Check_Khateeb_Gived_IC_From_4_Weeks($ad_id , $khateeb , Schedule::$current_friday) == true) {
                            array_push($shortest_khateebs_to_islamic_center, $khateeb);
                        }
                    }else{
                        if(Schedule::Check_Khateeb_Gived_IC_From_4_Weeks($ad_id , $khateeb , Schedule::$current_friday) == true) {
                            array_push($shortest_khateebs_to_islamic_center_alternative, $data);
                        }
                    }
                }else{
                    array_push($shortest_khateebs_to_islamic_center_alternative, $data);
                }
            }

        }

        if(!empty($shortest_khateebs_to_islamic_center)){

            // count available khateebs
            $count = count($shortest_khateebs_to_islamic_center);
            // if the array have alot of records i only want some of them
            if($count < $remainder){
                // here we are lower than what we want now i have to add what i have in the
                // know i will go to 4-7 and make same process
                // get data and assiging khateebs rated this islamic center from 1-3 and he rated the from 1-3
                // First here i must assign the khateebs to the islamic center
                $current_friday = Schedule::$current_friday ;
                $schedule = new Schedule();
                for($i = 0 ; $i < $count ; $i++){
                    $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id),"khateeb_id"=>$shortest_khateebs_to_islamic_center[$i] ];
                    array_push(Schedule::$schedule,$assign) ;
                    // second reduce available places in islamic center by the count
                    Schedule::update_Islamic_Center_Available_Places(Schedule::Return_Associated_Islamic_Center($ad_id),1);
                    $schedule->remove_khateeb_from_current_khateebs($shortest_khateebs_to_islamic_center[$i]);
                }

                // how much khateebs we need in the next step
                $remainder = $remainder-$count ;
                // here i will search in the other khateebs within my static variable khateebs after removing added


            }elseif($count == $remainder){
                // get current friday
                $current_friday = Schedule::$current_friday ;
                // loop and assign
                $schedule = new Schedule();

                foreach($shortest_khateebs_to_islamic_center as $khateeb){
                    $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id),"khateeb_id"=>$khateeb ];
                    array_push(Schedule::$schedule,$assign) ;
                    Schedule::update_Islamic_Center_Available_Places(Schedule::Return_Associated_Islamic_Center($ad_id),1);
                    $schedule->remove_khateeb_from_current_khateebs($khateeb);
                }
                // here mean $count > $remainder
            }else{
                // $count > $remainder array of khateebs more than what i need
                $current_friday = Schedule::$current_friday ;
                // loop and assign , count may be 5 and available places only 3
                $schedule = new Schedule();

                for($i = 0 ; $i < $remainder ; $i++){
                    $assign = ["friday_id"=>$current_friday, "ic_id"=>Schedule::Return_Associated_Islamic_Center($ad_id) , "khateeb_id"=>$shortest_khateebs_to_islamic_center[$i] ];
                    array_push(Schedule::$schedule,$assign) ;
                    $schedule->remove_khateeb_from_current_khateebs($shortest_khateebs_to_islamic_center[$i]);
                    Schedule::update_Islamic_Center_Available_Places(Schedule::Return_Associated_Islamic_Center($ad_id),1);
                }
            }

        }else{

        }
    }

}


