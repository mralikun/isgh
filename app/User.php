<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Hash;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'username', 'password', "role_id", "user_id"];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    public function addnewuser($input)
    {
        $role = $input["role"] ;
        switch ($role) {
            case "khateeb" :
                return $this->run_add_khateeb($input);
                break;
            case "admin":
                return $admin_id = $this->run_add_admin($input);
                break;
            case "ad":
               return $ad_id = $this->run_add_ad($input);
                break;
            default :
                return "true";

        }
    }


    private function add_new_user($username , $password , $role , $user_id){
        $user = new User();
        $user->username = $username ;
        $password = Hash::make($password);
        $user->password = $password ;
        $user->user_id = $user_id ;
        $user->role_id =  $role ;
        $user->save();
        return "false" ;
    }

    private function run_add_admin($input){
        $admin_id = $this->add_new_admin($input);
        if($admin_id == "true"){
            return "true" ;
        }else{
            $username = $input["username"];
            $password = $input["password"];
            $role = 1 ;
            $this->add_new_user($username , $password , $role , $admin_id);
            return "false";
        }
    }

    private function run_add_khateeb($input){
        $khateeb_id = $this->add_new_khateeb($input);
        if($khateeb_id == "true"){
            return "true" ;
        }else{
            $username = $input["username"];
            $password = $input["password"];
            $role = 2 ;
            $this->add_new_user($username , $password , $role , $khateeb_id);
            return "false";
        }
    }


    private function run_add_ad($input){
        $ad_id = $this->add_new_ad($input);
        if($ad_id == "true"){
            return "true" ;
        }else{
            $username = $input["username"];
            $password = $input["password"];
            $role = 3 ;
            $this->add_new_user($username , $password , $role , $ad_id);
            return "false";
        }
    }


    private function add_new_khateeb($input)
    {
        $khateeb = new Khateeb();
        $khateeb->member_isgh = $input["isgh_member"];
        if ($khateeb->save()) {
            return $khateeb->id;
        } else {
            return "true";
        }
    }

    private function add_new_ad($input)
    {
        $ad = new AssociateDirector();
        $ad->reviewer = $input["reviewer"] ;
        if ($ad->save()) {
            return $ad->id;
        } else {
            return "true";
        }
    }

    private function add_new_admin($input)
    {
        $admin = new Admin();
        if ($admin->save()) {
            return $admin->id;
        } else {
            return "true";
        }
    }


    public static function getUserData($user_id , $role_id){
        switch($role_id){
            case 1 :
                return Admin::whereid($user_id)->first();
                break ;
            case 2 :
                return Khateeb::whereid($user_id)->first();
                break ;
            case 3 :
                return AssociateDirector::whereid($user_id)->first();
                break ;
        }
    }

    public static function validateAllFields($input){
        $values = array_values($input);
        $keys = array_keys($input);
        $errors = array();
        for($i=0 ; $i<sizeof($input) ; $i++){
            if($values[$i]==""){
                array_push($errors , $keys[$i]);
            }
        }
        if(empty($errors)){
            return "true";
        }else{
            return $errors ;
        }
    }

    // getting role for this user in my website
    public static function getRole(){
        $role = Auth::user()->role_id ;
        switch($role){
            case 1 :
                return "admin";
                break;
            case 2 :
                return "khateeb";
                break ;
            case 3 :
                return "ad";
                break;
            default:
                return "false";
        }
    }


}
