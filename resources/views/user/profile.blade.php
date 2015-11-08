@extends("templates.master")


@section("navigation")
<li><a href="/user/dates">Available/Blocked Dates</a></li>
<li><a href="/user/rating">Rating</a></li>
<li><a href="/user/edit_profile">Update Profile Information</a></li>
<li><a href="/auth/logout">Logout</a></li>
@stop


@section("pageTitle")

Profile

@stop


@section("content")
<div class="row">
@if(!isset($user_info->reviewer))
<div class="col-sm-3 col-md-3 col-lg-3 profile-page-picture" style="background-image: url(/images/khateeb_pictures/{{$user_info->picture_url}});">
</div>
@endif
<div class="col-sm-8 col-md-8 col-lg-8 username"><h1>{{$user_info->name}}</h1></div>

</div>
<div class="row"><p class="bio text-center">{{$user_info->bio}}</p></div>

@stop