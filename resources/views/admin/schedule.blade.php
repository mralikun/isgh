@extends("templates.master")


@section("navigation")

<li><a href="/admin/members/create">Create new members</a></li>
<li><a href="/admin/islamic_center/create">Create islamic center</a></li>
<li><a href="/admin/schedule">Manage Schedule</a></li>
<li><a href="/auth/logout">Logout</a></li>

@stop

@section("pageTitle")

Schedule Management

@stop


<style>
    td , th {
        vertical-align: middle!important;
    }
    tr {
        transition: none!important;
    }
    table {
        -webkit-user-select: none;
        -moz-user-select: none;
        -o-user-select: none;
        -ms-user-select: none;
        user-select: none;
        max-width: 100%;
        overflow-x: auto;
    }
</style>

@section("content")
<div ng-controller="ScheduleController as SC" ng-show="schedule_generated">
    
    <div class="text-right schedule-opts">

        <button class="btn btn-isgh approve" ng-show="schedule_generated && !schedule_approved">Approve To Schedule</button>
        <button class="btn btn-isgh generate" ng-hide="schedule_generated">Generate Schedule</button>
        <button class="btn btn-isgh excel" ng-show="schedule_approved">Export Excel</button>

    </div>

    <div class="text-center" ng-hide="schedule_generated">
        No Schedule has been generated!, Please click on the "Generate Schedule" button on the top right.
    </div>

    <table class="table table-bordered" >

        <thead>

            <tr>
                <th>Center</th>
                <th ng-repeat="date in dates">[[date]]</th>
                <th>Options</th>
            </tr>

        </thead>

        <tbody>

            <tr ng-repeat="ic in schedule">
                <td>[[ic.islamic_center]]</td>
                <td class="text-center" ng-repeat="kh in ic.khutbahs">
                    <ul ng-show="kh.length" style="height: 100%">
                        <li ng-repeat="k in kh" style="height: 100%;">[[k.khateeb]]</li>
                    </ul>
                    <span ng-show="!kh.length">--</span>
                </td>
                <td>
                    <button class="btn btn-isgh">Edit</button>
                </td>
            </tr>

        </tbody>

    </table>
    
</div>


@stop


@section("scripts")
<script src="/assets/js/controllers/schedule.js"></script>
<script>
    $(".content-holder").removeClass("col-sm-6 col-md-6 col-lg-6 col-sm-offset-3 col-md-offset-3 col-lg-offset-3")
                        .addClass("col-sm-12 col-md-12 col-lg-12");
</script>
@stop