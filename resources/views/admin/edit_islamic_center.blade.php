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

Edit/Delete Islamic Center

@stop

@section("content")

@if(sizeof($all) > 0)
<table class="table table-bordered ic-table" ng-controller="IslamicCenterController as icc">
    
    <thead>
        
        <tr>
            <th>Name</th>
            <th>Options</th>
        </tr>
        
    </thead>
    
    <tbody>
        
        @foreach($all as $ic)
        <tr ng-click="delete($event)">

            <td><h4>{{$ic->name}}</h4></td>
            <td><a class="btn btn-isgh" data-member="{{$ic->id}}" href="/admin/islamic_center/create/{{$ic->id}}" target="_blank">Edit</a><button class="btn btn-isgh opt-delete" data-member="{{$ic->id}}">Delete</button></td>

        </tr>

        @endforeach
        
    </tbody>
    
</table>
@else

<h4 class="text-center">There're no islamic centers yet, <a href="/admin/islamic_center/create" class="inline-link">Create New Islamic Center</a></h4>

@endif

@stop