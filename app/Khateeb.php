<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Khateeb extends Model {

    protected $table = "khateeb";

    protected $fillable = ["id", "name", "email", "phone", "address", "bio","edu_background","member_isgh","post_code","picture_url"];

    public static function addFields($info){
        $user_id = Auth::user()->user_id ;
        $khateeb = Khateeb::whereid($user_id)->first();

        // checking if khateeb has old picture and replace it
        $old_picture_url = $khateeb->picture_url ;

        if($old_picture_url != ""){
            \File::Delete(public_path()."/images/khateeb_pictures/".$old_picture_url);
        }

        $khateeb->name = $info["name"];
        $khateeb->email = $info["email"];
        $khateeb->phone = $info["cell_phone"];
        $khateeb->address = $info["address"];
        $khateeb->bio = $info["bio"];
        $khateeb->edu_background = $info["edu_background"];
        $khateeb->post_code = $info["postal_code"];

        // section for adding image
        if ($info["profile_picture"]) {
            $file = $info["profile_picture"];

            $file_original_name = $file->getClientOriginalName();
            $filename = str_random(32) . $file->getClientOriginalName();

            $destination = public_path() . '/images/khateeb_pictures/';

            $slider = $info["profile_picture"]->getRealPath();

            if($info["profile_picture"]->move($destination, $filename)){
                $khateeb->picture_url = $filename;

                $khateeb->save();
                return "true";
            }
        }
    }

}
