<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Cycle;
use Illuminate\Support\Facades\DB;

class AdBlockedDates extends Model {

    protected $table = "ad_blocked_dates";

    protected $fillable= ["id","friday_id","ad_id"];

    public static function addBlockedDates($fridays , $user_id ,$names){
        $islamicCenter_With_DirectorData = IslamicCenter::with("ad")->whereid($user_id)->first();

        $khateeb_row = AdBlockedDates::whereic_id($user_id)->first();
        /**
         * Now i will get the running cycle
         */
        $current_cycle = Cycle::latest()->first();
        $cycle_id = $current_cycle->id ;

        /**
         * select all records in the database where cycle = current cycle and khateeb id = current khateeb id
         */
        $ad_blocked_dates_count = AdBlockedDates::wherecycle_id($cycle_id)->whereic_id($user_id)->count();

        if($ad_blocked_dates_count == 0){

            if(!empty($fridays )){

                foreach($fridays as $key=>$friday){
                    // ksf abbreviation to khateeb selected fridays
                    $ksf = new AdBlockedDates();
                    $ksf->ic_id = $user_id ;
                    $ksf->friday_id = $friday ;
                    $ksf->confirm = 1 ;
                    $ksf->visitor_name = $names[$key] ;
                    $ksf->cycle_id = $cycle_id ;
                    $ksf->save() ;
                }
            }
            return "true";
        }elseif($ad_blocked_dates_count > 0){

            // get all Selected Fridays
            $ad_blocked_dates = AdBlockedDates::wherecycle_id($cycle_id)->whereic_id($user_id)->select("id")->get();
            // Remove all fridays for this khateeb in this cycle
            foreach($ad_blocked_dates as $abd){
                AdBlockedDates::whereid($abd->id)->delete();
            }
            // Add new records to the database
            if(!empty($fridays)){
                foreach($fridays as $key=>$friday){
                    $abd = new AdBlockedDates();
                    $abd->ic_id = $user_id ;
                    $abd->friday_id = $friday ;
                    $abd->confirm = 1 ;
                    $abd->visitor_name = $names[$key] ;
                    $abd->cycle_id = $cycle_id ;
                    $abd->save() ;
                }
            }

            return "true";
        }else{
            return "false";
        }
    }

    /**
     * @param $friday_id
     * @return array $islamic_centers_available for this friday
     */
    public static function islamic_Centers_Available_This_Friday($friday_id){
        // here return the id's of all islamic centers that have blocked dates this friday and do not want khateeb in that day
        $islamic_centers_id = AdBlockedDates::wherefriday_id($friday_id)->whereconfirm(2)->select("ic_id")->get();

        // create array to hold the islamic centers that block this friday
        $islamic_centers_id_array = [];

        // push islamic centers to the array mainly to generate array
        if(!empty($islamic_centers_id)){
            foreach($islamic_centers_id as $id){
                array_push($islamic_centers_id_array,$id->ic_id);
            }
        }

        // here i want to get all islamic centers not in the array $islamic_centers_id_array

        $islamic_centers_available = $users = (array)DB::table('islamic_center')->whereNotIn('id',$islamic_centers_id_array )->select("id","speech_num")->get();

        return $islamic_centers_available ;
    }

    /**
     * @param $id
     * @param $status
     * @return string
     * updating a record only
     */
    public static function updateRecord($id , $status){
        $record = AdBlockedDates::whereid($id)->first();
        $record->confirm = $status ;
        if($record->update()){
            return "true";
        }else{
            return "false";
        }
    }

}