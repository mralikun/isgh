@extends("templates.master")


@section("navigation")

<li><a href="/user/dates">Available/Blocked Dates</a></li>
<li><a href="/user/rating">Rating</a></li>
<li><a href="/user/edit_profile">Update Profile Information</a></li>
<li><a href="/auth/logout">Logout</a></li>

@stop


@section("pageTitle")

Rating

@stop


@section("content")

<div id="allUsers">
   <input type="hidden" name="_token" value="{{csrf_token()}}">
</div>

@stop


@section("scripts")

<link rel="stylesheet" href="/assets/js/services/raty/lib/jquery.raty.css">
<script src="/assets/js/services/raty/lib/jquery.raty.js"></script>
<script src="/assets/js/services/rating.js"></script>

@stop