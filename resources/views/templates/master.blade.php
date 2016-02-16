<!DOCTYPE html>
<html lang="en">
    <head>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Khutbah Rotation</title>
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/css/main.css">
        <link rel="stylesheet" href="/assets/css/fa/css/font-awesome.min.css">
        
    </head>
    
    
    <body ng-app="isgh">
       <div class="alert">
            <div class="content">
                <h4 class="message"></h4>
                <button class="accept btn-isgh" data-confirm="1">Yes</button><button class="decline btn-isgh" data-confirm="0">No</button>
            </div>
        </div>
        <div class="notification"><p></p></div>
        <nav class="navbar navbar-primary" role="navigation">
            <div class="navbar-header">
                <button type="button" data-target="#isghNav" data-toggle="collapse" class="navbar-toggle">
                    <span class="fa fa-bars"></span>
                </button>
                <a href="/" class="navbar-brand"><h2>Khutbah Rotation</h2></a>
            </div>
            <div class="collapse navbar-collapse" id="isghNav">
                <ul class="nav navbar-nav">
                    @yield("navigation")
                </ul>
            </div>
        </nav>
        
           <div class="row">

               <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                   <h2 class="page-title">@yield("pageTitle")</h2>
               </div>
             <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 pull-right">
                   @yield("aside")
               </div>
               <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                   @yield("content")
               </div>
               
           </div>
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