@extends("templates.master")


@section("navigation")

<li><a href="/user/profile">View Profile</a></li>
<li><a href="/user/rating">Prefrences</a></li>
@if($role == 3)
<li><a href="/user/BlockedDates">Blocked Dates</a></li>
@endif
<li><a href="/user/dates">Available Dates as khateeb</a></li>
<li><a href="/user/edit_profile">Update Profile</a></li>
@if(isset($reviewer) and $reviewer == "true")
<li><a href="/admin/schedule">Review Schedule</a></li>
@endif

@if(isset($admin) and $admin == "true")
<li><a href="/admin/members/create">Create new members</a></li>
<li><a href="/admin/islamic_center/create">Create islamic center</a></li>
@endif
<li><a href="/auth/logout">Logout</a></li>

@stop

@section("pageTitle")

Blocked Dates

@stop


@section("content")

<div class="modal fade" id="visitor-name">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <strong>Please provide the visitor name...</strong>
                </h4>
            </div>
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
                       <label for="">Visitor Name</label>
                        <input type="text" placeholder="Visitor Name" nam="visitor_name" class="form-control" id="visitor_name_value">
                    </div>
                </div>
            </div>
            
            <div class="footer text-center" style="padding-bottom: 1em;">
                <button class="btn btn-isgh visitor_name_save">Save</button><button class="btn btn-isgh visitor-canceled" data-dismiss="modal">Cancel</button>
            </div>
            
        </div>
    </div>
</div>

<?php 

    function Map($arr , $cb){
        $mapped = [];
        foreach($arr as $a){
            array_push($mapped , $cb($a));
        }
        return $mapped;
    }

    $choosen = Map($fridays_choosen , function($fr){
        return $fr->friday_id;
    });

    $other_ics = Map($fridays_choosen_other_ic , function($fr){
        return $fr->friday_id;
    });

    $my_ic = Map($fridays_choosen_my_ic , function($fr){
        return $fr->friday_id;
    });
?>

<style>
    .date.available {
        background-color: #DD0E1D;
    }
</style>
<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <h3>{{$name}} @ {{$islamic_center->name}}</h3>

        <span class="hint"><strong>Check the box for all dates you want to <mark><em><u>Block</u></em></mark> from being assigned a khateeb by the system.</strong></span>
        
    </div>
</div>

<form id="blocked-dates-form">
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="dates-calendar">
      <?php $counter = 3;?>
       @foreach($fridays as $key => $friday)
       @if(in_array($friday->id , $choosen))
        <div class="date available" id="{{$friday->id}}">
            <div class="date-content">
               <h4>Friday</h4>
               <h5 class="visitor-name"><?php echo array_values(array_filter($fridays_choosen->toArray() , function($fr) use ($friday){
                    return $fr["friday_id"] == $friday->id;
                }))[0]["visitor_name"]; ?></h5>
               <h5>{{$friday->date}}</h5>
            </div>
        </div>
        <?php $counter = 0;?>
       @elseif(in_array($friday->id , $other_ics) || in_array($friday->id , $my_ic))
        <div class="date original-reserved reserved" id="{{$friday->id}}">
            <div class="date-content">
               <h4>Friday</h4>
               <h5>{{$friday->date}}</h5>
            </div>
        </div>
       <?php $counter ++; ?>
       @elseif(gmp_cmp(3 , $counter) == 1)
       <div class="date reserved" id="{{$friday->id}}">
            <div class="date-content">
               <h4>Friday</h4>
               <h5>{{$friday->date}}</h5>
            </div>
        </div>
       <?php $counter ++; ?>
       @else
       <div class="date" id="{{$friday->id}}">
            <div class="date-content">
               <h4>Friday</h4>
               <h5>{{$friday->date}}</h5>
            </div>
        </div>
       @endif
       @endforeach
    </div>
    <input type="submit" value="Submit" class="btn btn-primary pull-right" ng-disabled="dates.available.length == 0">
</form>
<audio src="/assets/alert.mp3"></audio>
@stop


@section("scripts")

<script>
    ISGH.Dates.init("/user/setBlockedDates/{{$islamic_center->id}}");
</script>

@stop