<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class cycle extends Model {

    protected $table = "cycle";

    protected $fillable = ["id", "start_date","end_date"];

    /**
     * @param $startDate
     * @param $endDate
     * @return string
     * for DRY i made this function
     */
    private static function saveCycle($startDate , $endDate){
        $cycle = new cycle();
        $cycle->start_date = $startDate ;
        $cycle->end_date = $endDate ;
        if($cycle->save()){
            return $cycle->id;
        }else{
            return "false";
        }
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return string
     * create new cycle before creating new one check if there is existing cycle
     * existing cycle get end date and check if it is finished or not
     */
    public static function CreateNewCycle($startDate , $endDate){
        $latest_cycle = cycle::latest()->first();

        if(empty($latest_cycle)){
            $result = self::saveCycle($startDate , $endDate);
            return $result ;
        }else{
            $latest_cycle_end_date = $latest_cycle->end_date ;

            // check if the end_date of the last cycle is older
            if (strtotime($latest_cycle_end_date) < time()) {
                // okay create the new cycle
                return $result = self::saveCycle($startDate , $endDate);
            } else {
                // cycle did not finished yet do not create new one
                return "Cycle did not finished yet";
            }
        }
    }


    // return current cycle
    public static function currentCycle(){
        $cycle = Cycle::latest()->first();
        return $cycle->id ;
    }

}
