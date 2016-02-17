@extends("templates.master")


@section("pageTitle")
Cycle
@stop

@section("navigation")

<li><a href="/auth/logout">Logout</a></li>

@stop


<style>
    h1 {
        font-size: 1.5em!important;
        margin-top: -50px!important;
    }
    
    .the-table {
        display: table;
        width: 100%;
        height: 100%;
    }
    
    .the-row {
        display: table-row;
        width: 100%;
        height: 100%;
    }
    
    .the-cell {
        display: table-cell;
        width: 100%;
        height: 100%;
        vertical-align: middle;
    }
    
</style>

@section("content")

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 text-center">
        <div class="the-table">
            <div class="the-row">
                <div class="the-cell">
                    <h1>There's no cycle running at the moment , Please return later!</h1>
                </div>
            </div>
        </div>
    </div>
</div>

@stop