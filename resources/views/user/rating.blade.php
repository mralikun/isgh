@extends("templates.master")


@section("navigation")

<li><a href="/user/profile">View Profile</a></li>
<li><a href="/user/dates">Available Dates</a></li>
<li><a href="/user/rating">Rating</a></li>
<li><a href="/user/edit_profile">Update Profile Information</a></li>
@if(isset($reviewer) and $reviewer)
<li><a href="/admin/schedule">Review Schedule</a></li>
@endif

@if(isset($admin) and $admin)
<li><a href="/admin/members/create">Create new members</a></li>
<li><a href="/admin/islamic_center/create">Create islamic center</a></li>
@endif
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
    
    .loading {
        display: none;
    }
</style>

@section("pageTitle")

Rating

@stop


@section("content")
@if($role == 3)
<div class="rating-options text-center">
    <button class="btn btn-isgh" data-kh="1">Rate khateebs</button><button class="btn btn-isgh" data-kh="0">Rate Other Islamic Centers</button>
</div>
@endif
@if(isset($photo) && $photo == 'false')
<div class="upload-pic">
  <div class="the-table">
      <div class="the-row">
          <div class="the-cell">
            <h3 class="text-center">Choose a profile picture</h3>
            <form id="upload_prof" class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="edit-img thumbnail" style="background-image: url(/assets/images/user.jpg);"></div>
                    <input type="file" class="form-control" name="prof_pic" id="prof_pic">
                </div>
                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                <div class="form-group text-center">
                    <input type="submit" class="btn btn-primary" value="Upload Picture"><img src="/assets/images/loading.gif" class="loading" style="margin-left: 5px;display: none;">
                </div>
            </form>
          </div>
      </div>
  </div>
</div>
@endif
<div id="allUsers">
  <div class="text-right">
      <button class="btn btn-isgh back" style="display: none;">Back</button>
  </div>
<div class="heading">
    <h4 class="text-center">The Rate goes from 0 ~ 7 where 0 <mark>(The X button)</mark> is not preferrable at all ,1 is least preferrable and 7 is most preferrable.</h4>
</div>
   <input type="hidden" name="_token" value="{{csrf_token()}}">
</div>
@stop
@section("scripts")

<link rel="stylesheet" href="/assets/js/services/raty/lib/jquery.raty.css">
<link rel="stylesheet" href="/assets/css/fa/css/font-awesome.min.css">
<script src="/assets/js/services/raty/lib/jquery.raty.js"></script>
<script>
    flag = false;
    var role = {{$role}};
    function disableImgs(){
        if(role === 2 || flag){
            $(".rating-img").remove();
            $(".rating-name").parent().removeClass("col-sm-8 col-md-8 col-lg-8").addClass("col-sm-12 col-md-12 col-lg-12");
        }
    }
</script>

<script src="/assets/js/services/rating.js"></script>
@stop