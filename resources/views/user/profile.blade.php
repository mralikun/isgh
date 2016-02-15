@extends("templates.master")


@section("navigation")
<li><a href="/user/profile">Home</a></li>
<li><a href="/user/rating">Prefrences</a></li>
@if(Auth::user()->role_id == 3)
<li><a href="/user/BlockedDates">Blocked Dates</a></li>
@endif
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

Profile

@stop


@section("content")
<div class="row">
<div class="the-table">
    <div class="the-row">
        <div class="the-cell">
            @if(!isset($user_info->reviewer))
            <div class="profile-page-picture" style="background-image: url(/images/khateeb_pictures/{{$user_info->picture_url}});">
            </div>
            @endif
            <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 username text-center">
                <h1>Welcome, {{$user_info->name}}</h1>
                <a href="/user/rating" class="quick-start"><i class="fa fa-star"></i>Setup your preferences</a><a href="/user/dates" class="quick-start"><i class="fa fa-calendar"></i>Setup your available dates</a>
            </div>
        </div>
    </div>
</div>


</div>

@stop