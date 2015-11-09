<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class Navigator extends Controller {

//	public function __construct()
//	{
//		$this->middleware('guest');
//	}

	public function index()
	{
        if (Auth::guest())
        {
            return view("auth.login");
        }


        if (Auth::user()->role_id == 1)
        {
            return redirect('/admin/members/create');
        }
        elseif (Auth::user()->role_id == 2 || Auth::user()->role_id == 3 )
        {
            return redirect('/user/profile');
        }else{
            return redirect("auth.login");
        }

	}
    
}
