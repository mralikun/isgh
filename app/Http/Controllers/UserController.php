<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserController extends Controller {

	public function getIslamicCenterBlockedDates(){
        return view("user.blocked_dates");
    }

	public function getRatingPage(){
        return view("user.rating");
    }

    public function getEditProfile(){
        return view("user.edit_profile");
    }
}
