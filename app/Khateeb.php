<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Khateeb extends Model {

    protected $table = "khateeb";

    protected $fillable = ["id", "name", "email", "phone", "address", "bio","edu_background","member_isgh","post_code","picture_url"];


}
