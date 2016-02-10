@extends("templates.master")


@section("navigation")

@if(isset($admin) and $admin == "true")
<li><a href="/user/profile">View Profile</a></li>
<li><a href="/user/dates">Available Dates</a></li>
<li><a href="/user/BlockedDates">Blocked Dates</a></li>
<li><a href="/user/rating">Prefrences</a></li>
<li><a href="/user/edit_profile">Update Profile Information</a></li>
@endif
<li><a href="/admin/members/create">Create new members</a></li>
<li><a href="/admin/islamic_center/create">Create islamic center</a></li>
<li><a href="/admin/blocked_dates_report">Blocked Dates Report</a></li>
<li><a href="/admin/schedule">Manage Schedule</a></li>
<li><a href="/auth/logout">Logout</a></li>
<li><a href="/admin/members/edit" class="sub-link">Edit/Delete Members</a></li>

@stop

@section("pageTitle")

Create new member

@stop


@section("content")
<span class="note text-right">All of the following fields are required</span>

<form method="POST" class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2 form-horizontal" id="members-form" ng-controller="UserController as uc" ng-submit="create(tempUser)" name="registerForm">

    <div class="form-group" ng-class="{'has-success': tempUser.username && tempUser.username.length >= 6 && tempUser.username.length <= 32}">
        <label for="" class="col-sm-2 control-label">Username </label>
        <div class="col-sm-10">
            <input type="text" data-toggle="popover" title="Error!" data-placement="auto" data-content="Please make sure you have a username with length between 6 ~ 32 character" class="form-control" name="Name" placeholder="Username: 6 ~ 32 Characters" ng-model="tempUser.username" required>
            <div class="text-danger" ng-show="tempUser.username">
                <ul>
                    <li ng-show="!tempUser.username">The Username is required!</li>
                    <li ng-show="!(tempUser.username.length >= 6 && tempUser.username.length <= 32)">The Username must be between 6 ~ 32 characters long.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="form-group" ng-class="{'has-success': tempUser.password && tempUser.password.length >= 8 && tempUser.password.length <= 32}">
        <label for="" class="col-sm-2 control-label">Password </label>
        <div class="col-sm-10">
            <input type="password" class="form-control" name="password" placeholder="Password: at least 8 Characters" ng-model="tempUser.password" required>
            <div class="text-danger" ng-show="tempUser.password">
                <ul>
                    <li ng-show="!tempUser.password">The password is required!</li>
                    <li ng-show="!(tempUser.password.length >= 8 && tempUser.password.length <= 32)">The password must be between 8 ~ 32 characters long.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="form-group" ng-class="{'has-success': tempUser.confirm_password && tempUser.password === tempUser.confirm_password}">
        <label for="" class="col-sm-2 control-label">Confirm Password</label>
        <div class="col-sm-10">
            <input type="password" name="confirm_password" required class="form-control" ng-model="tempUser.confirm_password" placeholder="Re-enter the password">
            <div class="text-danger" ng-show="tempUser.confirm_password">
                <ul>
                    <li ng-show="!tempUser.confirm_password">Please confirm your password</li>
                    <li ng-show="tempUser.confirm_password !== tempUser.password">The Passwords don't match!</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="form-group">

        <label class="col-sm-2 control-label">Role </label>
        <div class="col-sm-10">

            <label for="" class="control-label col-sm-3"><input type="radio" name="role" value="0" ng-model="tempUser.role" required> Associate Director </label>
            <label for="" class="control-label col-sm-3"><input type="radio" name="role" value="1" ng-model="tempUser.role" required> Khateeb </label>
            <label for="" class="control-label col-sm-3"><input type="radio" name="role" value="2" ng-model="tempUser.role" required> Admin </label>

        </div>

    </div>

    <div class="form-group" ng-show="tempUser.role == 0" ng-init="tempUser.reviewer = 0">

        <label class="control-label col-sm-2">Reviewer ?</label>
        <div class="col-sm-10">
            <label class="col-sm-3 control-label"><input type="radio" name="reviewer" value="1" ng-model="tempUser.reviewer"> Yes </label>
            <label class="col-sm-3 control-label"><input type="radio" name="reviewer" value="0" ng-model="tempUser.reviewer"> No </label>
        </div>

    </div>
    
    <div class="form-group" ng-show="tempUser.role == 0" ng-init="tempUser.admin_priv = 0">
        <label class="col-sm-2 control-label">Admin privileges ?</label>
        <div class="col-sm-10">
            <label class="col-sm-3 control-label"><input type="radio" name="admin" value="1" ng-model="tempUser.admin_priv"> Yes </label>
            <label class="col-sm-3 control-label"><input type="radio" name="admin" value="0" ng-model="tempUser.admin_priv"> No </label>
        </div>
    </div>
    <div class="form-group" ng-show="tempUser.role == 0">
        <label class="col-sm-2 control-label">Islamic Center</label>
        <div class="col-sm-10">
            <select class="form-control" ng-model="tempUser.ic">
                @foreach($islamic_centers as $islamic_center)
                <option value="{{$islamic_center->id}}">{{$islamic_center->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <span class="form-group" ng-init="tempUser.isgh_member = 0">
    </span>
    
    <div class="form-group" ng-show="tempUser.role == 2">
        <label class="control-label col-sm-2">E-mail</label>
        <div class="col-sm-10">
            <input type="email" name="admin_email" class="form-control" placeholder="E-mail Address" ng-model="tempUser.email">
            <span class="help-block">We will use this email for password recovery and mail notifications for the schedule.</span>
        </div>
    </div>
    
    <div class="form-group">
        <input type="submit" class="btn btn-primary pull-right" value="Add Member">
    </div>

</form>
<audio src="/assets/alert.mp3"></audio>
@stop