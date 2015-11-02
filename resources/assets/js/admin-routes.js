angular.module( "isgh" , ["ngRoute"])
    .config(function($routeProvider){
        
        $routeProvider.when("/" , {
            templateUrl: "assets/js/templates/create_members.html",
            controller: function(){
                // Empty Controller
            }
        })
        
        .when("/create/islamic_center" , {
            templateUrl: "assets/js/templates/create_ic.html",
            controller: function(){}
        })
        .otherwise({
            redirectTo: "/"
        });
    
    });