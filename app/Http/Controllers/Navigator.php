<?php namespace App\Http\Controllers;

class Navigator extends Controller {

	public function __construct()
	{
		$this->middleware('guest');
	}

	public function index()
	{
		return view('user.edit_profile');
	}
    
}
