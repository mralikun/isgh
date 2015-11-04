@extends("templates.master")


@section("navigation")

<li><a href="/user/dates">Available/Blocked Dates</a></li>
<li><a href="/user/rating">Rating</a></li>
<li><a href="/user/edit_profile">Update Profile Information</a></li>
<li><a href="/auth/logout">Logout</a></li>

@stop


@section("pageTitle")

Available/Blocked Dates

@stop


@section("content")

<h3>Name</h3>
<span class="hint"><strong>Check the box for all dates you're willing to give khutbah in and leave the box blank if you don't want to give any khutbah at that day , <mark>You must check at least 1 box.</mark></strong></span>
<div class="options">
    
    <button class="btn btn-isgh select-all">Select All</button>
    <button class="btn btn-isgh unselect-all">Unselect All</button>
    <button class="btn btn-isgh reverse-select">Reverse Selection</button>
    
</div>
<form id="blocked-dates-form">
  <!-- Token Mismatch Exception !!!!!! -->
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="dates-calendar">

       <div class="date" id="1">
           <div class="date-content">
               <h4>Friday</h4>
               <h5>12 / 11 / 2015</h5>
               <input type="checkbox" disabled>
           </div>
       </div>
       <div class="date" id="2">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox" disabled>
           </div>
       </div>
       <div class="date" id="3">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox" disabled>
           </div>
       </div>
       <div class="date" id="4">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox" disabled>
           </div>
       </div>
       <div class="date" id="5">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox" disabled>
           </div>
       </div>
       <div class="date" id="6">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox" disabled>
           </div>
       </div>
       <div class="date" id="7">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox" disabled>
           </div>
       </div>
       <div class="date" id="8">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox" disabled>
           </div>
       </div>
       <div class="date" id="9">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox" disabled>
           </div>
       </div>
       <div class="date" id="10">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox" disabled>
           </div>
       </div>
       <div class="date" id="11">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox" disabled>
           </div>
       </div>
       <div class="date" id="12">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox" disabled>
           </div>
       </div>
       

    </div>
    <input type="submit" value="Submit" class="btn btn-primary pull-right" ng-disabled="dates.available.length == 0">
</form>

@stop