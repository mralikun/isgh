<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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


    /**
     * @param $input
     * @return string
     * admin add new user khateeb , admin , ad
     * this calls a function responsible for run the add method for each role
     */
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

    /**
     * @param $input
     * @return string
     * this function responsible for adding new admin
     */
    private function run_add_admin($input){
        $admin_id = $this->add_new_admin($input);
        if($admin_id == "true"){
            return "true" ;
        }else{
            $user = new User();
            $user->username = $input["username"] ;
            $user->email = $input["email"] ;
            $password = Hash::make($input["password"]);
            $user->password = $password ;
            $user->user_id = $admin_id ;
            $user->role_id =  1 ;
            $user->save();
            return "false";
        }
    }

    /**
     * @param $input
     * @return string
     * this function responsible for adding new khateeb take's khateeb data
     */
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

    /**
     * @param $input
     * @return string
     * this function responsible for adding new ad take's ad data
     */
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

    /**
     * @param $input
     * @return string
     * this function responsible for new khateeb when admin create new khateeb
     */
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

    /**
     * @param $input
     * @return string
     * this function responsible for new ad when admin create new ad
     */
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

    /**
     * @param $input
     * @return string
     * this function responsible for new admin when admin create new admin
     */
    private function add_new_admin($input)
    {
        $admin = new Admin();
        if ($admin->save()) {
            return $admin->id;
        } else {
            return "true";
        }
    }

    /**
     * @param $user_id
     * @param $role_id
     * @return mixed
     * return user data used in all controllers
     */
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
    /**
     * @param $user_id
     * @param $role_id
     * @return mixed
     * return user data used in all controllers
     */
    public static function getWhateveruser_id_from_user_table($id , $role_id){
        $user = User::whereuser_id($id)->whererole_id($role_id)->first();
        return $user->id ;
    }

    /**
     * @param $email
     * this function takes email and validate if there is one email with this email with this data and user_id is different do not add it
     */
    protected static function checkEmail($email,$user_id ,$process){
            // we are editing user so we must check and we know we will find a row with this email we we will not find return okay
        if($process != null){
            $emails = User::select("id","user_id","email")->whereemail($email)->get();
            $checker = 0 ;
            $availability = 0 ;
            foreach($emails as $row){
                // if so the check if this email for this user by checking the id
                if($process == $row["id"]){
                    // here is the user and this is his email this email belongs to this user
                    $checker ++;
                }else{
                    $availability ++;
                }

            }
            if($checker == 0 && $availability == 0){
            // here we did not found any users with this email
                return "true";
            }elseif($availability > 0){
            // here we found that there are users have this email
                return "false";
            }elseif($checker == 1){
            // here we found that this user have this email and you can take it
                return "true";
            }else{
                return "false";
            }
        }else{
            // we are creating new user
            $emails = User::select("id","user_id","email")->whereemail($email)->get();
            $checker = 0 ;
            $availability = 0 ;
            foreach($emails as $row){
                // if so the check if this email for this user by checking the id
                if($user_id == $row["id"]){
                    $checker ++ ;
                }
            }

            if($checker == 0){
                return "true" ;
            }else{
                return "false";
            }
        }

    }

    /**
     * @param $input
     * @return array|string
     * validate all fields that there is no null in the data
     * $process if null
     */
    public static function validateAllFields($input , $process = null){
        // if $process = null we are creating new record
        if(isset($input["userID"])){
            $result = self::checkEmail($input["email"],$input["userID"],$process);
        }else{
            $result = self::checkEmail($input["email"],Auth::user()->user_id,$process);
        }

        $values = array_values($input);
        $keys = array_keys($input);
        $errors = [
            "missing"=>[],
            "email"=>false
        ];
        for($i=0 ; $i<sizeof($input) ; $i++){
            if($values[$i]==""){
                array_push($errors["missing"] , $keys[$i]);
            }
        }

        if(empty($errors["missing"]) && $result == "true"){
            return "true";
        }elseif(empty($errors["missing"]) && $result =="false"){
            return $errors ;
        }elseif(!empty($errors["missing"]) && $result =="false"){
            return $errors ;
        }elseif(!empty($errors["missing"]) && $result =="true"){
            $errors["email"]= true ;
            return $errors ;
        }else{
            return "false"  ;
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

    /**
     * Deleting user from the system
     */
    public static function deleteUser($user_id , $role){
        $user_id ;
        $user = User::where("id",$user_id)->where("role_id",$role)->first();
        $user_id = $user->user_id ;
        if(User::destroy($user->id)){
            return $user_id;
        }
        else{
            return "false";
        }
    }


    /**
     * @return array
     * this function for getting khateebs and ad's names associated with their id in user table
     */
    public static function getUserNames(){
        $khateebs_users = User::whererole_id(2)->get();
        $ads = User::whererole_id(3)->get();
        $all=["khateebs"=>[] , "ads"=>[]];

        // if no khateebs and no associative directors
        if(empty($khateebs_users) && empty($ads)){

        // if no khateebs but there are associative directories
        }elseif(empty($khateebs_users) && !empty($ads)){
            foreach($ads as $user_ad){
                $ad = AssociateDirector::whereid($user_ad->user_id)->where("email","!=","")->first();
                // if no ad added his email do not display him
                if(!empty($ad)) {
                    array_push($all["ads"], [$user_ad->id, $ad->name]);
                }else{
                    array_push($all["ads"], [$user_ad->id, $user_ad->username]);
                }
            }
        //  if no associative directories but there are khateebs
        }elseif(empty($ads) && !empty($khateebs_users)){
            foreach($khateebs_users as $user_khateeb){
                $khateeb = Khateeb::whereid($user_khateeb->user_id)->where("email","!=","")->first();
                // if no khateeb added his email do not display him
                if(!empty($khateeb)) {
                    array_push($all["khateebs"], [$user_khateeb->id, $khateeb->name]);
                }else{
                    array_push($all["khateebs"], [$user_khateeb->id, $user_khateeb->username]);
                }
            }
        // if there are khateebs and also associative directories
        }else{
            foreach($khateebs_users as $user_khateeb){
                $khateeb = Khateeb::whereid($user_khateeb->user_id)->where("email","!=","")->first();
                // if no khateeb added his email do not display him
                if(!empty($khateeb)){
                    array_push($all["khateebs"],[$user_khateeb->id , $khateeb->name]) ;
                }else{
                    array_push($all["khateebs"],[$user_khateeb->id , $user_khateeb->username]) ;
                }
            }

            foreach($ads as $user_ad){

                $ad = AssociateDirector::whereid($user_ad->user_id)->where("email", "!=", "")->first();
                // if no ad added his email do not display him
                if(!empty($ad)) {
                    array_push($all["ads"], [$user_ad->id, $ad->name]);
                }else{
                    array_push($all["ads"], [$user_ad->id, $user_ad->username]);
                }
            }
        }

        return $all ;
    }
}
