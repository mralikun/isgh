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

<h3 class="text-center">Sumbit your ratings </h3>
<h4 ng-show="!rates.length" class="text-center">Loading data ...</h4>
<div id="allUsers">
   <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="row rating-row" ng-repeat="us in ratingUsers">
        <div class="col-sm-5 col-md-5 col-lg-5">

            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <img src="" alt="" class="rating-img">
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <h3 class="rating-name"></h3>
                </div>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7 stars" id=""></div>
    </div>
</div>

@stop


@section("scripts")

<link rel="stylesheet" href="/assets/js/services/raty/lib/jquery.raty.css">
<script src="/assets/js/services/raty/lib/jquery.raty.js"></script>
<script src="/assets/js/services/rating.js"></script>

@stop