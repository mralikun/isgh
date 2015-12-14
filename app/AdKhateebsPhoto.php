<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AdKhateebsPhoto extends Model {

	//
    protected $table ="ad_khateebs_photo";

    protected $fillable = ["id","ad_id","photo_url"];

    public static function khateebsForRating(){
        $ad_khateebs = self::adKhateebs();
        // get all khateebs in array structure
        $khateebs = Khateeb::where("name","!=","")->get()->toArray();
        // check if empty

        if(!empty($ad_khateebs)){
            $khateebs_ad = self::mappingad($ad_khateebs);
            if(!empty($khateebs)){
               $khateebs = self::mappingkhateeb($khateebs);
                return array_merge($khateebs_ad , $khateebs);
            }else{
                return $khateebs_ad ;
            }
        }else{
            return self::mappingkhateeb($khateebs) ;
        }
    }

    // get me all ad's that are khateebs
    private static function adKhateebs(){
//        if(Auth::user()->role_id == 3){
//            $all_ad_pictures = AdKhateebsPhoto::where("ad_id","!=",Auth::user()->user_id)->get()->toArray();
//        }else{
            $all_ad_pictures = AdKhateebsPhoto::all()->toArray();
       // }
        // khaateebs selected this cycle
        $khateebsSelectedThisCycle = Khateebselectedfridays::wherecycle_id(cycle::currentCycle())->select("khateeb_id")->get();
        //converting them to array
        $khateebsSelectedThisCycle = Schedule::return_array($khateebsSelectedThisCycle , "khateeb_id");
        // array to hold them
        $ad_khateebs = [];

        if(!empty($khateebsSelectedThisCycle)){
            foreach($all_ad_pictures as $ad){
                if(in_array( User::getWhateveruser_id_from_user_table($ad["ad_id"] , 3 ) , $khateebsSelectedThisCycle)){
                    array_push($ad_khateebs , $ad);
                }
            }
        }
        return $ad_khateebs ;
    }

    // mapp ad and return his data in deferent mapping structure
    public static function mappingad($ad_khateebs){
        return array_map(function($element){
            return [
                "id" => User::getWhateveruser_id_from_user_table($element["ad_id"] , 3 ),// here return the id from the users table
                "picture_url" => $element["photo_url"],
                "name"=>User::getUserData($element["ad_id"] , 3)->name,
                "ad_rate_khateeb"=>Rating::returnRateRow(Auth::user()->user_id ,User::getWhateveruser_id_from_user_table($element["ad_id"] , 3 ))
            ];
        },$ad_khateebs);
    }

    // mapp khateeb and return his data in deferent mapping structure
    public static function mappingkhateeb($ad_khateebs){
        return array_map(function($element){
            return [
                "id" => User::getWhateveruser_id_from_user_table($element["id"] , 2 ),
                "picture_url" => $element["picture_url"],
                "name"=>$element["name"],
                "ad_rate_khateeb"=>Rating::returnRateRow(Auth::user()->user_id ,User::getWhateveruser_id_from_user_table($element["id"] , 2 ))
            ];
        },$ad_khateebs);
    }

    // return all khateebs that rated by this asoociative director
    public static function getKhateebsRated(){
        return $khateebs = self::adKhateebs();
        return array_map(function($element){
            return [
                "id" => User::getWhateveruser_id_from_user_table($element["id"] , 2 ),
                "picture_url" => $element["picture_url"],
                "name"=>$element["name"],
                "ad_rate_khateeb"=>"sad"
            ];
        },$khateebs);
        return $khateebs ;
    }

}
