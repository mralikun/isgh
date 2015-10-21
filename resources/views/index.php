<!DOCTYPE html>

<html>
   
    <head>
        
        <meta charset="utf-8">
        <title>ISGH</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/main.css">
        <script src="assets/js/core/jquery.min.js"></script>
        <script src="assets/js/core/bootstrap.min.js"></script>
        
    </head>
    
    <body>
        
        <header><h2>ISGH</h2></header>
        <form action="/login" method="POST" id="login-form">

            <img src="assets/images/auth.jpg" alt="Unknown" id="auth-img">

            <legend>Login</legend>

            <div class="form-group">

                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="checkbox" name="remember"> <label> Remember Me</label>
                <a href="#">Forgot Password?</a>
                <input type="submit" value="Login">

            </div>

        </form>
        
    </body>

</html>