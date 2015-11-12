<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Fridays extends Model {

    protected $table = "fridays";

    protected $fillable = ["id", "date","cycle_id"];

    /**
     * @param $cycle
     * @param $start
     * @param $end
     * @return string
     * get all fridays within a period of time the cycle period and add this fridays to the database and assign to it the cycle_id
     */
    public static function addFridays($cycle , $start , $end){
        $cycle =  $cycle->id ;

        // strtotime transform the date to seconds from 1970
        $start = strtotime($start); // your start/end dates here
        $end = strtotime($end);

        $friday = strtotime("friday", $start);
        $array=[];
        while($friday <= $end) {
            array_push($array,date("Y-m-d", $friday));
            $friday = strtotime("+1 weeks", $friday);
        }

        $saved = [];
        for($i = 0 ; $i <sizeof($array) ; $i++){
            $object = new Fridays();
            $object->date = $array[$i];
            $object->cycle_id = $cycle ;
            $object->save();
        }
        return "true";

    }

}
