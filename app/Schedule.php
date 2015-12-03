<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model {

    // this variable hold khateebs that available in the current friday.
    public static $khateebs ;
    public static $islamic_centers ;
    public static $schedule =[] ;
    public static $current_friday =2 ;
    public static $islamic_center_available_places = [];


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Start Schedule
        public function startSchedule($friday_id){
            $this::Add_Islamic_Center_Available_Places($friday_id);
            $data = $this->Initial($friday_id);
            // now i returned all the available khateebs + all available Islamic Centers
            // now i returned all islamic centers I'am going to make a call to a function loop through islamic centers
            // loop through  islamic centers  and start assigning
            $this->Islamic_Center_Looping();
            return self::$schedule ;
        }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     | @param $friday_id
     | @return array|string of khateebs and islamic centers
     */
        private function Initial($friday_id){
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
        private function Islamic_Center_Looping(){
            $islamic_centers = self::$islamic_centers ;
            // loop through all islamic centers and get me all khateebs in our array gived this islamic center 7 and vise versa
            foreach($islamic_centers as $islamic_center){
                // get me all khateebs gived this islamic center 7 and islamic center gived them 7 also
                   $data = Rating::Get_all_khateebs_givied_Islamic_Center_7($islamic_center->id , self::$khateebs);
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

}
