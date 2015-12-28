@extends("templates.master")

@section("navigation")
@if(isset($admin) and $admin == "true")
<li><a href="/user/profile">View Profile</a></li>
<li><a href="/user/dates">Available Dates</a></li>
<li><a href="/user/BlockedDates">Blocked Dates</a></li>
<li><a href="/user/rating">Rating</a></li>
<li><a href="/user/edit_profile">Update Profile Information</a></li>
@endif
<li><a href="/admin/members/create">Create new members</a></li>
<li><a href="/admin/islamic_center/create">Create islamic center</a></li>
<li><a href="/admin/schedule">Manage Schedule</a></li>
<li><a href="/auth/logout">Logout</a></li>
<li><a href="/admin/islamic_center/edit" class="sub-link">Edit/Delete Islamic Centers</a></li>
@stop

@section("content")
<span class="text-right note">All fields with a <sup>*</sup> sign are required fields</span>
<form method="POST" class="form-horizontal" ng-controller="IslamicCenterController as icc" ng-submit="create()" name="icForm">
   <div class="form-group" ng-class="{'has-success': center.name}">
       <label class="col-sm-2 control-label">Name <sup>*</sup></label>
       <div class="col-sm-10">
           <input type="text" name="name" class="form-control" required placeholder="Enter Islamic Center's name" ng-model="center.name">
           <div class="text-danger" ng-show="center.length > 0 && !center.name">
               
               <ul>
                   <li>The Islamic Center name is required!</li>
               </ul>
               
           </div>
       </div>
   </div>

   <div class="form-group" ng-class="{'has-success': center.auto}">
       <label class="col-sm-2 control-label">Address <sup>*</sup></label>
       <div class="col-sm-10">
           <input type="text" name="address" class="form-control" required ng-model="center.auto">
           <div class="text-danger" ng-show="center.length > 0 && !center.auto">
               <ul>
                   <li>Please specify the Islamic Center's address.</li>
               </ul>
           </div>
        </div>
       <div class="col-sm-10 col-sm-offset-2">

           <div class="row form-group">

                <label class="col-sm-2">Country <sup>*</sup></label>
                <div class="col-sm-3"><input type="text" name="country" class="form-control" disabled placeholder="Country" required/></div>

                <label class="col-sm-2">City <sup>*</sup></label>
                <div class="col-sm-3"><input type="text" name="locality" class="form-control" disabled placeholder="City" required/></div>

           </div>


            <div class="row form-group">

                <label class="col-sm-2">State <sup>*</sup></label>
                <div class="col-sm-3"><input type="text" name="administrative_area_level_1" class="form-control" disabled placeholder="State" required/></div>

                <label class="col-sm-2">Postal Code <sup>*</sup></label>
                <div class="col-sm-3"><input name="postal_code" class="form-control" type="text" disabled placeholder="Postal Code" required/></div>

            </div>

       </div>
   </div>

   <div class="form-group" ng-class="{'has-success': center.director_name}">
       <label class="col-sm-2 control-label">Director Name <sup>*</sup></label>
       <div class="col-sm-10">
          <select name="director_name" class="form-control" required ng-model="center.director_name" ng-change="updateDirectorCellPhone()">
               @foreach($directors as $director)
               <option value="{{$director->id}}">{{$director->name}}</option>
               @endforeach
           </select>
           <div class="text-danger" ng-show="center.length > 0 && !center.director_name">
               <ul>
                   <li>Please choose a director for the Islamic Center.</li>
               </ul>
           </div>
       </div>
   </div>

   <div class="form-group" ng-class="{'has-success': center.director_name && center.director_cell_phone != 0 , 'has-warning': center.director_name && center.director_cell_phone == 0}">
       <label class="col-sm-2 control-label">Director Cell Phone</label>
       <div class="col-sm-10">
           <input type="tel" name="director_cell" class="form-control" disabled placeholder="Director cell phone" ng-model="center.director_cell_phone">
           <div class="text-warning" ng-show="center.director_name && center.director_cell_phone == 0">
               <ul>
                   <li>This director didn't insert his cell phone yet!</li>
               </ul>
           </div>
       </div>
   </div>

  <div class="form-group" ng-class="{'has-success': center.website}">
       <label class="col-sm-2 control-label">Website</label>
       <div class="col-sm-10"><input type="url" name="website" class="form-control" placeholder="e.g: http://www.google.com/" ng-model="center.website"></div>
   </div>

  <div class="form-group" ng-class="{'has-success': center.khutbah_start && center.khutbah_end && center.khutbah_start < center.khutbah_end}">
       <label class="col-sm-2 control-label">Khutbah Time <sup>*</sup></label>
       <div class="col-sm-10">

           <label for="start_time" class="col-sm-2">Start <sup>*</sup></label>
           <div class="col-sm-3">
               <input type="time" name="khutbah_start_time" class="form-control" required ng-model="center.khutbah_start">
               <div class="text-danger" ng-show="center.length > 0 && !center.khutbah_start">
                   <ul>
                       <li>Khutbah's Starting time is required!</li>
                   </ul>
               </div>
           </div>

          <label for="end_time" class="col-sm-2">End <sup>*</sup></label>
           <div class="col-sm-3">
                <input type="time" name="khutbah_end_time" class="form-control" required ng-model="center.khutbah_end">
                <div class="text-danger" ng-show="center.length > 0 && !center.khutbah_end">
                    <ul>
                        <li>Khutbah's Ending time is required!</li>
                    </ul>
                </div>
            </div>

            <div class="row" ng-show="center.khutbah_start > center.khutbah_end">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="text-danger">You can't end khutbah before it starts!</div>
                </div>
            </div>
       </div>
   </div>

 <div class="form-group" ng-class="{'has-success': center.parking_information}">
       <label class="col-sm-2 control-label">Parking Information <sup>*</sup></label>
       <div class="col-sm-10">
           <textarea name="parking_info" cols="30" rows="5" class="form-control" resize="none" placeholder="Please fill any parking related details" required ng-model="center.parking_information"></textarea>
           <div class="text-danger" ng-show="center.length > 0 && !center.parking_information">
               <ul>
                   <li>Please provide the parking information!</li>
               </ul>
           </div>
        </div>
   </div>

