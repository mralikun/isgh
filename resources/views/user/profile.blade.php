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

<div class="col-sm-4 col-md-4 col-lg-4">
    <img src="/assets/images/user.jpg" alt="User">
</div>
<div class="col-sm-8 col-md-8 col-lg-8 username"><h1>User name</h1></div>

</div>
<div class="row"><p class="bio">Biography</p></div>

@stop