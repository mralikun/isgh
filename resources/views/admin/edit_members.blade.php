@extends("templates.master")


@section("navigation")

<li><a href="/admin/members/create">Create new members</a></li>
<li><a href="/admin/islamic_center/create">Create islamic center</a></li>
<li><a href="/admin/schedule">Manage Schedule</a></li>

@stop

@section("pageTitle")

Edit/Delete Members

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
            <td>Name</td>
            <td><button class="btn btn-isgh">Edit</button><button class="btn btn-isgh">Delete</button></td>
        </tr>
        
    </tbody>
    
</table>

@stop