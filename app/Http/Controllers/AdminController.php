<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AdminController extends Controller {

	public function Create_Islamic_Center(){
        return view("admin.create_islamic_center");
    }

    public function Create_members(){
        return view("admin.create_members");
    }
    public function Manage_schedule(){
        return view("admin.schedule");
    }

    public function Edit_Members_Information(){
        return view("admin.edit_members");
    }

    public function Edit_Islamic_Center_Information(){
        return view("admin.edit_islamic_center");
    }
}
