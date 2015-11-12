@extends("templates.master")


@section("navigation")
<li><a href="/user/profile">View Profile</a></li>
<li><a href="/user/dates">Available/Blocked Dates</a></li>
<li><a href="/user/rating">Rating</a></li>
<li><a href="/user/edit_profile">Update Profile Information</a></li>
<li><a href="/auth/logout">Logout</a></li>

@stop


@section("pageTitle")

Available/Blocked Dates

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
@else
<span class="hint"><strong>Check the box for all dates you want to <mark><em><u>Block</u></em></mark> from being assigned a khateeb by the system.</strong></span>
@endif

<div class="options">
    
    <button class="btn btn-isgh select-all">Select All</button>
    <button class="btn btn-isgh unselect-all">Unselect All</button>
    <button class="btn btn-isgh reverse-select">Reverse Selection</button>
    
</div>
<form id="blocked-dates-form">
  <!-- Token Mismatch Exception !!!!!! -->
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
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