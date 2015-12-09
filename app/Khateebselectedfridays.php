<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Cycle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Khateebselectedfridays extends Model {

	protected $table = "khateebselectedfridays";

    protected $fillable= ["id","friday_id","khateeb_id","role_id"];

    /**
     * @param $fridays
     * @param $user_id
     * add available dates to khateeb
     */
    public static function addAvailableDates($fridays , $user_id , $role){

        $user = User::whereuser_id($user_id)->whererole_id($role)->first();
        $user_id =$user->id ;
        $khateeb_row = Khateebselectedfridays::wherekhateeb_id($user_id)->first();
        /**
         * Now i will get the running cycle
         */
       $current_cycle = cycle::latest()->first();
       $cycle_id = $current_cycle->id ;

        /**
         * select all records in the database where cycle = current cycle and khateeb id = current khateeb id
         */
        $khateeb_selected_fridays_count = Khateebselectedfridays::wherecycle_id($cycle_id)->wherekhateeb_id($user_id)->whererole_id($role)->count();

        if($khateeb_selected_fridays_count == 0){
            foreach($fridays as $friday){
                // ksf abbreviation to khateeb selected fridays
                $ksf = new Khateebselectedfridays();
                $ksf->khateeb_id = Auth::user()->id ;
                $ksf->role_id = $role ;
                $ksf->friday_id = $friday ;
                $ksf->cycle_id = $cycle_id ;
                $ksf->save() ;
            }
            return "true";
        }elseif($khateeb_selected_fridays_count > 0){
            // get all Selected Fridays
                $khateeb_selected_fridays = Khateebselectedfridays::wherecycle_id($cycle_id)->wherekhateeb_id($user_id)->whererole_id($role)->select("id")->get();
            // Remove all fridays for this khateeb in this cycle
            foreach($khateeb_selected_fridays as $ksf){
                Khateebselectedfridays::whereid($ksf->id)->delete();
            }
            // Add new records to the database
            foreach($fridays as $friday){
                $ksf = new Khateebselectedfridays();
                $ksf->khateeb_id = Auth::user()->id ;
                $ksf->role_id = $role ;
                $ksf->friday_id = $friday ;
                $ksf->cycle_id = $cycle_id ;
                $ksf->save() ;
            }
            return "true";
        }else{
            return "false";
        }
    }

    /**
     * @param $friday_id
     * get all khateebs available in this friday
     */
    public static function Get_Khateebs_Choosed_that_Friday($friday_id){
        return (array)DB::table('khateebselectedfridays')->where('friday_id','=', $friday_id)->select("khateeb_id","role_id")->get();
    }

}