@extends("templates.master")


@section("navigation")

<li><a href="/user/profile">View Profile</a></li>
<li><a href="/user/dates">Available Dates</a></li>
<li><a href="/user/rating">Rating</a></li>
<li><a href="/user/edit_profile">Update Profile Information</a></li>
<li><a href="/auth/logout">Logout</a></li>

@stop


@section("pageTitle")

Rating

@stop


@section("content")
<div class="heading">
    @if($role == 2)
    <h4 class="text-center">Please rate the following Islamic centers from 0 <mark><u>(The X button)</u></mark> to 7 where 0 is the least preferrable place you want to go to give khutbah and 7 is the most preferrable.</h4>
    @else
    <h4 class="text-center">Please rate the following Khateebs from 0 <mark><u>(The X button)</u></mark> to 7 where 0 is the least preferrable khateeb you want to give khutbah and 7 is the most preferrable.</h4>
    @endif
</div>

<div id="allUsers" ng-controller="RatingController as RC">
   <input type="hidden" name="_token" value="{{csrf_token()}}">
   <div class="row rating-row" ng-repeat="u in current_page_data">
       
       <div class="col-sm-7 col-md-7 col-lg-7">
           <div class="row">
               <div class="col-sm-4 col-md-4 col-lg-4">
                   <img src="/images/khateeb_pictures/[[u.picture_url]]" class="img-responsive rating-img">
               </div>
               <div class="col-sm-8 col-md-8 col-lg-8">
                   <h3 class="rating-name">[[u.name]]</h3>
               </div>
           </div>
       </div>
       
       <div class="col-sm-4 col-md-4 col-lg-4 stars" id="[[u.id]]" data-rating="[[u.khateeb_rate_ad || u.ad_rate_khateeb]]"></div>
<!--       <span class="col-sm-1 col-md-1 col-lg-1"></span>-->
   </div>
   <div class="text-center">
       <button class="btn btn-isgh" ng-click="prev()" ng-disabled="page == 1"><< Prev</button><button class="btn btn-isgh" ng-click="next()" ng-disabled="page == pages_num">Next >></button>
   </div>
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