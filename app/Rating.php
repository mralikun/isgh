<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model {

    protected $table = "rating";

    protected $fillable = ["id", "ad_id", "khateeb_id", "ad_rate_khateeb", "khateeb_rate_ad", "cycle_id"];

    public static function addRate($user_who_rate_id , $user_who_rate_role , $rated_user , $rate_id){

        switch($user_who_rate_role){
            case 2 :
                /* here we determined that khateeb is trying to rate ad
                * check if khateeb_id and ad_id is already added
                */
                // until getting cycle id
                $check_exsitence = Rating::where("ad_id","=",$rated_user)->where("khateeb_id","=",$user_who_rate_id)->latest()->first();

                if(!empty($check_exsitence)){
                    $rate = Rating::whereid($check_exsitence->id)->first();
                }else{
                    $rate = new Rating();
                }
                $rate->ad_id = $rated_user ;
                $rate->khateeb_id = $user_who_rate_id ;
                $rate->khateeb_rate_ad = $rate_id ;
                $rate->cycle_id = 1 ;
                if($rate->save()){
                    return "true";
                }else{
                    return "false" ;
                }

                break;
            case 3 :
                // until getting cycle id
                    $check_exsitence = Rating::where("ad_id","=",$user_who_rate_id)->where("khateeb_id","=",$rated_user)->latest()->first();
                // here we determined that ad is trying to rate khateeb
                if(!empty($check_exsitence)){
                    $rate = Rating::whereid($check_exsitence->id)->first();
                }else{
                    $rate = new Rating();
                }
                $rate->ad_id =  $user_who_rate_id;
                $rate->khateeb_id =  $rated_user;
                $rate->ad_rate_khateeb = $rate_id ;
                $rate->cycle_id = 1 ;
                if($rate->save()){
                    return "true";
                }else{
                    return "false" ;
                }
                break ;
            default :
                return "false" ;
        }
    }

}






