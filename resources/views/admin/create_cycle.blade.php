@extends("templates.master")


@section("navigation")

<li><a href="/auth/logout">Logout</a></li>

@stop


@section("pageTitle")

Create new cycle

@stop

@section("content")

<h4 class="text-center text-warning">Please note , you may create a new cycle 15 days before the current cycle's end , In this case all saved rating and dates selection of the previous cycle can't be edited and a new session will begin.</h4>

<form action="/admin/create_cycle" method="POST" class="form-horizontal">
    
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <h4 class="text-center">Please create a new cycle to start the system.</h4>
    <div class="form-group">
        
        <label class="control-label col-sm-2">Cycle Starting Date</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" name="cycle_start_date">
        </div>
        
    </div>
    
    <div class="form-group">
        <label class="control-label col-sm-2">Cycle length (Months)</label>
        <div class="col-sm-10">
            <input type="number" name="months" min="1" max="3" class="form-control" placeholder="Cycle length, How many months ?">
        </div>
    </div>
    
    <div class="form-group text-center">
        <input type="submit" class="btn btn-isgh" value="Start Cycle">
    </div>
    
</form>

@stop