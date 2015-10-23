<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Khateeb extends Model {

    protected $table = "khateeb";

    protected $fillable = ["id", "name", "email", "phone", "address", "bio","edu_background","member_isgh","post_code","picture_url"];

    public static function addFields($info){
        $user_id = Auth::user()->user_id ;
        $khateeb = Khateeb::whereid($user_id)->first();

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

                    // checking if khateeb has old picture and replace it
                    $khateeb->save();
                    if($old_picture_url != ""){
                        \File::Delete(public_path()."/images/khateeb_pictures/".$old_picture_url);
                    }

                    return "true";
                }

        }else{
            $khateeb->picture_url = $old_picture_url ;
            $khateeb->save();
            return "true";
        }


    }

}
