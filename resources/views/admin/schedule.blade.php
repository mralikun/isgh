@extends("templates.master")


@section("navigation")
@if(!isset($reviewer))
<li><a href="/admin/members/create">Create new members</a></li>
<li><a href="/admin/islamic_center/create">Create islamic center</a></li>
<li><a href="/admin/schedule">Manage Schedule</a></li>
@elseif(isset($reviewer) and $reviewer)
<li><a href="/user/profile">View Profile</a></li>
<li><a href="/user/dates">Available Dates</a></li>
<li><a href="/user/rating">Rating</a></li>
<li><a href="/user/edit_profile">Update Profile Information</a></li>
@if($reviewer)
<li><a href="/admin/schedule">Review Schedule</a></li>
@endif
@endif
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
    
    #editModal .modal-dialog{
        background-color: #fff;
        padding: 15px;
        max-height: 650px;
        overflow-y: auto;
    }
    
    .table {
        width: 100%!important;
    }
    
    .khateeb-edit {
        padding: 2px 5px;
        margin: 0 5px;
        border: 1px solid #888;
        color: #000!important;
    }
    
    .schedule-wrapper {
        max-width: 100%;
        overflow-x: auto;
    }
    
    .khateeb {
        border: 1px solid #888;
        padding: 5px;
        position: relative;
        margin: 0 5px;
    }
    
    .khateeb .khateeb-name {
        display: inline-block;
        margin-right: 7.5%;
    }
    
    .khateeb .remove-khateeb{
        position: absolute;
        right: 0;
        top: 0;
        height: 100%;
        margin: 0;
        border: none;
        background-color: transparent;
        border-left: 1px solid #777;
        transition: all 300ms;
    }
    
    .khateeb .remove-khateeb:hover {
        color: white;
        background-color: #f33;
    }
    
    .add-khateeb {
        border: 1px solid #777;
        background-color: transparent;
        transition: all 300ms;
    }
    
    .add-khateeb:hover {
        background-color: #3a3;
        color: white;
    }
    
</style>

@section("content")
<div ng-controller="ScheduleController as SC">
    <div class="modal fade" id="editModal" aria-labelledby="EditingModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-header">
                <h1 class="modal-title text-center">[[record.islamic_center.name]]</h1>
            </div>
            <div class="modal-content">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <table class="table table-bordered edit-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Khateebs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="rec in record.khutbahs">
                                    <th>[[rec.date]]</th>
                                    <td>
                                       
                                       <span ng-repeat="kh in rec.data" class="khateeb">
                                           <span class="khateeb-name">[[kh.khateeb.name]]</span>
                                           <button class="remove-khateeb" ng-click="removeKhateeb(rec , kh , $event)">X</button>
                                       </span>
                                       <span ng-repeat="mi in rec.missing track by $index">
                                           <button class="add-khateeb" ng-click="addKhateeb(rec , mi.date_id , $event)" ng-show="!editing_mode || editing_fri != mi.date_id">+</button>
                                           <select class="khateeb-edit" data-fri="[[mi.date_id]]" ng-show="editing_mode && editing_fri == mi.date_id" ng-change="markKhateeb(mi.date_id)" ng-model="tempChoice">
                                               <option ng-repeat="op in available_ops" ng-value="[[op.id]]">[[op.name]]</option>
                                           </select>
                                       </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-isgh">Close</button>
                <button type="button" class="btn btn-isgh" style="margin: 5px;" ng-disabled="processing" ng-click="editSchedule()">Save</button><img src="/assets/images/loading.gif" alt="Loading" ng-show="processing">
            </div>
        </div>
    </div>
    @if(!isset($reviewer))
    <div class="text-right schedule-opts">
        <div class="text-center text-warning" ng-show="schedule_edited"><strong><u>You need to refresh to see your modifications to the schedule!</u></strong></div>
        <button class="btn btn-isgh approve" ng-show="schedule_generated && !schedule_approved" ng-disabled="processing" ng-click="approve_schedule()">Approve To Schedule</button><img src="/assets/images/loading.gif" alt="Loading Image" style="margin: 0 5px;" ng-show="processing">
        <button class="btn btn-isgh generate" ng-hide="schedule_generated" ng-click="generate()" ng-disabled="processing">Generate Schedule</button>
        <a href="/ExportSchedule" class="btn btn-isgh excel" ng-show="schedule_approved" ng-disabled="processing" target="_blank">Export Excel</a>

    </div>
    @endif

   @if(!isset($reviewer))
   <div class="text-center help-block" ng-show="!schedule_generated">
        <h4>[[msg]]</h4>
    </div>
    
    @else
    
    <div class="text-center help-block" ng-show="!schedule_generated">
        <h4>No Schedule is Generated Yet!</h4>
    </div>
    
   @endif

    <div class="schedule-wrapper">
        <table class="table table-bordered" ng-show="schedule_generated">

            <thead>

                <tr>
                    <th>Center</th>
                    <th ng-repeat="date in dates">[[date]]</th>
                    @if(!isset($reviewer))
                    <th>Options</th>
                    @endif
                </tr>

            </thead>

            <tbody>

                <tr ng-repeat="ic in schedule">
                    <td>[[ic.islamic_center.name]]</td>
                    <td class="text-center" ng-repeat="kh in ic.khutbahs">
                        <ul ng-show="kh.data.length" style="height: 100%">
                            <li ng-repeat="k in kh.data" style="height: 100%;">[[k.khateeb.name]]</li>
                        </ul>
                        <span ng-show="!kh.data.length">--</span>
                    </td>
                    @if(!isset($reviewer))
                    <td>
                        <button class="btn btn-isgh" data-toggle="modal" data-target="#editModal" data-ic="[[ic.islamic_center.name]]" ng-click="prep_edit($event)">Edit</button>
                    </td>
                    @endif
                </tr>

            </tbody>

        </table>
    </div>

    
</div>


@stop


@section("scripts")
<script src="/assets/js/controllers/schedule.js"></script>
<script>
    $(".content-holder").removeClass("col-sm-6 col-md-6 col-lg-6 col-sm-offset-3 col-md-offset-3 col-lg-offset-3")
                        .addClass("col-sm-12 col-md-12 col-lg-12");
</script>
@stop