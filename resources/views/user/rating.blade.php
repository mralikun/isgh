@extends("templates.master")


@section("navigation")

<li><a href="/user/profile">View Profile</a></li>
<li><a href="/user/dates">Available/Blocked Dates</a></li>
<li><a href="/user/rating">Rating</a></li>
<li><a href="/user/edit_profile">Update Profile Information</a></li>
<li><a href="/auth/logout">Logout</a></li>

@stop


@section("pageTitle")

Rating

@stop


@section("content")
<div class="heading">
    @if($role == 2)
    <h4 class="text-center">Please rate the following Islamic centers from 0 <mark><u>(The X button)</u></mark> to 7 where 0 is the least preferrable place you want to go to give khutbah and 7 is the most preferrable.</h4>
    @else
    <h4 class="text-center">Please rate the following Khateebs from 0 <mark><u>(The X button)</u></mark> to 7 where 0 is the least preferrable khateeb you want to give khutbah and 7 is the most preferrable.</h4>
    @endif
</div>

<div id="allUsers">
   <input type="hidden" name="_token" value="{{csrf_token()}}">
</div>

@stop


@section("scripts")

<link rel="stylesheet" href="/assets/js/services/raty/lib/jquery.raty.css">
<link rel="stylesheet" href="/assets/css/fa/css/font-awesome.min.css">
<script src="/assets/js/services/raty/lib/jquery.raty.js"></script>
<script>
    function disableImgs(){
        var role = {{$role}} ;
        if(role === 2){
            $(".rating-img").remove();
            $(".rating-name").parent().removeClass("col-sm-8 col-md-8 col-lg-8").addClass("col-sm-12 col-md-12 col-lg-12");
        }
    }
</script>

<script src="/assets/js/services/rating.js"></script>
@stop