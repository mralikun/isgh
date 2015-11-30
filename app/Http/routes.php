<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'Navigator@index');
Route::get('/home', 'Navigator@index');


/**
 * Authentication Routes Created by taylor and i make some changes to all registration files
 * route auth/login
 * route auth/register
 */

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);


// routes related to admin
// route for creating new islamic center
Route::get('/admin/islamic_center/create/{id?}', 'AdminController@Create_Islamic_Center');

// route for creating new members
Route::get('/admin/members/create', 'AdminController@Create_members');

// route for managing schedule
Route::get('/admin/schedule', 'AdminController@Manage_schedule');

// route for edit information about members
Route::get('/admin/members/edit', 'AdminController@Edit_Members_Information');

// route for edit information about islamic center
Route::get('/admin/islamic_center/edit', 'AdminController@Edit_Islamic_Center_Information');

// here the route for creating members from "create_members.blade.php" in admin
Route::post("/admin/createUser",'AdminController@createUser');

// here the route for creating islamic center
Route::post("/admin/createIslamicCenter/{id?}",'AdminController@createIslamicCenter');

// here the route for Deleting Users
Route::delete("/admin/DeleteKhateeb/{id}",'AdminController@DeleteKhateeb');

// here the route for Deleting Users
Route::delete("/admin/DeleteAd/{id}",'AdminController@DeleteAd');

// here the route for Deleting Users
Route::delete("/admin/DeleteIslamicCenter/{id}",'AdminController@DeleteIslamicCenter');

// here the route for getting cell phone for director to create islamic center
Route::post("/user/getCellPhone",'AdminController@getCellPhone');

// this route responsible for taking start date and the number of months and then generate new cycle and
// generate fridays to start the system .
Route::post("/admin/create_cycle",'AdminController@start_New_Cycle');

// return Add new cycle page
Route::get("/admin/cycle",'AdminController@getCyclePage');
Route::get("/admin/startNewCycle/{date}/{months}",'AdminController@start_New_Cycle');

// routes related to User
// route for creating getting blocked dates
Route::get('/user/dates', 'UserController@getIslamicCenterBlockedDates');

// route for creating getting rating page
Route::get('/user/rating', 'UserController@getRatingPage');

// route for editing user profile
Route::get('/user/edit_profile/{id?}', 'UserController@getEditProfile');

// route for getting user profile
Route::get('/user/profile', 'UserController@getProfile');

// route for receiving data from update profile page and save user data
Route::post('/user/updateProfile/{id?}', 'UserController@updateProfile');

// route for returning first ten records to the rating page
Route::post('/user/startRate', 'UserController@startRate');

//route for submitting a rate
Route::post('/user/rate', 'UserController@addRate');

// choosing
Route::post('/user/setAvailableDates', 'UserController@setDates');

Route::get("/when",function(){
    $schedule = new \App\Schedule;
    return $schedule->startSchedule(2);
});


        modified:   
        modified:   app/IslamicCenter.php
        modified:   app/Khateeb.php
        modified:   app/Khateebselectedfridays.php
        modified:   app/Rating.php
        modified:   database/migrations/2015_10_19_121133_create_rating_table.ph
p
        modified:   database/migrations/2015_10_20_114045_create_islamic_center_
table.php
        modified:   database/seeds/DatabaseSeeder.php