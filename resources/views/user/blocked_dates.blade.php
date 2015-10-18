@extends("templates.master")


@section("navigation")

<li><a href="/user/dates">Available/Blocked Dates</a></li>
<li><a href="/user/rating">Rating</a></li>
<li><a href="/user/edit_profile">Update Profile Information</a></li>

@stop


@section("pageTitle")

Available/Blocked Dates

@stop


@section("content")

<h3>Name</h3>
<span class="hint"><strong>Check the box for all dates you're willing to give khutbah in and leave the box blank if you don't want to give any khutbah at that day , <mark>You must check at least 1 box.</mark></strong></span>
<form>
    <div class="dates-calendar">

       <div class="date">
           <div class="date-content">
               <h4>Friday</h4>
               <h5>12 / 11 / 2015</h5>
               <input type="checkbox">
           </div>
       </div>
       <div class="date">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox">
           </div>
       </div>
       <div class="date">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox">
           </div>
       </div>
       <div class="date">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox">
           </div>
       </div>
       <div class="date">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox">
           </div>
       </div>
       <div class="date">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox">
           </div>
       </div>
       <div class="date">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox">
           </div>
       </div>
       <div class="date">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox">
           </div>
       </div>
       <div class="date">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox">
           </div>
       </div>
       <div class="date">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox">
           </div>
       </div>
       <div class="date">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox">
           </div>
       </div>
       <div class="date">
           <div class="date-content">
               <h4>Friday</h4>
               <input type="checkbox">
           </div>
       </div>
       

    </div>
    <input type="submit" value="Submit" class="btn btn-primary pull-right">
</form>

@stop