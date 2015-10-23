<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AssociateDirector extends Model
{

    protected $table = "associate_director";

    protected $fillable = ["id", "name", "email", "phone", "address", "bio", "post_code"];

    public static function addFields($info){

        $user_id = Auth::user()->user_id ;
        $ad = AssociateDirector::whereid($user_id)->first();

        $ad->name = $info["name"];
        $ad->email = $info["email"];
        $ad->phone = $info["cell_phone"];
        $ad->address = $info["address"];
        $ad->bio = $info["bio"];
        $ad->post_code = $info["postal_code"];

        app/AssociateDirector.php
 app/Http/Controllers/UserController.php
 app/Khateeb.php


        $ad->save();
        return "true";
    }

}
