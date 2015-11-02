angular.module("isgh" , ["ngRoute"])
    .config(function($routeProvider){
        
        $routeProvider.when("/",{
            templateUrl: "assets/js/templates/profile.html",
            controller: "UserController"
        })
        .when("/user/edit_profile" , {
            templateUrl: "assets/js/templates/edit-profile.html",
            controller: "UserController"
        })
        .when("/user/rating" , {
            templateUrl: "assets/js/templates/rating.html",
            controller: "UserController"
        })
        .when("/user/dates" , {
            templateUrl: "assets/js/templates/dates.html",
            controller: "UserController"
        })
        .otherwise({
            redirectTo: "/"
        });
    
    });