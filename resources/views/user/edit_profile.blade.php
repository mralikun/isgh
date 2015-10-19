@extends("templates.master")


@section("navigation")

<li><a href="/user/dates">Available/Blocked Dates</a></li>
<li><a href="/user/rating">Rating</a></li>
<li><a href="/user/edit_profile">Update Profile Information</a></li>

@stop


@section("pageTitle")

Update Profile

@stop


@section("content")

<span class="text-right note">All of the following fields are required</span>
<form class="form-horizontal" method="POST" id="update-profile-form" ng-controller="UserController as uc" ng-submit="update()" enctype="multipart/form-data">

    <div class="form-group">
        <label class="control-label col-sm-3">Name</label>
        <div class="col-sm-9"><input type="text" name="name" class="form-control" placeholder="Enter your name" ng-model="user.name"></div>
    </div>
    <div class="form-group">
        <label for="" class="control-label col-sm-3">Address</label>
        <div class="col-sm-9">
            <input type="text" name="address" class="form-control">
        </div>
        <div class="col-sm-9 col-sm-offset-3">

           <div class="row form-group">

                <label class="col-sm-2">Country</label>
                <div class="col-sm-3"><input type="text" name="country" class="form-control" disabled placeholder="Country"/></div>

                <label class="col-sm-2">City</label>
                <div class="col-sm-3"><input type="text" name="locality" class="form-control" disabled placeholder="City"/></div>

           </div>


            <div class="row form-group">

                <label class="col-sm-2">State</label>
                <div class="col-sm-3"><input type="text" name="administrative_area_level_1" class="form-control" disabled placeholder="State"/></div>

                <label class="col-sm-2">Postal Code</label>
                <div class="col-sm-3"><input name="postal_code" class="form-control" type="text" disabled placeholder="Postal Code"/></div>

            </div>

       </div>
    </div>

    <div class="form-group">
        <label for="" class="control-label col-sm-3">Cell Phone</label>
        <div class="col-sm-9"><input type="tel" name="cell_phone" class="form-control" placeholder="Cell phone number" ng-model="user.cell_phone"></div>
    </div>
    <div class="form-group">
        <label for="" class="control-label col-sm-3">Biography</label>
        <div class="col-sm-9">
            <textarea name="bio" rows="5" class="form-control" placeholder="Tell us about yourself" ng-model="user.bio"></textarea>
        </div>
    </div>   
    <div class="form-group">
        <label for="" class="control-label col-sm-3">Educational Background</label>
        <div class="col-sm-9">
            <input type="text" name="edu_bg" placeholder="Please insert your educational background" class="form-control" ng-model="user.edu_bg" />
        </div>
    </div>
    <div class="form-group">
        <label for="" class="control-label col-sm-3">E-mail</label>
        <div class="col-sm-9"><input type="email" name="email" class="form-control" placeholder="E-mail" ng-model="user.email"></div>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary pull-right" value="Update Information">
    </div>


@stop

@section("aside")

<!--<img src="/assets/images/user.jpg" class="edit-img thumbnail"/>-->
<div class="edit-img thumbnail"></div>
<input type="file" name="profile_picture" class="form-control profile-pic">

@stop
</form>
@section("scripts")

<script src="/assets/js/services/google-geocode.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyByh3oCcAHKsHhGrd2widWjrkH2a14hVfU&signed_in=true&libraries=places&callback=initAutocomplete"></script>

@stop