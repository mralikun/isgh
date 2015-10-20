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


@section("content")

<table class="table schedule">
    
    <thead>
        
        <tr>
            <th>Date</th>
            <th>Khateeb Name</th>
            <th>Director Name</th>
            <th>Information</th>
        </tr>
        
    </thead>
    <tbody>
        
        <tr>
            <td>12/10/2015</td>
            <td>Ahmed</td>
            <td>Mohamed</td>
            <td>The khutbah starts at 12:10 P.M and ends at 12:30 P.M</td>
        </tr>
        
    </tbody>
    
</table>

@stop