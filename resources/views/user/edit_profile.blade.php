@extends("templates.master")


@section("navigation")

@if($firstTime == "false" and !isset($adminEditing))

<li><a href="/user/profile">View Profile</a></li>
<li><a href="/user/rating">Prefrences</a></li>
@if($role == 3)
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
@endif
<li><a href="/auth/logout">Logout</a></li>

@stop
@section("pageTitle")

Update Profile

@stop

@section("content")
@if($firstTime == "true")
<h4 class="first-time">It seems this is your first time logging into ISGH System ,Please take a minute to update your personal information</h4>
@endif
<span class="text-right note">All of the following fields are required</span>
<form class="col-xs-10 col-xs-offset-1 form-horizontal" id="update-profile-form" ng-controller="UserController as uc" enctype="multipart/form-data" name="profile">
  @if(isset($adminEditing) and $adminEditing != null)
  <input type="hidden" value="{{$adminEditing}}" name="userID">
  @endif

    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="form-group">
        <label class="control-label col-sm-3">Name</label>
        <div class="col-sm-9">
            <input type="text" name="name" class="form-control" placeholder="Enter your name" required value="{{$result->name}}">
            <div ng-messages="profile.name.$error">
                <div ng-message="required">Please enter your name</div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="control-label col-sm-3">Address</label>
        <div class="col-sm-9">
            <input type="text" name="address" class="form-control" value="{{$result->address}}">
        </div>
        <div class="col-sm-9 col-sm-offset-3">

           <div class="row form-group">

                <label class="col-sm-2">Country</label>
                <div class="col-sm-3"><input type="text" name="country" class="form-control address-part" disabled placeholder="Country"/></div>

                <label class="col-sm-2">City</label>
                <div class="col-sm-3"><input type="text" name="locality" class="form-control address-part" disabled placeholder="City"/></div>

           </div>


            <div class="row form-group">

                <label class="col-sm-2">State</label>
                <div class="col-sm-3"><input type="text" name="administrative_area_level_1" class="form-control address-part" disabled placeholder="State"/></div>

                <label class="col-sm-2">Postal Code</label>
                <div class="col-sm-3"><input name="postal_code" class="form-control address-part" type="text" disabled placeholder="Postal Code" value="{{$result->post_code}}"/></div>

            </div>

       </div>
    </div>
    <div class="form-group">
        <label for="" class="control-label col-sm-3">Cell Phone</label>
        <div class="col-sm-9">
            <input type="tel" name="cell_phone" class="form-control" placeholder="Cell phone number" required value="{{$result->phone}}">
            <div ng-messages="profile.cell_phone.$error">
                <div ng-message="required">Please Enter your cell phone</div>
            </div>
        </div>
    </div>
    @if($role == 2)
    <div class="form-group">
        <label for="" class="control-label col-sm-3">Educational Background</label>
        <div class="col-sm-9">
            <input type="text" name="edu_background" placeholder="Please insert your educational background" class="form-control" value="{{$result->edu_background}}"/>
        </div>
    </div>
    @endif
    <div class="form-group">
        <label for="" class="control-label col-sm-3">E-mail</label>
        <div class="col-sm-9">
            <input type="email" name="email" class="form-control" placeholder="E-mail" required value="{{$result->email}}">
            <div ng-messages="profile.email.$error">
                <div ng-message="required">Please Enter E-mail</div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary pull-right" value="Update Information">
    </div>

<audio src="/assets/alert.mp3"></audio>

@stop

@section("aside")

@if($role == 2)

<div class="pic-holder">
   
    
    @if($result->picture_url != "")
    <div class="edit-img thumbnail" style="background-image: url(/images/khateeb_pictures/{{$result->picture_url}})"></div>
    <input type="file" name="profile_picture" class="form-control profile-pic">
    @else
    <div class="edit-img thumbnail" style="background-image: url(/assets/images/user.jpg)"></div>
    <input type="file" name="profile_picture" class="form-control profile-pic" required>
    @endif

</div>

@endif

@stop
</form>
@section("scripts")

<script src="/assets/js/services/google-geocode.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyByh3oCcAHKsHhGrd2widWjrkH2a14hVfU&signed_in=true&libraries=places&callback=initAutocomplete"></script>
<script>

    function isAdmin(){
        return {{ isset($adminEditing) }} ;
    }

</script>
@stop