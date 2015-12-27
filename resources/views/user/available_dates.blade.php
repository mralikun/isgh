@extends("templates.master")

@section("navigation")
<li><a href="/user/profile">View Profile</a></li>
<li><a href="/user/dates">Available Dates</a></li>
@if($role == 3)
<li><a href="/user/BlockedDates">Blocked Dates</a></li>
@endif
<li><a href="/user/rating">Rating</a></li>
<li><a href="/user/edit_profile">Update Profile Information</a></li>
@if(isset($reviewer) and $reviewer)
<li><a href="/admin/schedule">Review Schedule</a></li>
@endif

@if(isset($admin) and $admin)
<li><a href="/admin/members/create">Create new members</a></li>
<li><a href="/admin/islamic_center/create">Create islamic center</a></li>
@endif
<li><a href="/auth/logout">Logout</a></li>
@stop

@section("pageTitle")

Available Dates

@stop


@section("content")
<?php 

    function Map($arr , $cb){
        $mapped = [];
        foreach($arr as $a){
            array_push($mapped , $cb($a));
        }
        return $mapped;
    }

    $choosen = Map($fridays_choosen , function($fr){
        return $fr->friday_id;
    });
?>

<h3>{{$name}}</h3>
@if($role == 2)
<span class="hint"><strong>Check the box for all dates you're willing to give khutbah in and leave the box blank if you don't want to give any khutbah at that day , <mark>You must check at least 1 box.</mark></strong></span>
<div class="options">
    
    <button class="btn btn-isgh select-all">Select All</button>
    <button class="btn btn-isgh unselect-all">Unselect All</button>
    <button class="btn btn-isgh reverse-select">Reverse Selection</button>
    
</div>
@endif

@if($role == 2)
<form id="blocked-dates-form">
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="dates-calendar">

       @foreach($fridays as $friday)
       @if(in_array($friday->id , $choosen))
        <div class="date available" id="{{$friday->id}}">
            <div class="date-content">
               <h4>Friday</h4>
               <h5>{{$friday->date}}</h5>
            </div>
        </div>
       @else
        <div class="date" id="{{$friday->id}}">
            <div class="date-content">
               <h4>Friday</h4>
               <h5>{{$friday->date}}</h5>
            </div>
        </div>
       
       @endif
       @endforeach
       
    </div>
    <input type="submit" value="Submit" class="btn btn-primary pull-right" ng-disabled="dates.available.length == 0">
</form>

@else
<div class="text-center">
   <h4 class="hint"><strong>Where would you like to give khutbah ?</strong></h4>
    <a href="/user/ad/same_islamic_center" class="btn btn-isgh">My Own Islamic Center</a><a href="/user/ad/other_islamic_centers" class="btn btn-isgh">Other Islamic Centers</a>
</div>

@endif
<audio src="/assets/alert.mp3"></audio>
@stop


@section("scripts")

<script>
    ISGH.Dates.init("/user/setAvailableDates");
</script>

@stop