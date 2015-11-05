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

<div class="text-right schedule-opts">
    
    <button class="btn btn-isgh">Approve To Schedule</button>
    <button class="btn btn-isgh">Export Excel</button>
    
</div>

<table class="schedule">
    
    <thead>
        
        <tr>
            <th>Date</th>
            <th>Khateeb Name</th>
            <th>Islamic Center</th>
            <th>Information</th>
            <th>Options</th>
        </tr>
        
    </thead>
    
    <tbody>
        
        <tr>
            <td>12/10/2015</td>
            <td>Ahmed</td>
            <td>River Oaks @ Eastside Street</td>
            <td>The khutbah starts at 12:10 P.M and ends at 12:30 P.M</td>
            <td><button class="btn btn-isgh">Edit</button></td>
        </tr>
        <tr>
            <td>12/10/2015</td>
            <td>Ahmed</td>
            <td>Masjid Bilal @ Adel Road</td>
            <td>The khutbah starts at 12:10 P.M and ends at 12:30 P.M</td>
            <td><button class="btn btn-isgh">Edit</button></td>
        </tr>
        
    </tbody>
    
</table>

@stop