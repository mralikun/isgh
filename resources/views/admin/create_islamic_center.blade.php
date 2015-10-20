@extends("templates.master")

@section("navigation")

<li><a href="/admin/members/create">Create new members</a></li>
<li><a href="/admin/islamic_center/create">Create islamic center</a></li>
<li><a href="/admin/schedule">Manage Schedule</a></li>
<li><a href="/admin/islamic_center/edit" class="sub-link">Edit/Delete Islamic Centers</a></li>

@stop


@section("pageTitle")

Create Islamic Center

@stop


@section("content")

<span class="text-right note">All fields with a <sup>*</sup> sign are required fields</span>
<form method="POST" class="form-horizontal" ng-controller="IslamicCenterController as icc" ng-submit="create()" name="icForm">

   <div class="form-group">
       <label class="col-sm-2 control-label">Name <sup>*</sup></label>
       <div class="col-sm-10">
           <input type="text" name="name" class="form-control" required placeholder="Enter Islamic Center's name" ng-model="center.name">
           <div ng-messages="icForm.name.$error">
               <div ng-message="required">Name is required</div>
           </div>
       </div>
   </div>

   <div class="form-group">
       <label class="col-sm-2 control-label">Address <sup>*</sup></label>
       <div class="col-sm-10">
           <input type="text" name="address" class="form-control" required ng-model="center.auto">
             <div ng-messages="icForm.address.$error">
               <div ng-message="required">Address is required</div>
           </div>
       </div>
       <div class="col-sm-10 col-sm-offset-2">

           <div class="row form-group">

                <label class="col-sm-2">Country <sup>*</sup></label>
                <div class="col-sm-3"><input type="text" name="country" class="form-control" disabled placeholder="Country" required ng-model="center.auto"/></div>

                <label class="col-sm-2">City <sup>*</sup></label>
                <div class="col-sm-3"><input type="text" name="locality" class="form-control" disabled placeholder="City" required ng-model="center.auto"/></div>

           </div>


            <div class="row form-group">

                <label class="col-sm-2">State <sup>*</sup></label>
                <div class="col-sm-3"><input type="text" name="administrative_area_level_1" class="form-control" disabled placeholder="State" required ng-model="center.auto"/></div>

                <label class="col-sm-2">Postal Code <sup>*</sup></label>
                <div class="col-sm-3"><input name="postal_code" class="form-control" type="text" disabled placeholder="Postal Code" required ng-model="center.auto"/></div>

            </div>

       </div>
   </div>

   <div class="form-group">
       <label class="col-sm-2 control-label">Director Name <sup>*</sup></label>
       <div class="col-sm-10">
          <select name="director_name" class="form-control" required ng-model="center.director_name" ng-change="updateDirectorCellPhone()">
               @foreach($directors as $director)
               <option value="{{$director->id}}">{{$director->name}}</option>
               @endforeach
           </select>
           <div ng-messages="icForm.director_name.$error">
               <div ng-message="required">Please choose a director</div>
           </div>
       </div>
   </div>

   <div class="form-group">
       <label class="col-sm-2 control-label">Director Cell Phone</label>
       <div class="col-sm-10">
           <input type="tel" name="director_cell" class="form-control" disabled placeholder="Director cell phone" ng-model="center.director_cell_phone">
       </div>
   </div>

  <div class="form-group">
       <label class="col-sm-2 control-label">Website</label>
       <div class="col-sm-10"><input type="url" name="website" class="form-control" placeholder="e.g: http://www.google.com/" ng-model="center.website"></div>
   </div>

  <div class="form-group">
       <label class="col-sm-2 control-label">Khutbah Time <sup>*</sup></label>
       <div class="col-sm-10">

           <label for="start_time" class="col-sm-2">Start <sup>*</sup></label>
           <div class="col-sm-3">
               <input type="time" name="khutbah_start_time" class="form-control" required ng-model="center.khutbah_start">
               <div ng-messages="icForm.khutbah_start_time.$error">
                   <div ng-message="required">Please insert the starting time</div>
               </div>
           </div>

          <label for="end_time" class="col-sm-2">End <sup>*</sup></label>
           <div class="col-sm-3">
                <input type="time" name="khutbah_end_time" class="form-control" required ng-model="center.khutbah_end">
                <div ng-messages="icForm.khutbah_end_time.$error">
                   <div ng-message="required">Please insert the ending time</div>
               </div>
            </div>


       </div>
   </div>

 <div class="form-group">
       <label class="col-sm-2 control-label">Parking Information <sup>*</sup></label>
       <div class="col-sm-10">
           <textarea name="parking_info" cols="30" rows="5" class="form-control" resize="none" placeholder="Please fill any parking related details" required ng-model="center.parking_information"></textarea>
           <div ng-messages="icForm.parking_info.$error">
               <div ng-message="required">Please provide parking information</div>
           </div>
        </div>
   </div>

<div class="form-group">
       <label class="col-sm-2 control-label">Other Information</label>
       <div class="col-sm-10"><textarea name="other_info" cols="30" rows="5" class="form-control" resize="none" placeholder="Please include any additional instructions" ng-model="center.other_information"></textarea></div>
   </div>

   <input type="submit" class="btn btn-primary pull-right" value="Add Islamic Center" ng-disabled="!center.name||!center.director_name||!center.khutbah_start||!center.khutbah_end||!center.parking_information">

</form>
<audio src="/assets/alert.mp3"></audio>
@stop


@section("scripts")

<script src="/assets/js/services/google-geocode.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyByh3oCcAHKsHhGrd2widWjrkH2a14hVfU&signed_in=true&libraries=places&callback=initAutocomplete"></script>
<script src="/assets/js/controllers/islamicCenter.js"></script>
@stop