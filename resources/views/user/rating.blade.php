@extends("templates.master")


@section("navigation")

<li><a href="/user/profile">View Profile</a></li>
<li><a href="/user/dates">Available Dates</a></li>
<li><a href="/user/rating">Rating</a></li>
<li><a href="/user/edit_profile">Update Profile Information</a></li>
<li><a href="/auth/logout">Logout</a></li>

@stop
<style>
    .upload-pic {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255,255,255,.8);
/*        display: none;*/
    }
    
    .the-table {
        display: table;
        width: 100%;
        height: 100%;
    }
    
    .the-row {
        display: table-row;
        width: 100%;
        height: 100%;
    }
    
    .the-cell {
        width: 100%;
        height: 100%;
        display: table-cell;
        vertical-align: middle;
    }
</style>

@section("pageTitle")

Rating

@stop


@section("content")

<div class="rating-options text-center">
    <button class="btn btn-isgh" data-kh="1">Rate khateebs</button><button class="btn btn-isgh" data-kh="0">Rate Other Islamic Centers</button>
</div>

<div class="upload-pic">
  <div class="the-table">
      <div class="the-row">
          <div class="the-cell">
            <h3 class="text-center">Choose a profile picture</h3>
            <form method="POST" action="/adUploadProfilePicture" id="upload_prof" class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
                <div class="form-group">
                    <div class="edit-img thumbnail" style="background-image: url(/assets/images/user.jpg);"></div>
                    <input type="file" class="form-control" name="prof_pic">
                </div>
                <div class="form-group text-center">
                    <input type="submit" class="btn btn-primary" value="Upload Picture"><img src="/assets/images/loading.gif" class="loading" style="margin-left: 5px;display: none;">
                </div>
            </form>
          </div>
      </div>
  </div>
</div>

<div id="allUsers">
  <div class="text-right">
      <button class="btn btn-isgh back" style="display: none;">Back</button>
  </div>
<div class="heading">
    @if($role == 2)
    <h4 class="text-center">Please rate the following Islamic centers from 0 <mark><u>(The X button)</u></mark> to 7 where 0 is the least preferrable place you want to go to give khutbah and 7 is the most preferrable.</h4>
    @else
    <h4 class="text-center">Please rate the following Khateebs from 0 <mark><u>(The X button)</u></mark> to 7 where 0 is the least preferrable khateeb you want to give khutbah and 7 is the most preferrable.</h4>
    @endif
</div>
   <input type="hidden" name="_token" value="{{csrf_token()}}">
</div>

@stop


@section("scripts")

<link rel="stylesheet" href="/assets/js/services/raty/lib/jquery.raty.css">
<link rel="stylesheet" href="/assets/css/fa/css/font-awesome.min.css">
<script src="/assets/js/services/raty/lib/jquery.raty.js"></script>
<script>
    function disableImgs(){
        var role = {{$role}} ;
        if(role === 2){
            $(".rating-img").remove();
            $(".rating-name").parent().removeClass("col-sm-8 col-md-8 col-lg-8").addClass("col-sm-12 col-md-12 col-lg-12");
        }
    }
</script>

<script src="/assets/js/services/rating.js"></script>
@stop