<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model {

    // this variable hold khateebs that available in the current friday.
    public static $khateebs ;
    public static $islamic_centers ;
    public static $schedule =[] ;
    public static $schedule2 =[] ;
    public static $current_friday = 2 ;
    public static $islamic_center_available_places = [];
    protected $table = "schedule";
    protected $fillable = ["ad_id","khateeb_id","friday_id"];


    public static function start(){
        $cycle = cycle::latest()->first();
        $cycle_id = $cycle->id ;

        $fridays = Fridays::wherecycle_id($cycle_id)->get();
        foreach($fridays as $friday){
            self::$khateebs = "";
            self::$islamic_centers = "";
            self::$islamic_center_available_places = [];
            self::$current_friday = $friday->id;
            self::startSchedule($friday->id);
        }
        self::run_CheckingExistenseOfCycle($cycle_id);
        return self::$schedule ;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Start Schedule
        public static function startSchedule($friday_id){
            self::Add_Islamic_Center_Available_Places($friday_id);
            self::Initial($friday_id);
            // now i returned all the available khateebs + all available Islamic Centers
            // now i returned all islamic centers I'am going to make a call to a function loop through islamic centers
            // loop through  islamic centers  and start assigning
            self::Islamic_Center_Looping();
        }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     | @param $friday_id
     | @return array|string of khateebs and islamic centers
     */
        private static function Initial($friday_id){
            // first return all khateebs available in this friday
            $khateebs = Khateebselectedfridays::Get_Khateebs_Choosed_that_Friday($friday_id);
            if(empty($khateebs)){
                return "Sorry there is no Khateebs Available in this Friday";
            }

            // Second return all Islamic Centers have no blocked dates and available this friday
            $islamic_centers = AdBlockedDates::islamic_Centers_Available_This_Friday($friday_id);
            if(empty($islamic_centers)){
                return "Sorry there is no Khateebs Available in this Friday";
            }
            // now create new array and assign data to it
            self::$khateebs = $khateebs ;
            self::$islamic_centers = $islamic_centers ;
        }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     // this function when called it fill all the array $islamic_center_available_places with available places
     // start it in the beginning of each friday

        public static function Add_Islamic_Center_Available_Places($friday_id){
            $available_islamic_centers = AdBlockedDates::islamic_Centers_Available_This_Friday($friday_id);
            foreach($available_islamic_centers as $ic){
                $add_speeches_available = ["islamic_center_id"=>$ic->id ,"speech_num"=>$ic->speech_num  ];
                array_push(self::$islamic_center_available_places,$add_speeches_available);
            }
            return self::$islamic_center_available_places ;
        }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // decrease islamic center available places by one

        public static function update_Islamic_Center_Available_Places($islamic_center_id , $decrease_amount){
            // here before calling this function i will ensure there is a place that i can decrease it
            $i = 0 ;
            foreach(self::$islamic_center_available_places as $array){
                if($array["islamic_center_id"] == $islamic_center_id){
                    self::$islamic_center_available_places[$i]["speech_num"] = self::$islamic_center_available_places[$i]["speech_num"]-$decrease_amount ;
                }
                $i++;
            }
        }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // decrease islamic center available places by one

        public static function Get_Islamic_Center_Available_Places($islamic_center_id){
            // here before calling this function i will ensure there is a place that i can decrease it
            $i = 0 ;
            foreach(self::$islamic_center_available_places as $array){
                if($array["islamic_center_id"] == $islamic_center_id){
                    return self::$islamic_center_available_places[$i]["speech_num"];
                }
                $i++;
            }
        }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Return Associated Islamic Center for this ad

        public static function Return_Associated_Islamic_Center($ad_id){
           /* $ad_id = User::whereid($user_id)->first();
            $ad_id = $ad_id->user_id ;*/
            // here before calling this function i will ensure there is a place that i can decrease it
           $ic_id = IslamicCenter::wheredirector_id($ad_id)->select("id")->first();
            return $ic_id->id ;
        }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Return Associated ad for this islamic center

        public static function Return_Associated_ad($ic_id){
            // here before calling this function i will ensure there is a place that i can decrease it
            $ad_id = IslamicCenter::whereid($ic_id)->select("director_id")->first();
            $ad  = User::whereuser_id($ad_id->director_id)->whererole_id(3)->first();
            return $ad->id ;
        }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     | @param $object
     | @param $element_to_get_its_data
     | @return array from the object you gived it
     */
        public static function return_array($object ,$element_to_get_its_data){
            // create array to hold the data
            $array = [];

            // push data to the array from the object
            if(!empty($object)){
                foreach($object as $id){
                    array_push($array,$id->$element_to_get_its_data);
                }
            }
            return $array ;
        }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     | @return mixed
     | here i'm looping throw islamic centers and assign
     */
        private static function Islamic_Center_Looping(){
            $islamic_centers = self::$islamic_centers ;
            if(!empty($islamic_centers)){
                // loop through all islamic centers and get me all khateebs in our array gived this islamic center 7 and vise versa
                foreach($islamic_centers as $islamic_center){
                    // get me all khateebs gived this islamic center 7 and islamic center gived them 7 also
                    Rating::Get_all_khateebs_givied_Islamic_Center_7($islamic_center->id , self::$khateebs);
                }
            }

        }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     | @return mixed
     | here i'm looping throw islamic centers and assign
     */
        public static function Check_Khateeb_Gived_IC_From_4_Weeks($ad_id , $khateeb_id , $friday_id){
            $allowed = true ;
            if(!empty(self::$schedule )){
                foreach(self::$schedule as $s){
                    // if this khateeb gived this islamic center before
                    if($khateeb_id == $s["khateeb_id"] and self::Return_Associated_Islamic_Center($ad_id) == $s["ic_id"]){
                        if(($friday_id - $s["friday_id"]) < 4 ){
                            $allowed = false ;
                        }
                    }
                }
                return $allowed ;
            }else{
                return $allowed ;
            }

        }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // within every friday when we are assigning khateebs to islamic center ex 1 we must remove him from khateebs because in this friday
    // he will give khutbah .
    public function remove_khateeb_from_current_khateebs($var){

        if(!empty($var)){
            if(is_numeric($var)){
                for($i = 0 ; $i<sizeof(self::$khateebs) ; $i++){
                    if(self::$khateebs[$i]->khateeb_id  == $var){
                        unset(self::$khateebs[$i]);
                    }
                }
            }else{
                for($i = 0 ; $i<sizeof(self::$khateebs) ; $i++){
                    if(in_array( self::$khateebs[$i]->khateeb_id , $var)){
                        unset(self::$khateebs[$i]);
                    }
                }
            }
            $reindex = array_values(self::$khateebs); //normalize index
            self::$khateebs = $reindex; //update variable
        }

    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // here I will check if this cycle have schedule already have schedule created for it

    private static function run_CheckingExistenseOfCycle($cycle_id){
        $old_schedule = Schedule::wherecycle_id($cycle_id)->get();
        if(!empty($old_schedule)){
            foreach($old_schedule as $schedule){
                Schedule::whereid($schedule->id)->delete();
            }
        }

        foreach(self::$schedule as $schedule){
            $me = new Schedule();
            $me->friday_id = $schedule["friday_id"];
            $me->khateeb_id = $schedule["khateeb_id"];
            $me->ic_id = $schedule["ic_id"];
            $me->cycle_id = $cycle_id ;
            $me->save();
        }
    }


}
