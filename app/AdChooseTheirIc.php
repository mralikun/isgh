<?php namespace App;

use Illuminate\Database\Eloquent\Model;

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

}
