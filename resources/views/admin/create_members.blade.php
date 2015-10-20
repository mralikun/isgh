@extends("templates.master")


@section("navigation")

<li><a href="/admin/members/create">Create new members</a></li>
<li><a href="/admin/islamic_center/create">Create islamic center</a></li>
<li><a href="/admin/schedule">Manage Schedule</a></li>
<li><a href="/admin/members/edit" class="sub-link">Edit/Delete Members</a></li>

@stop


@section("pageTitle")

Create new member

@stop


@section("content")

<span class="note text-right">All of the following fields are required</span>

<form method="POST" class="form-horizontal" id="members-form" ng-controller="UserController as uc" ng-submit="create(tempUser)">

    <div class="form-group">
        <label for="" class="col-sm-2 control-label">Username </label>
        <div class="col-sm-10"><input type="text" class="form-control" required ng-model="tempUser.username" placeholder="Username: 6 ~ 32 Characters" minlength="6" maxlength="32"></div>
    </div>

    <div class="form-group">
        <label for="" class="col-sm-2 control-label">Password </label>
        <div class="col-sm-10"><input type="password" name="password" required class="form-control" ng-model="tempUser.password" placeholder="Password: at least 8 Characters" minlength="8"></div>
    </div>

    <div class="form-group">
        <label for="" class="col-sm-2 control-label">Confirm Password</label>
        <div class="col-sm-10"><input type="password" name="confirm_password" required class="form-control" ng-model="tempUser.confirm_password" placeholder="Re-enter the password" minlength="8"></div>
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
    
    <div class="form-group" ng-show="tempUser.role == 1" ng-init="tempUser.isgh_member = 0">
        
        <label class="control-label col-sm-2">ISGH Member ?</label>
        <div class="col-sm-10">
            <label class="col-sm-3 control-label"><input type="radio" name="isgh_member" value="1" ng-model="tempUser.isgh_member"> Yes </label>
            <label class="col-sm-3 control-label"><input type="radio" name="isgh_member" value="0" ng-model="tempUser.isgh_member"> No </label>
        </div>
        
    </div>

    <div class="form-group">
        <input type="submit" class="btn btn-primary pull-right" value="Add Member" ng-disabled="!tempUser.username||!tempUser.password||!tempUser.confirm_password||!tempUser.role||tempUser.password!==tempUser.confirm_password">
    </div>

</form>
<audio src="/assets/alert.mp3"></audio>
@stop