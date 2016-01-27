@extends("templates.master")

@section("navigation")
<li><a href="/user/profile">View Profile</a></li>
<li><a href="/user/rating">Prefrences</a></li>
<li><a href="/user/BlockedDates">Blocked Dates</a></li>
<li><a href="/user/dates">Available Dates as khateeb</a></li>
<li><a href="/user/edit_profile">Update Profile</a></li>
@if(isset($reviewer) and $reviewer == "true")
<li><a href="/admin/schedule">Review Schedule</a></li>
@endif

@if(isset($admin) and $admin == "true")
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

    $other_choice = Map($fridays_choosen_my_ic , function($fr){
        return $fr->friday_id;
    });

    $blocked = Map($blocked_dates , function($fr){
        return $fr->friday_id;
    });
?>

<h3>{{$name}}</h3>
<span class="hint"><strong>Choose the dates which you want the system to assign you to give khutbahs <mark>in other Islamic Centers.</mark></strong></span>
<div class="options">
    
    <button class="btn btn-isgh select-all">Select All</button>
    <button class="btn btn-isgh unselect-all">Unselect All</button>
    <button class="btn btn-isgh reverse-select">Reverse Selection</button>
    
</div>
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
       @elseif(in_array($friday->id , $other_choice))
        <div class="date reserved" id="{{$friday->id}}">
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
<audio src="/assets/alert.mp3"></audio>
@stop

@section("scripts")

<script>
    ISGH.Dates.init("/user/adOtherIslamicCenters");
</script>

@stop