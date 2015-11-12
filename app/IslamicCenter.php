<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class IslamicCenter extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'islamic_center';

    public function Ad()
    {
        return $this->belongsTo('App\AssociateDirector');
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
       if($IslamicCenter->save($input)){
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


}
