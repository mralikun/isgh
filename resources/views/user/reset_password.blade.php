@extends("templates.master")


@section("content")

<form id="reset-password" method="post" action="/user/resetPassword">
    
    <div class="form-group">
        <label for="new_password" class="control-label col-sm-2">New Password</label>
        <div class="col-sm-10">
            <input type="password" name="new_password" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <label for="new_password_confirm" class="control-label col-sm-2">Confirm New Password</label>
        <div class="col-sm-10">
            <input type="password" name="new_password_confirm" class="form-control">
        </div>
    </div>
    
    <div class="form-group text-center">
        <input type="submit" class="btn btn-isgh" value="Reset Password">
    </div>
    
</form>

@stop