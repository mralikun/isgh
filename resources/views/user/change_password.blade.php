@extends("templates.master")

@section("navigation")

<li><a href="/auth/logout">Logout</a></li>

@stop

@section("pageTitle")
Change Password
@stop


@section("content")
<div class="row" ng-controller="UserController as uc">
    <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
       <div class="text-center text-warning">
           <h5>It seems this is your first time into the system , Please change the default password with a new one.</h5>
       </div>
        <form ng-submit="saveNewPassword()">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" ng-model="chg_pass.new" class="form-control" placeholder="New Password">
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" ng-model="chg_pass.confirm_new" class="form-control" placeholder="Confirm New Password">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-isgh" value="Save">
            </div>
        </form>
    </div>
</div>
@stop