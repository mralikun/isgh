<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class IslamicCenter extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'islamic_center';

    public function Ad()
    {
        return $this->belongsTo('App\AssociateDirector',"director_id");
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'director_id', 'khutbah_start', "khutbah_end", "other_information",
                            "parking_information","country","city","address","postal_code","state","website"];

    /**
     * @param $input
     * @return string
     * this function takes the islamic center data from the admin and create new islamic center
     */
    public static function addNewIslamicCenter($input){
        $input["director_id"] = $input["director_name"];
       if(IslamicCenter::create($input)){
            return "true";
       }else{
            return "false";
       }
    }

    /**
     * @param $input
     * @param $id
     * @return string
     *  this function takes the islamic center data from the admin and edit new islamic center
     */
    public static function EditExistingIslamicCenter($input,$id){
        $IslamicCenter = IslamicCenter::whereid($id)->first();
        $input["director_id"] = $input["director_name"];
       if($IslamicCenter->update($input)){
            return "true";
       }else{
            return "false";
       }
    }


    /**
     * @param $islamic_center_id
     * @return string
     * delete islamic center
     */
    public static function DeleteMembers($islamic_center_id){
        if(IslamicCenter::destroy($islamic_center_id)){
            return "true";
        }else{
            return "false" ;
        }
    }


    /**
     * this function takes a date start or end time nad transfor it to 10:05:AM
     */
    public static function TransformDate($date){
        $date = new \DateTime($date);
        return $date->format('h:i a');
    }

    public static function return_islamic_centers_for_Rating(){
        $khateeb_id = Auth::user()->user_id ;
        $islamic_centers =  IslamicCenter::where("name","!=","")->where("director_id","!=",$khateeb_id)->select("id","name","director_id")->get()->toArray();

        return array_map(function($element){
            return [
                "id" => User::getWhateveruser_id_from_user_table($element["id"] , 3 ),// here return the id from the users table
                "director_id"=>$element["name"] ,
                "khateeb_rate_ad"=>Rating::returnRateRowIslamicCenter(User::getWhateveruser_id_from_user_table($element["director_id"] , 3 ),Auth::user()->id )
            ];
        },$islamic_centers);
    }

}
