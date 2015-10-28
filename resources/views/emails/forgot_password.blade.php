@extends("templates.mail")


@section("mail_body")

<h2>Password Reset Request</h2>

<p>You've requested to reset your password please follow the following link to reset your password, Please notice this is a one time usage link and it will be expired once you've reseted your password.</p>
<a href="{{$link}}">Reset Password</a>

@stop