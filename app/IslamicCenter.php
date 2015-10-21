<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class IslamicCenter extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'islamic_center';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'director_id', 'khutbah_start', "khutbah_end", "other_information",
                            "parking_information","country","city","address","postal_code","state","website"];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    public static function addNewIslamicCenter($input){

        $input["director_id"] = $input["director_name"];
       if(IslamicCenter::create($input)){
            return "true";
       }else{
            return "false";
       }
    }

}
