<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AssociateDirector extends Model
{

    protected $table = "associate_director";

    protected $fillable = ["id", "name", "email", "phone", "address", "bio", "post_code"];

    public static function addFields($info){

        $user_id =  $info["userID"];
        $user = User::whereid($user_id)->first();
        $ad_id = $user->user_id ;
        $ad = AssociateDirector::whereid($ad_id)->first();

        $ad->name = $info["name"];
        $ad->email = $info["email"];
        $ad->phone = $info["cell_phone"];
        $ad->address = $info["address"];
        $ad->bio = $info["bio"];
        $ad->post_code = $info["postal_code"];

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
