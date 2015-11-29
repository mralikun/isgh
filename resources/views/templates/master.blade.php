<!DOCTYPE html>


<html lang="en">
    
    <head>
        
        <meta charset="utf-8">
        <title>Welcome to ISGH</title>
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/css/main.css">
        <link rel="stylesheet" href="/assets/css/fa/css/font-awesome.min.css">
        
    </head>
    
    
    <body ng-app="isgh">
       
        <header class="container-fluid">

            <div class="col-sm-4 col-md-2 col-lg-2"><h2>ISGH</h2></div>
            <div class="col-sm-8 col-md-10 col-lg-10">
                <nav>
                    <ul>
                    
                        @yield("navigation")
                    
                    </ul>
                </nav>

            </div>

        </header>
        
        <div class="alert">
            
            <div class="content">
                <h4 class="message"></h4>
                <button class="accept btn-isgh" data-confirm="1">Yes</button><button class="decline btn-isgh" data-confirm="0">No</button>
            </div>
            
        </div>
        
        <div class="notification"><p></p></div>
        
        <main>
   
            <h2 class="page-title">@yield("pageTitle")</h2>

            <div class="container-fluid">

                <div class="col-sm-6 col-md-6 col-lg-6 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">

                    @yield("content")

                </div>
                
                <div class="col-sm-3 col-md-3 col-lg-3">
                    @yield("aside")
                </div>
                
            </div>
        </main>    
        
        <script src="/assets/js/core/jquery.min.js"></script>
        <script src="/assets/js/core/angular.min.js"></script>
        <script src="/assets/js/core/bootstrap.min.js"></script>
        <script src="/assets/js/app.js"></script>
        <script src="/assets/js/core/angular-messages.min.js"></script>
        <script src="/assets/js/controllers/user.js"></script>
        <script src="/assets/js/controllers/islamicCenter.js"></script>
        <script>
        
            ISGH.init();
            
        </script>
        @yield("scripts")

        
    </body>

    
</html>