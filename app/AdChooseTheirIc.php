<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Cycle ;

class AdChooseTheirIc extends Model {

	protected $table ="ad_choose_their_ic";

    protected $fillable = ["id","ad_id","friday_id"];

    public static function getChoosenFridays($ad_id , $cycle_id){
        return AdChooseTheirIc::wheread_id($ad_id)->wherecycle_id($cycle_id)->select("friday_id")->get();
    }

    public static function UnChoosenFridays($ad_id , $cycle_id , $choosen_other , $fridays_his_islamic_center){
        // return fridays within this cycle did not choose at all
        if(!empty($choosen_other) && !empty($fridays_his_islamic_center)){
            $choosen_other = Schedule::return_array($choosen_other ,"friday_id");
            $fridays_his_islamic_center = Schedule::return_array($fridays_his_islamic_center ,"friday_id");

            return $fridays_available= Fridays::wherecycle_id($cycle_id)->whereNotIn('id', $choosen_other)->whereNotIn('id', $fridays_his_islamic_center)->select("id")->get();

        }elseif(!empty($choosen_other)){
            $choosen_other = Schedule::return_array($choosen_other ,"friday_id");

            return $fridays_available= Fridays::wherecycle_id($cycle_id)->whereNotIn('id', $choosen_other)->select("id")->get();

        }elseif(!empty($fridays_his_islamic_center)){
            $fridays_his_islamic_center = Schedule::return_array($fridays_his_islamic_center ,"friday_id");

            return $fridays_available= Fridays::wherecycle_id($cycle_id)->whereNotIn('id', $fridays_his_islamic_center)->select("id")->get();
        }

    }


    public static function AddFridays($fridays){
        $cycle = Cycle::latest()->first();
        $cycle_id = $cycle->id;
        $user_id = Auth::user()->user_id ;
        $ad_choose_fridays = AdChooseTheirIc::wherecycle_id($cycle_id)->wheread_id($user_id)->count();

        if($ad_choose_fridays == 0){
            foreach($fridays as $friday){
                // ksf abbreviation to khateeb selected fridays
                $ksf = new AdChooseTheirIc();
                $ksf->ad_id = $user_id ;
                $ksf->friday_id = $friday ;
                $ksf->cycle_id = $cycle_id ;
                $ksf->save() ;
            }
            return "true";
        }elseif($ad_choose_fridays > 0){

            // get all Selected Fridays
                $ad_choosen_dates = AdChooseTheirIc::wherecycle_id($cycle_id)->wheread_id($user_id)->select("id")->get();
            // Remove all fridays for this khateeb in this cycle
                foreach($ad_choosen_dates as $abd){
                    AdChooseTheirIc::whereid($abd->id)->delete();
                }
            // Add new records to the database
                foreach($fridays as $friday){
                    $abd = new AdChooseTheirIc();
                    $abd->ad_id = $user_id ;
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
