<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Cycle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Rating extends Model {

    protected $table = "rating";

    protected $fillable = ["id", "ad_id", "khateeb_id", "ad_rate_khateeb", "khateeb_rate_ad", "cycle_id"];

    public static function addRate($user_who_rate_id , $user_who_rate_role , $rated_user , $rate_id){

        switch($user_who_rate_role){
            case 2 :
                $khateeb = Khateeb::whereid($user_who_rate_id)->first();
                $khateeb_address = $khateeb->post_code ;

                $ad = IslamicCenter::wheredirector_id($rated_user)->first();
                $ad_address = $ad->postal_code ;
                $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$khateeb_address&destinations=$ad_address&key=AIzaSyAreAoOFcm44KdplQ8GCWMox-3-QZlLcEA";
                $data   = @file_get_contents($url);
                $distance = json_decode($data)->rows[0]->elements[0]->distance->value;


                /* here we determined that khateeb is trying to rate ad
                * check if khateeb_id and ad_id is already added
                */
                // until getting cycle id
                $check_exsitence = Rating::where("ic_id","=",Schedule::Return_Associated_Islamic_Center($rated_user))->where("khateeb_id","=",User::getWhateveruser_id_from_user_table($user_who_rate_id,2))->latest()->first();

                if(!empty($check_exsitence)){
                    $rate = Rating::whereid($check_exsitence->id)->first();
                }else{
                    $rate = new Rating();
                }

                // get current cycle id
                $cycle = Cycle::latest()->first();
                $cycle_id= $cycle->id ;

                $rate->ic_id = Schedule::Return_Associated_Islamic_Center($rated_user) ;
                $rate->khateeb_id =  User::getWhateveruser_id_from_user_table($user_who_rate_id,2) ;
                $rate->khateeb_rate_ad = $rate_id ;
                $rate->cycle_id = $cycle_id ;
                $rate->distance = $distance ;
                if($rate->save()){
                    return "true";
                }else{
                    return "false" ;
                }

                break;
            case 3 :
                // now i'm ad going to rate khateeb
                $user = User::whereid($rated_user)->first();
                $rated_user = $user->user_id;

                    // now I'am going to rate khateeb
                if($user->role_id == 2){
                    $khateeb = Khateeb::whereid($rated_user)->first();
                    $khateeb_address = $khateeb->post_code ;

                    $ad = IslamicCenter::wheredirector_id($user_who_rate_id)->first();
                    $ad_address = $ad->postal_code ;
                    $url = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=$khateeb_address&destinations=$ad_address&mode=driving&language=en-EN&sensor=false";
                    $data   = @file_get_contents($url);
                    $distance = json_decode($data)->rows[0]->elements[0]->distance->value;

                    // until getting cycle id
                    $check_exsitence = Rating::where("ic_id","=",Schedule::Return_Associated_Islamic_Center($user_who_rate_id))->where("khateeb_id","=",User::getWhateveruser_id_from_user_table($rated_user,2))->latest()->first();
                    // here we determined that ad is trying to rate khateeb
                    if(!empty($check_exsitence)){
                        $rate = Rating::whereid($check_exsitence->id)->first();
                    }else{
                        $rate = new Rating();
                    }
                    $cycle = Cycle::latest()->first();
                    $cycle_id= $cycle->id ;
                    $rate->ic_id =  Schedule::Return_Associated_Islamic_Center($user_who_rate_id);
                    $rate->khateeb_id =  $user->id;
                    $rate->ad_rate_khateeb = $rate_id ;
                    $rate->cycle_id = $cycle_id ;
                    $rate->distance = $distance ;
                    if($rate->save()){
                        return "true";
                    }else{
                        return "false" ;
                    }
                }else{
                    // now I'am going to rate ad but he is khateeb
                    $khateeb = AssociateDirector::whereid($rated_user)->first();
                    $khateeb_address = $khateeb->post_code ;

                    $ad = IslamicCenter::wheredirector_id($user_who_rate_id)->first();
                    $ad_address = $ad->postal_code ;
                    $url = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=$khateeb_address&destinations=$ad_address&mode=driving&language=en-EN&sensor=false";
                    $data   = @file_get_contents($url);
                    $distance = json_decode($data)->rows[0]->elements[0]->distance->value;

                    // until getting cycle id
                    $check_exsitence = Rating::where("ic_id","=",Schedule::Return_Associated_Islamic_Center($user_who_rate_id))->where("khateeb_id","=",$user->id)->latest()->first();
                    // here we determined that ad is trying to rate khateeb
                    if(!empty($check_exsitence)){
                        $rate = Rating::whereid($check_exsitence->id)->first();
                    }else{
                        $rate = new Rating();
                    }
                    $cycle = Cycle::latest()->first();
                    $cycle_id= $cycle->id ;
                    $rate->ic_id =  Schedule::Return_Associated_Islamic_Center($user_who_rate_id);
                    $rate->khateeb_id =  $user->id;
                    $rate->ad_rate_khateeb = $rate_id ;
                    $rate->cycle_id = $cycle_id ;
                    $rate->distance = $distance ;
                    if($rate->save()){
                        return "true";
                    }else{
                        return "false" ;
                    }
                }
                break ;
            default :
                return "false" ;
        }
    }

    public static function addRateToIslamic_center($user_who_rate_id , $user_who_rate_role , $rated_user , $rate_id){
        // now this is the user who made rate from users table
            $user = AssociateDirector::whereid($user_who_rate_id)->first();
            $khateeb_address = $user->post_code ;

            $rated_ad = AssociateDirector::whereid($rated_user)->first();

            $ad = IslamicCenter::wheredirector_id($rated_user)->first();
            if(empty($ad)){
                return "You did not assign islamic center to this assiciate Director";
            }
            $ad_address = $ad->postal_code ;

            $url = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=$khateeb_address&destinations=$ad_address&mode=driving&language=en-EN&sensor=false";
            $data   = @file_get_contents($url);
            $distance = json_decode($data)->rows[0]->elements[0]->distance->value;

            // until getting cycle id
            $check_exsitence = Rating::where("ic_id","=",Schedule::Return_Associated_Islamic_Center($rated_ad->id))->where("khateeb_id","=",Auth::user()->id)->latest()->first();

            // here we determined that ad is trying to rate khateeb
            if(!empty($check_exsitence)){
                $rate = Rating::whereid($check_exsitence->id)->first();
            }else{
                $rate = new Rating();
            }
            $cycle = Cycle::latest()->first();
            $cycle_id= $cycle->id ;
            $rate->ic_id =  Schedule::Return_Associated_Islamic_Center($rated_ad->id);
            $rate->khateeb_id =  Auth::user()->id;
            $rate->khateeb_rate_ad = $rate_id ;
            $rate->cycle_id = $cycle_id ;
            $rate->distance = $distance ;
            if($rate->save()){
                return "true";
            }else{
                return "false" ;
            }
    }

    // return rate row for ad khateebs
    public static function returnRateRow($ad_id ,$khateeb_user_id){
        $cycle = Cycle::currentCycle();

        $row = Rating::whereic_id(Schedule::Return_Associated_Islamic_Center($ad_id))->wherekhateeb_id($khateeb_user_id)->wherecycle_id($cycle)->first();
        if(empty($row)){
            return null ;
        }else{
            return $row->ad_rate_khateeb ;
        }
    }

    // return rate row for islamic center
    public static function returnRateRowIslamicCenter( $ad_id , $khateeb_user_id ){
        $cycle = Cycle::currentCycle();

        $row = Rating::whereic_id(Schedule::Return_Associated_Islamic_Center($ad_id))->wherekhateeb_id($khateeb_user_id)->wherecycle_id($cycle)->first();
        if(empty($row)){
            return null ;
        }else{
            return $row->khateeb_rate_ad ;
        }
    }

    /**
     * @param $islamic_center_id
     * @param $khateebs_available
     *  return khateebs gived this islamic center 7 and islamic center gived them 7
     */
    public static function Get_all_khateebs_givied_Islamic_Center_7($islamic_center_id , $khateebs_available){

         // return The ad of the islamic center to get the rating
        $ad_id = IslamicCenter::whereid($islamic_center_id)->select("director_id")->first();

        $ad_id = $ad_id->director_id ;
        $user = User::whereuser_id($ad_id)->whererole_id(3)->first();

        if(!empty($user)) {

            $user_id = $user->id;

            // get the current cycle
            $cycle = Cycle::latest()->first();
            $cycle_id = $cycle->id;

            // check if this islamic center ad choosed this friday to give khutbah in it
            $ad_choose_fridays = AdChooseTheirIc::wherecycle_id($cycle_id)->wheread_id($ad_id)->wherefriday_id(Schedule::$current_friday)->get();

            $count = count($ad_choose_fridays);

            $schedule = new Schedule();
            // if we found that he choosed this friday in this cycle to give khutbah in his islamic center
            if ($count != 0) {

                $assign = ["friday_id" => Schedule::$current_friday, "ic_id" => Schedule::Return_Associated_Islamic_Center($ad_id), "khateeb_id" => $user_id, "role_id" => 3];
                array_push(Schedule::$schedule, $assign);
                // $schedule->remove_khateeb_from_current_khateebs($ad_id);
                Schedule::update_Islamic_Center_Available_Places($islamic_center_id, 1);
                $schedule->remove_khateeb_from_current_khateebs($user_id);
            }

        }
            $khateebs_available = Schedule::return_array($khateebs_available, "khateeb_id");

            $rules = ["ic_id" => Schedule::Return_Associated_Islamic_Center($ad_id), "ad_rate_khateeb" => 7, "khateeb_rate_ad" => 7];

            // now get me all khateebs gived this islamic center 7 and islamic center gived him 7
            $data = DB::table('rating')->whereIn('khateeb_id', $khateebs_available)->where($rules)->select("khateeb_id")->get();

            // here i will check if this khateebs gave another islamic center 7 and also the islamic center gave them 7
            // these function must take khateebs array and the current islamic center and return valid khateebs that can be added

            if(!empty($data)){
                $khateebs_available = Schedule::return_array($data , "khateeb_id");
                /**
                 * here counting if khateebs more than one to add him directly if more than one check
                 * if khateeb gave another islamic center the same rating
                 * and this islamic center available this friday
                 */
                $count = count($khateebs_available);
                /**
                 * if another khateeb give this islamic center 7 - 7 and vise versa
                 * another khateeb from the available khateebs gave this islamic center 7-7 and lower distance
                 * check how many available places in this islamic center
                 */

               Khateeb::Check_Khateeb_Gave_7_To_IslamicCenter($ad_id , $khateebs_available);

            }else{

                // here now no one gived this islamic center 7-7 now i have to to check between 4-7
                Khateeb::Get_Khateebs_Gave_this_Islamic_Center_4_7($ad_id);
            }

    }

}






