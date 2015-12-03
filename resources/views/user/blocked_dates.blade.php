@extends("templates.master")


@section("navigation")
<li><a href="/user/profile">View Profile</a></li>
<li><a href="/user/dates">Available Dates</a></li>
@if($role == 3)
<!-- We will change this link href for a new route -->
<li><a href="/user/BlockedDates">Blocked Dates</a></li>
@endif
<li><a href="/user/rating">Rating</a></li>
<li><a href="/user/edit_profile">Update Profile Information</a></li>
<li><a href="/auth/logout">Logout</a></li>

@stop


@section("pageTitle")

Blocked Dates

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

<style>
    .date.available {
        background-color: #DD0E1D;
    }
</style>

<h3>{{$name}}</h3>

<span class="hint"><strong>Check the box for all dates you want to <mark><em><u>Block</u></em></mark> from being assigned a khateeb by the system.</strong></span>

<div class="options">
    
    <button class="btn btn-isgh select-all">Select All</button>
    <button class="btn btn-isgh unselect-all">Unselect All</button>
    <button class="btn btn-isgh reverse-select">Reverse Selection</button>
    
</div>
<form id="blocked-dates-form">
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
   <!-- The value of this input is the ID of the islamic center! -->
   <input type="text" style="display:none;" name="ic" value="{{$islamic_center->id}}">
    <div class="dates-calendar">
       @foreach($fridays as $friday)
       @if(in_array($friday->id , $choosen))
        <div class="date available" id="{{$friday->id}}">
            <div class="date-content">
               <h4>Friday</h4>
               <h5>{{$friday->date}}</h5>
               <input type="checkbox" disabled checked>
            </div>
        </div>
       @else
        <div class="date" id="{{$friday->id}}">
            <div class="date-content">
               <h4>Friday</h4>
               <h5>{{$friday->date}}</h5>
               <input type="checkbox" disabled>
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
    ISGH.Dates.init();
</script>

@stop