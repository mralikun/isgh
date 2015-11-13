<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Khateeb extends Model {

    protected $table = "khateeb";

    protected $fillable = ["id", "name", "email", "phone", "address", "bio","edu_background","member_isgh","post_code","picture_url"];

    /**
     * @param $info
     * @return string
     * take the data khateeb sent to update his profile and add this data to his profile which returns
     * the result which is true or false .
     */
    public static function addFields($info){
        // admin editing user
        if(isset($info["userID"])){
            $user_id =  $info["userID"];
            $user = User::whereid($user_id)->first();
            $khateeb_id = $user->user_id ;
            $khateeb_user_id = $user_id ;
        }else{
            $khateeb_id =  Auth::user()->user_id;
            $khateeb_user_id = Auth::user()->id ;
        }

        $khateeb = Khateeb::whereid($khateeb_id)->first();

        $khateeb->name = $info["name"];
        $khateeb->email = $info["email"];
        $khateeb->phone = $info["cell_phone"];
        $khateeb->address = $info["address"];
        $khateeb->bio = $info["bio"];
        $khateeb->edu_background = $info["edu_background"];
        $khateeb->post_code = $info["postal_code"];

        // checking if khateeb has old picture and replace it
        $old_picture_url = $khateeb->picture_url ;

        if(array_key_exists("profile_picture",$info)){

            // section for adding image
                $file = $info["profile_picture"];

                $filename = str_random(32) . $file->getClientOriginalName();

                $destination = public_path() . '/images/khateeb_pictures/';

                if($info["profile_picture"]->move($destination, $filename)){
                    $khateeb->picture_url = $filename;
                    $user = User::whereid($khateeb_user_id)->first() ;
                    $user->email = $khateeb->email ;
                    $user->save();
                    // checking if khateeb has old picture and replace it
                    $khateeb->save();
                    if($old_picture_url != ""){
                        \File::Delete(public_path()."/images/khateeb_pictures/".$old_picture_url);
                    }

                    return "true";
                }

        }else{
            $user =  User::whereid($khateeb_user_id)->first() ;
            $user->email = $khateeb->email ;
            $user->save();

            $khateeb->picture_url = $old_picture_url ;
            $khateeb->save();
            return "true";
        }

    }

    /**
     * @param $khateeb_id
     * @return string
     * this section resbonsible for deleting khateebs
     */
    public static function DeleteMembers($khateeb_id){
        $role = 2 ;
        $result = User::deleteUser($khateeb_id , 2 ) ;
        if($result != "false"){
            if(Khateeb::destroy($result)){
                return "true";
            }else{
                return "false" ;
            }
        }else{
            return "false";
        }
    }
    

}
