@extends("templates.master")


@section("navigation")

@stop


@section("pageTitle")

Profile

@stop


@section("content")

<div class="row">

<div class="col-sm-4 col-md-4 col-lg-4">
    <img src="assets/images/user.jpg" alt="User">
</div>
<div class="col-sm-8 col-md-8 col-lg-8 username"><h1>User name</h1></div>

</div>
<div class="row"><p class="bio">Biography</p></div>

@stop