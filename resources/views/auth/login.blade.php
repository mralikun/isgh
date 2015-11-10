<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">
    <title>ISGH</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <script src="/assets/js/core/jquery.min.js"></script>
    <script src="/assets/js/core/bootstrap.min.js"></script>
    <style>
        .forgot {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255,255,255,0.6);
            z-index: 999;
            display: none;
        }
        
        #recover-password {
            width: 50%;
            margin-left: 25%;
            margin-top: 25%;
            transform: translateY(-50%);
            background-color: white;
            padding: 25px;
            text-align: center;
            box-shadow: 0 0 5px black;
        }
        
        .close {
            opacity: 0.3;
        }
        
    </style>

</head>

<body>

<header><h2>ISGH</h2></header>
<form action="{{ url('/auth/login') }}" method="POST" id="login-form">

    <img src="/assets/images/auth.jpg" alt="Unknown" id="auth-img">

    <legend>Login</legend>

    <div class="form-group">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="checkbox" name="remember"> <label> Remember Me</label>
        <a href="/password/email" id="forgot-password">Forgot Password?</a>
        <input type="submit" value="Login">
    </div>

</form>

<div class="forgot">
    <form method="POST" id="recover-password">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <button type="button" class="close">X</button>
        <div>
            <label>Please insert your email address, You will recive an email containing further instructions on how to reset your password.</label>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">E-mail: </label>
            <div class="col-sm-10"><input type="email" name="email" class="form-control" placeholder="E-mail Address"></div>
        </div>
        
        <input type="submit" class="btn btn-isgh" value="Send Reset Password Mail">
    </form>
</div>

</body>

</html>