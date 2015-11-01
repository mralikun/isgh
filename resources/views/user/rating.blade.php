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

<div class="row rating-row">

    <div class="col-sm-5 col-md-5 col-lg-5">

        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6">
                <img src="/assets/images/user.jpg" alt="NONE" class="rating-img">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6">
                <h3 class="rating-name">Name</h3>
            </div>
        </div>
    </div>

    <div class="col-sm-7 col-md-7 col-lg-7 stars" id="5"></div>

</div>
<div class="row rating-row">

    <div class="col-sm-5 col-md-5 col-lg-5">

        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6">
                <img src="/assets/images/user.jpg" alt="NONE" class="rating-img">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6">
                <h3 class="rating-name">Name</h3>
            </div>
        </div>
    </div>

    <div class="col-sm-7 col-md-7 col-lg-7 stars" id="12"></div>

</div>
<button class="btn btn-primary pull-right" id="submit-rates">Submit</button>

@stop


@section("scripts")

<link rel="stylesheet" href="/assets/js/services/raty/lib/jquery.raty.css">
<script src="/assets/js/services/raty/lib/jquery.raty.js"></script>
<script src="/assets/js/services/rating.js"></script>

@stop