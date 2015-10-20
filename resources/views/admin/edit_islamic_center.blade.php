@extends("templates.master")


@section("navigation")

<li><a href="/admin/members/create">Create new members</a></li>
<li><a href="/admin/islamic_center/create">Create islamic center</a></li>
<li><a href="/admin/schedule">Manage Schedule</a></li>
<li><a href="/auth/logout">Logout</a></li>

@stop


@section("pageTitle")

Edit/Delete Islamic Center

@stop


@section("content")

<table class="table table-bordered">
    
    <thead>
        
        <tr>
            <th>Name</th>
            <th>Options</th>
        </tr>
        
    </thead>
    
    <tbody>
        
        <tr>
            <td>Islamic Center</td>
            <td><button class="btn btn-isgh">Edit</button><button class="btn btn-isgh">Delete</button></td>
        </tr>
        
    </tbody>
    
</table>

@stop