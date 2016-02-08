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

@stop

@section("pageTitle")
Blocked Dates Report
@stop

@section("content")

<style>
    th , td{
        vertical-align: middle!important;
    }
</style>

<div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1" ng-controller="IslamicCenterController as icc">
   <div class="help-block text-center" ng-show="waitingBlockedDates && waitingBlockedDates.length == 0">
       <h3 style="padding: 25px;">No blocked dates are available for review!</h3>
   </div>
    <table class="table table-bordered" style="margin-top: 25px;" ng-show="waitingBlockedDates.length > 0">
        <thead>
            <tr>
                <th>Islamic Center</th>
                <th>AD</th>
                <th>Visitor</th>
                <th>Friday Date</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="date in waitingBlockedDates">
                <th>[[date.islamic_center_name]]</th>
                <th>[[date.director_name.name]]</th>
                <th>[[date.visitor_name.visitor_name]]</th>
                <th>[[date.friday_date.date | date:"dd-MM-yyyy"]]</th>
                <td>
                    <button class="btn btn-isgh" style="margin: 5px!important;" ng-click="editBlockedDateStatus({pos: $index , status: 2 , event: $event})">Approve</button>
                    <button class="btn btn-isgh" style="margin: 5px!important;" ng-click="editBlockedDateStatus({pos: $index , status: 3 , event: $event})">Decline</button>
                </td>
            </tr>
        </tbody>
    </table>
    
</div>
@stop