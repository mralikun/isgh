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
                                         <select class="khateeb-edit" ng-repeat="kh in rec.data" data-prev-value="[[kh.khateeb.id]]" data-date="[[rec.data[0].friday_id]]" ng-model="pre_made" ng-change="handle_change()" ng-click="set_element($event)">
                                             <option value="0">--</option>
                                             <option value="[[kh.khateeb.id]]" selected>[[kh.khateeb.name]]</option>
                                         </select>
                                         <select class="khateeb-edit" ng-repeat="em in rec.missing" data-date="[[em.date_id]]" ng-model="somethingelse" ng-change="handle_change" ng-click="set_element($event)">
                                             <option value="0">--</option>
                                         </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-isgh">Close</button>
                <button type="button" class="btn btn-isgh" style="margin: 5px;" ng-disabled="processing">Save</button><img src="/assets/images/loading.gif" alt="Loading" ng-show="processing">
            </div>
        </div>
    </div>
    
    <div class="text-right schedule-opts">

        <button class="btn btn-isgh approve" ng-show="schedule_generated && !schedule_approved" ng-disabled="processing">Approve To Schedule</button>
        <button class="btn btn-isgh generate" ng-hide="schedule_generated" ng-click="generate()" ng-disabled="processing">Generate Schedule</button>
        <button class="btn btn-isgh excel" ng-show="schedule_approved" ng-disabled="processing">Export Excel</button>

    </div>

    <div class="text-center help-block" ng-show="!schedule_generated">
        <h4>[[msg]]</h4>
    </div>
    <div class="schedule-wrapper">
        <table class="table table-bordered" ng-show="schedule_generated">

            <thead>

                <tr>
                    <th>Center</th>
                    <th ng-repeat="date in dates">[[date]]</th>
                    <th>Options</th>
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
                    <td>
                        <button class="btn btn-isgh" data-toggle="modal" data-target="#editModal" data-ic="[[ic.islamic_center.name]]" ng-click="prep_edit($event)">Edit</button>
                    </td>
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