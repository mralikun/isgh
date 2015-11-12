<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AdBlockedDates extends Model {

    protected $table = "ad_blocked_dates";

    protected $fillable= ["id","friday_id","ad_id"];

    public static function addBlockedDates($fridays , $user_id , $role){
        $islamicCenter_With_DirectorData = IslamicCenter::with("ad")->wheredirector_id($user_id)->first();

        $khateeb_row = AdBlockedDates::whereic_id($user_id)->first();
        /**
         * Now i will get the running cycle
         */
        $current_cycle = cycle::latest()->first();
        $cycle_id = $current_cycle->id ;

        /**
         * select all records in the database where cycle = current cycle and khateeb id = current khateeb id
         */
        $ad_blocked_dates_count = AdBlockedDates::wherecycle_id($cycle_id)->whereic_id($user_id)->count();

        if($ad_blocked_dates_count == 0){
            foreach($fridays as $friday){
                // ksf abbreviation to khateeb selected fridays
                $ksf = new AdBlockedDates();
                $ksf->ic_id = $islamicCenter_With_DirectorData->id ;
                $ksf->friday_id = $friday ;
                $ksf->cycle_id = $cycle_id ;
                $ksf->save() ;
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
            foreach($fridays as $friday){
                $abd = new AdBlockedDates();
                $abd->ic_id =  $islamicCenter_With_DirectorData->id ;
                $abd->friday_id = $friday ;
                $abd->cycle_id = $cycle_id ;
                $abd->save() ;
            }
            return "true";
        }else{
            return "false";
        }
    }

}
