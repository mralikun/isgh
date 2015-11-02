angular.module("isgh")
    .controller("UserController" , ["$scope" , "$http" , function( scope , http ){
        
        // we will make a request to get the online user's data.
        scope.updateInformation = function ( ){
            console.log($("#update-profile-form").serialize());
        }
        
    }]);