<div class="form-group" ng-class="{'has-success': center.other_information}">
       <label class="col-sm-2 control-label">Other Information</label>
       <div class="col-sm-10"><textarea name="other_info" cols="30" rows="5" class="form-control" resize="none" placeholder="Please include any additional instructions" ng-model="center.other_information"></textarea></div>
   </div>
   
   <input type="submit" class="btn btn-primary pull-right" value="Add Islamic Center" ng-disabled="!center.name||!center.director_name||!center.khutbah_start||!center.khutbah_end||!center.parking_information||center.khutbah_start > center.khutbah_end">
    <input type="hidden" id="token" name="_token" value="{{csrf_token()}}">
</form>
<audio src="/assets/alert.mp3"></audio>
@stop

@section("scripts")

<script src="/assets/js/services/google-geocode.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyByh3oCcAHKsHhGrd2widWjrkH2a14hVfU&signed_in=true&libraries=places&callback=initAutocomplete"></script>
<script src="/assets/js/controllers/islamicCenter.js"></script>
<script>
    var where = window.location.pathname;
    var hasNumber = where.substring(where.lastIndexOf("/") + 1 , where.length);
    if(isNaN(parseInt(hasNumber))){
        $(".page-title").text("Create Islamic Center");
    }else {
        function setDefaults() {
            $(".page-title").text("Edit Islamic Center");
            
            $.ajax({
                type: "POST",
                url: "/islamicCenterData/" + hasNumber,
                data: {_token: $("#token").val()},
                dataType: "json",
                success: function(resp){
                    var controllerE = $("form[name='icForm']");
                    var sco = angular.element(controllerE).scope();
                    
                    sco.$apply(function(){
                        console.log(resp);
                        sco.center.name = resp.name;
                        sco.center.website = resp.website;
                        sco.center.parking_information = resp.parking_information;
                        sco.center.other_information = resp.other_information;
                        sco.center.director_cell_phone = resp.ad.phone;
                        var sd = new Date(resp.khutbah_start);
                        var ed = new Date(resp.khutbah_end);
                        sd.setHours( sd.getHours() + (sd.getTimezoneOffset() * - 1 / 60) );
                        ed.setHours( ed.getHours() + (ed.getTimezoneOffset() * - 1 / 60) );
                        sco.center.khutbah_start = sd;
                        sco.center.khutbah_end = ed;
                        sco.center.director_name = resp.ad.id;
                        $("select[name='director_name']").prepend("<option value='"+resp.ad.id+"'> "+ resp.ad.name +" </option>");
                        $("option[value='"+resp.ad.id+"']").attr("selected" , true);
                        $("input[name='address']").val(resp.address);
                        $("input[name='country']").val(resp.address);
                        $("input[name='locality']").val(resp.city);
                        $("input[name='administrative_area_level_1']").val(resp.state);
                        $("input[name='postal_code']").val(resp.postal_code);
                        $("input[type='submit']").removeAttr("ng-disabled").attr("disabled" , false);
                    });
                }
            });

        }

        setDefaults();
    }
</script>
@stop