@extends("templates.master")


@section("navigation")

<li><a href="/admin/members/create">Create new members</a></li>
<li><a href="/admin/islamic_center/create">Create islamic center</a></li>
<li><a href="/admin/schedule">Manage Schedule</a></li>
<li><a href="/auth/logout">Logout</a></li>

@stop

@section("pageTitle")

Edit/Delete Members

@stop

@section("content")

<div class="tabs-links">
    
    <button data-content="2" class="active-tab">Khateebs</button>
    <button data-content="3">Associate Directors</button>
    
</div>

<div class="content-tabs" ng-controller="UserController as uc">
    
    <div class="tab-content" data-content="2">
       <h3>All Khateebs</h3>
        <table class="table table-bordered">

            <thead>

                <tr>
                    <th>Name</th>
                    <th>Options</th>
                </tr>

            </thead>

            <tbody>

                @foreach($all["khateebs"] as $khateeb)
                <tr ng-click="delete({_ev: $event, role: 2})">
                    
                    <td><h4>{{$khateeb->name}}</h4></td>
                    <td><button class="btn btn-isgh" data-member="{{$khateeb->id}}">Edit</button><button class="btn btn-isgh opt-delete" data-member="{{$khateeb->id}}">Delete</button></td>
                    
                </tr>
                
                @endforeach

            </tbody>

        </table>
    </div>
    
    <div class="tab-content" data-content="3">
        
       <h3>All Associate Directors</h3>
        <table class="table table-bordered">

            <thead>

                <tr>
                    <th>Name</th>
                    <th>Options</th>
                </tr>

            </thead>

            <tbody>

                @foreach($all["ads"] as $ad)
                <tr ng-click="delete({_ev: $event, role: 3})">
                    
                    <td><h4>{{$ad->name}}</h4></td>
                    <td><button class="btn btn-isgh" data-member="{{$ad->id}}">Edit</button><button class="btn btn-isgh opt-delete" data-member="{{$ad->id}}">Delete</button></td>
                    
                </tr>
                
                @endforeach

            </tbody>

        </table>
        
    </div>
    
</div>

@stop


@section("scripts")

<script>

    ISGH.Tabs.init();
    
</script>

@stop