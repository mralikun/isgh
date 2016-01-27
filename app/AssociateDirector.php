<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AssociateDirector extends Model
{

    protected $table = "associate_director";

    protected $fillable = ["id", "name", "email", "phone", "address", "bio", "post_code","reviewerschedule"];

    public function islamicCenter(){
        return $this->hasOne("App\IslamicCenter");
    }

    /**
     * @param $info
     * @return string
     * add fields to the associative director if userID then he is admin adding data to ad else ad trying to edit his data
     */
    public static function addFields($info){

        if(isset($info["userID"])){
            $user_id =  $info["userID"];
        }else{
            $user_id =  Auth::user()->id;
        }

        $user = User::whereid($user_id)->first();
        $ad_id = $user->user_id ;
        $ad = AssociateDirector::whereid($ad_id)->first();

        $ad->name = $info["name"];
        $ad->email = $info["email"];
        $ad->phone = $info["cell_phone"];
        $ad->address = $info["address"];
//        $ad->bio = $info["bio"];
        $ad->post_code = $info["postal_code"];

        $user = User::whereid($user_id)->first() ;
        $user->email = $ad->email ;
        $user->save();

        $ad->save();
        return "true";
    }

    /**
     * @param $ad_id
     * @return string
     * delete members
     */
    public static function DeleteMembers($ad_id){
        $role = 3 ;
        $result = User::deleteUser($ad_id , $role );
        if($result != "false"){
            if(AssociateDirector::destroy($result)){
                return "true";
            }else{
                return "false" ;
            }
        }else{
            return "false";
        }
    }

}
